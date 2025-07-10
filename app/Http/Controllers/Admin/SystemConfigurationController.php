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
        $configurations = $request->get('configurations', []);

        foreach ($configurations as $key => $value) {
            $config = SystemConfiguration::where('key', $key)->first();
            
            if ($config) {
                // Handle file uploads
                if ($config->type === 'file' || $config->type === 'image') {
                    if ($request->hasFile("files.{$key}")) {
                        $file = $request->file("files.{$key}");
                        
                        // Delete old file if exists
                        if ($config->value && Storage::disk('public')->exists($config->value)) {
                            Storage::disk('public')->delete($config->value);
                        }
                        
                        // Store new file
                        $path = $file->store('configurations', 'public');
                        $value = $path;
                    } else {
                        $value = $config->value; // Keep existing value
                    }
                }
                
                // Handle boolean values
                if ($config->type === 'boolean') {
                    $value = $request->has("configurations.{$key}") ? true : false;
                }
                
                // Handle array values
                if ($config->type === 'array') {
                    $value = is_array($value) ? $value : explode(',', $value);
                }
                
                // Handle JSON values
                if ($config->type === 'json') {
                    $value = is_string($value) ? json_decode($value, true) : $value;
                }
                
                $config->update([
                    'value' => $value,
                    'updated_by' => auth()->id()
                ]);
            }
        }

        return redirect()->back()->with('success', 'Konfigurasi berhasil diperbarui.');
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
            }
        }
        
        // Handle boolean values
        if ($request->type === 'boolean') {
            $value = $request->has('value') ? true : false;
        }
        
        // Handle array values
        if ($request->type === 'array') {
            $value = is_array($value) ? $value : explode(',', $value);
        }
        
        // Handle JSON values
        if ($request->type === 'json') {
            $value = is_string($value) ? json_decode($value, true) : $value;
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

        return redirect()->route('admin.configurations.index', ['group' => $request->group])
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
