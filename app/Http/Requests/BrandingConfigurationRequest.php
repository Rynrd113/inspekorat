<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\BrandingPresetService;

class BrandingConfigurationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && 
               (auth()->user()->isSuperAdmin() || auth()->user()->hasRole(['admin', 'branding_manager']));
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'color_preset' => 'nullable|string|in:' . implode(',', array_keys(BrandingPresetService::getColorPresets())),
        ];

        // Only validate custom colors if preset is 'custom'
        if ($this->input('color_preset') === 'custom') {
            $rules = array_merge($rules, [
                'brand_primary_color' => [
                    'required',
                    'regex:/^#[a-fA-F0-9]{6}$/',
                    function ($attribute, $value, $fail) {
                        if (!BrandingPresetService::validateColorContrast($value)) {
                            $fail('Warna tidak memenuhi standar aksesibilitas WCAG.');
                        }
                    },
                ],
                'brand_secondary_color' => [
                    'required',
                    'regex:/^#[a-fA-F0-9]{6}$/',
                    function ($attribute, $value, $fail) {
                        if (!BrandingPresetService::validateColorContrast($value)) {
                            $fail('Warna tidak memenuhi standar aksesibilitas WCAG.');
                        }
                    },
                ],
                'brand_accent_color' => [
                    'required',
                    'regex:/^#[a-fA-F0-9]{6}$/',
                    function ($attribute, $value, $fail) {
                        if (!BrandingPresetService::validateColorContrast($value)) {
                            $fail('Warna tidak memenuhi standar aksesibilitas WCAG.');
                        }
                    },
                ],
            ]);
        }

        // Optional gradient colors (only for advanced presets)
        $rules = array_merge($rules, [
            'brand_gradient_start' => 'nullable|regex:/^#[a-fA-F0-9]{6}$/',
            'brand_gradient_end' => 'nullable|regex:/^#[a-fA-F0-9]{6}$/',
            'brand_theme_mode' => 'nullable|in:light,dark',
        ]);

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'color_preset.in' => 'Preset warna yang dipilih tidak valid.',
            'brand_primary_color.required' => 'Warna utama wajib diisi untuk kustomisasi.',
            'brand_primary_color.regex' => 'Format warna utama tidak valid. Gunakan format #RRGGBB.',
            'brand_secondary_color.required' => 'Warna sekunder wajib diisi untuk kustomisasi.',
            'brand_secondary_color.regex' => 'Format warna sekunder tidak valid. Gunakan format #RRGGBB.',
            'brand_accent_color.required' => 'Warna aksen wajib diisi untuk kustomisasi.',
            'brand_accent_color.regex' => 'Format warna aksen tidak valid. Gunakan format #RRGGBB.',
            'brand_gradient_start.regex' => 'Format warna gradient awal tidak valid.',
            'brand_gradient_end.regex' => 'Format warna gradient akhir tidak valid.',
            'brand_theme_mode.in' => 'Mode tema harus light atau dark.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'color_preset' => 'preset warna',
            'brand_primary_color' => 'warna utama',
            'brand_secondary_color' => 'warna sekunder', 
            'brand_accent_color' => 'warna aksen',
            'brand_gradient_start' => 'warna gradient awal',
            'brand_gradient_end' => 'warna gradient akhir',
            'brand_theme_mode' => 'mode tema',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Additional cross-field validation
            if ($this->input('color_preset') === 'custom') {
                $this->validateCustomColorCombination($validator);
            }
            
            // Validate gradient colors if provided
            if ($this->filled(['brand_gradient_start', 'brand_gradient_end'])) {
                $this->validateGradientColors($validator);
            }
        });
    }

    /**
     * Validate custom color combination
     */
    private function validateCustomColorCombination($validator): void
    {
        $primary = $this->input('brand_primary_color');
        $secondary = $this->input('brand_secondary_color');
        $accent = $this->input('brand_accent_color');

        // Check if colors are too similar (simplified check)
        if ($primary === $secondary || $primary === $accent || $secondary === $accent) {
            $validator->errors()->add('brand_primary_color', 'Warna utama, sekunder, dan aksen harus berbeda.');
        }

        // Check for too bright colors (accessibility concern)
        $brightColors = ['#ffffff', '#fffff0', '#ffffe0', '#f0f8ff', '#f5f5f5'];
        foreach ([$primary, $secondary, $accent] as $color) {
            if (in_array($color, $brightColors)) {
                $validator->errors()->add('brand_primary_color', 'Warna terlalu terang untuk aksesibilitas yang baik.');
                break;
            }
        }
    }

    /**
     * Validate gradient colors
     */
    private function validateGradientColors($validator): void
    {
        $start = $this->input('brand_gradient_start');
        $end = $this->input('brand_gradient_end');

        // Ensure gradient colors are not identical
        if ($start === $end) {
            $validator->errors()->add('brand_gradient_end', 'Warna gradient awal dan akhir harus berbeda.');
        }

        // Validate contrast for gradients
        foreach ([$start, $end] as $color) {
            if ($color && !BrandingPresetService::validateColorContrast($color)) {
                $validator->errors()->add('brand_gradient_start', 'Warna gradient tidak memenuhi standar aksesibilitas.');
                break;
            }
        }
    }
}