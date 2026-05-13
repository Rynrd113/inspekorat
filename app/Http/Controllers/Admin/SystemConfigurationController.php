<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SystemConfigurationController extends Controller
{
    private const SUPER_ADMIN_GROUPS = ['mail', 'technical'];

    private function isSuperAdmin(): bool
    {
        return auth()->user()->hasRole('super_admin');
    }

    private function denyIfRestricted(string $group): void
    {
        if (in_array($group, self::SUPER_ADMIN_GROUPS) && !$this->isSuperAdmin()) {
            abort(403, 'Grup konfigurasi ini hanya dapat diakses oleh Super Admin.');
        }
    }

    private function visibleGroups(): array|null
    {
        if ($this->isSuperAdmin()) {
            return null;
        }
        return array_diff(['identity', 'contact', 'social', 'seo', 'general', 'performance', 'maintenance'], []);
    }

    public function index(Request $request)
    {
        $query = SystemConfiguration::query();

        if (!$this->isSuperAdmin()) {
            $query->whereNotIn('group', self::SUPER_ADMIN_GROUPS);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('key', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('value', 'like', "%{$search}%");
            });
        }

        if ($request->filled('group')) {
            $this->denyIfRestricted($request->group);
            $query->where('group', $request->group);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $configurations = $query->orderBy('group')->orderBy('key')->paginate(20);

        return view('admin.configurations.index', compact('configurations'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id'          => 'required|exists:system_configurations,id',
            'key'         => 'required|string',
            'type'        => 'required|string',
            'value'       => 'nullable',
            'group'       => 'required|string',
            'description' => 'nullable|string',
            'is_public'   => 'boolean',
        ]);

        $this->denyIfRestricted($request->group);

        $config = SystemConfiguration::findOrFail($request->id);
        $this->denyIfRestricted($config->group);

        $value = $request->value;

        if ($request->type === 'file' || $request->type === 'image') {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $maxSize = $request->type === 'image' ? 5120 : 10240;
                if ($file->getSize() > $maxSize * 1024) {
                    return redirect()->back()->with('error', 'Ukuran file terlalu besar.');
                }
                $allowedTypes = $request->type === 'image'
                    ? ['jpeg', 'png', 'jpg', 'gif', 'webp']
                    : ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt'];
                if (!in_array(strtolower($file->getClientOriginalExtension()), $allowedTypes)) {
                    return redirect()->back()->with('error', 'Tipe file tidak diizinkan.');
                }
                if ($config->value && Storage::disk('public')->exists($config->value)) {
                    Storage::disk('public')->delete($config->value);
                }
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9\.]/', '', $file->getClientOriginalName());
                $value = $file->storeAs('configurations', $filename, 'public');
            } else {
                $value = $config->value;
            }
        }

        if ($request->type === 'boolean') {
            $value = $request->has('value') && in_array(strtolower((string) $request->value), ['true', '1', 'yes', 'on']);
        }

        if ($request->type === 'array' && is_string($value)) {
            $value = explode(',', $value);
        }

        if ($request->type === 'json' && is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $value = $decoded;
            }
        }

        $config->update([
            'key'         => $request->key,
            'value'       => $value,
            'type'        => $request->type,
            'group'       => $request->group,
            'description' => $request->description,
            'is_public'   => $request->boolean('is_public'),
            'updated_by'  => auth()->id(),
        ]);

        Cache::forget('sys_config_' . $request->key);

        return redirect()->route('admin.configurations.index')
            ->with('success', 'Konfigurasi berhasil diperbarui.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'key'         => 'required|string|unique:system_configurations,key',
            'value'       => 'nullable',
            'type'        => 'required|string',
            'description' => 'nullable|string',
            'group'       => 'required|string',
            'is_public'   => 'boolean',
        ]);

        $this->denyIfRestricted($request->group);

        $value = $request->value;

        if ($request->type === 'file' || $request->type === 'image') {
            $value = $request->hasFile('file') ? $request->file('file')->store('configurations', 'public') : null;
        }

        if ($request->type === 'boolean') {
            $value = $request->has('value') && in_array(strtolower((string) $request->value), ['true', '1', 'yes', 'on']);
        }

        if ($request->type === 'array' && is_string($value)) {
            $value = explode(',', $value);
        }

        if ($request->type === 'json' && is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $value = $decoded;
            }
        }

        SystemConfiguration::create([
            'key'         => $request->key,
            'value'       => $value,
            'type'        => $request->type,
            'description' => $request->description,
            'group'       => $request->group,
            'is_public'   => $request->boolean('is_public'),
            'updated_by'  => auth()->id(),
        ]);

        Cache::forget('sys_config_' . $request->key);

        return redirect()->route('admin.configurations.index')
            ->with('success', 'Konfigurasi berhasil ditambahkan.');
    }

    public function destroy(SystemConfiguration $configuration)
    {
        $this->denyIfRestricted($configuration->group);

        if (in_array($configuration->type, ['file', 'image']) && $configuration->value
            && Storage::disk('public')->exists($configuration->value)) {
            Storage::disk('public')->delete($configuration->value);
        }

        Cache::forget('sys_config_' . $configuration->key);
        $configuration->delete();

        return redirect()->back()->with('success', 'Konfigurasi berhasil dihapus.');
    }

    public function initialize()
    {
        if (!$this->isSuperAdmin()) {
            abort(403);
        }
        SystemConfiguration::initializeDefaults();
        return redirect()->route('admin.configurations.index')
            ->with('success', 'Konfigurasi default berhasil diinisialisasi.');
    }

    public function export()
    {
        $query = SystemConfiguration::query();
        if (!$this->isSuperAdmin()) {
            $query->whereNotIn('group', self::SUPER_ADMIN_GROUPS);
        }
        $configurations = $query->get();

        $data = $configurations->map(fn($c) => [
            'key'         => $c->key,
            'value'       => $c->value,
            'type'        => $c->type,
            'description' => $c->description,
            'group'       => $c->group,
            'is_public'   => $c->is_public,
        ]);

        return response()->json($data, 200, [
            'Content-Type'        => 'application/json',
            'Content-Disposition' => 'attachment; filename="system_configurations_' . now()->format('Y-m-d_H-i-s') . '.json"',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:json']);

        $configurations = json_decode(file_get_contents($request->file('file')->path()), true);
        if (!is_array($configurations)) {
            return redirect()->back()->with('error', 'Format file tidak valid.');
        }

        foreach ($configurations as $config) {
            $this->denyIfRestricted($config['group'] ?? '');
            SystemConfiguration::updateOrCreate(
                ['key' => $config['key']],
                [
                    'value'       => $config['value'],
                    'type'        => $config['type'],
                    'description' => $config['description'],
                    'group'       => $config['group'],
                    'is_public'   => $config['is_public'],
                    'updated_by'  => auth()->id(),
                ]
            );
            Cache::forget('sys_config_' . $config['key']);
        }

        return redirect()->route('admin.configurations.index')
            ->with('success', 'Konfigurasi berhasil diimport.');
    }
}
