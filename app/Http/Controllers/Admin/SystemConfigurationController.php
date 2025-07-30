<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SystemConfigurationController extends Controller
{
    /**
     * Display a listing of configurations
     */
    public function index(Request $request)
    {
        $configurations = SystemConfiguration::orderBy('key')->paginate(20);

        return view('admin.configurations.index', compact('configurations'));
    }

    /**
     * Update configurations
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:system_configurations,id',
            'key' => 'required|string',
            'type' => 'required|string',
            'value' => 'nullable',
            'group' => 'required|string',
            'description' => 'nullable|string',
            'is_public' => 'boolean'
        ]);

        $config = SystemConfiguration::findOrFail($request->id);
        $value = $request->value;

        // Handle file uploads
        if ($request->type === 'file' || $request->type === 'image') {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                
                // Security validation
                $maxSize = $request->type === 'image' ? 5120 : 10240; // KB
                if ($file->getSize() > $maxSize * 1024) {
                    return redirect()->back()->with('error', 'Ukuran file terlalu besar.');
                }
                
                // Validate file type
                $allowedTypes = $request->type === 'image' 
                    ? ['jpeg', 'png', 'jpg', 'gif', 'webp']
                    : ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt'];
                    
                if (!in_array(strtolower($file->getClientOriginalExtension()), $allowedTypes)) {
                    return redirect()->back()->with('error', 'Tipe file tidak diizinkan.');
                }
                
                // Delete old file if exists
                if ($config->value && Storage::disk('public')->exists($config->value)) {
                    Storage::disk('public')->delete($config->value);
                }
                
                // Generate secure filename
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9\.]/', '', $file->getClientOriginalName());
                
                // Store new file
                $path = $file->storeAs('configurations', $filename, 'public');
                $value = $path;
            } else {
                $value = $config->value; // Keep existing value
            }
        }
        
        // Handle boolean values
        if ($request->type === 'boolean') {
            $value = $request->has('value') && in_array(strtolower($request->value), ['true', '1', 'yes', 'on']) ? true : false;
        }
        
        // Handle array values
        if ($request->type === 'array') {
            if (is_string($value)) {
                $value = explode(',', $value);
            }
        }
        
        // Handle JSON values
        if ($request->type === 'json') {
            if (is_string($value)) {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $value = $decoded;
                }
            }
        }
        
        $config->update([
            'key' => $request->key,
            'value' => $value,
            'type' => $request->type,
            'group' => $request->group,
            'description' => $request->description,
            'is_public' => $request->boolean('is_public'),
            'updated_by' => auth()->id()
        ]);

        return redirect()->route('admin.configurations.index')
            ->with('success', 'Konfigurasi berhasil diperbarui.');
    }

    /**
     * Create new configuration
     */
    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|unique:system_configurations,key',
            'value' => 'nullable',
            'type' => 'required|string',
            'description' => 'nullable|string',
            'group' => 'required|string',
            'is_public' => 'boolean'
        ]);

        $value = $request->value;
        
        // Handle file uploads
        if ($request->type === 'file' || $request->type === 'image') {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $value = $file->store('configurations', 'public');
            } else {
                $value = null;
            }
        }
        
        // Handle boolean values
        if ($request->type === 'boolean') {
            $value = $request->has('value') && in_array(strtolower($request->value), ['true', '1', 'yes', 'on']) ? true : false;
        }
        
        // Handle array values
        if ($request->type === 'array') {
            if (is_string($value)) {
                $value = explode(',', $value);
            }
        }
        
        // Handle JSON values
        if ($request->type === 'json') {
            if (is_string($value)) {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $value = $decoded;
                }
            }
        }

        SystemConfiguration::create([
            'key' => $request->key,
            'value' => $value,
            'type' => $request->type,
            'description' => $request->description,
            'group' => $request->group,
            'is_public' => $request->boolean('is_public'),
            'updated_by' => auth()->id()
        ]);

        return redirect()->route('admin.configurations.index')
            ->with('success', 'Konfigurasi berhasil ditambahkan.');
    }

    /**
     * Delete configuration
     */
    public function destroy(SystemConfiguration $configuration)
    {
        // Delete file if exists
        if (($configuration->type === 'file' || $configuration->type === 'image') 
            && $configuration->value 
            && Storage::disk('public')->exists($configuration->value)) {
            Storage::disk('public')->delete($configuration->value);
        }

        $configuration->delete();

        return redirect()->back()->with('success', 'Konfigurasi berhasil dihapus.');
    }

    /**
     * Initialize default configurations
     */
    public function initialize()
    {
        SystemConfiguration::initializeDefaults();
        
        return redirect()->route('admin.configurations.index')
            ->with('success', 'Konfigurasi default berhasil diinisialisasi.');
    }

    /**
     * Export configurations
     */
    public function export()
    {
        $configurations = SystemConfiguration::all();
        
        $filename = 'system_configurations_' . now()->format('Y-m-d_H-i-s') . '.json';
        
        $data = $configurations->map(function($config) {
            return [
                'key' => $config->key,
                'value' => $config->value,
                'type' => $config->type,
                'description' => $config->description,
                'group' => $config->group,
                'is_public' => $config->is_public
            ];
        });

        $headers = [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->json($data, 200, $headers);
    }

    /**
     * Import configurations
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:json'
        ]);

        $content = file_get_contents($request->file('file')->path());
        $configurations = json_decode($content, true);

        if (!is_array($configurations)) {
            return redirect()->back()->with('error', 'Format file tidak valid.');
        }

        foreach ($configurations as $config) {
            SystemConfiguration::updateOrCreate(
                ['key' => $config['key']],
                [
                    'value' => $config['value'],
                    'type' => $config['type'],
                    'description' => $config['description'],
                    'group' => $config['group'],
                    'is_public' => $config['is_public'],
                    'updated_by' => auth()->id()
                ]
            );
        }

        return redirect()->route('admin.configurations.index')
            ->with('success', 'Konfigurasi berhasil diimport.');
    }
}
