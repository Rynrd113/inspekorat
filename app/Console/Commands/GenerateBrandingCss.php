<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BrandingService;

class GenerateBrandingCss extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'branding:generate-css 
                            {--force : Force overwrite existing CSS file}
                            {--minify : Minify the output CSS}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate CSS variables from branding configuration';

    /**
     * Execute the console command.
     */
    public function handle(BrandingService $brandingService)
    {
        $this->info('Generating branding CSS variables...');

        try {
            // Get branding configuration
            $branding = $brandingService->getBrandingConfig();
            
            // Generate CSS content
            $css = $this->generateCssContent($branding);
            
            // Apply minification if requested
            if ($this->option('minify')) {
                $css = $this->minifyCss($css);
                $this->info('CSS minified successfully.');
            }

            // Define output path
            $outputPath = public_path('css/branding-variables.css');
            $backupPath = public_path('css/branding-variables.backup.css');

            // Check if file exists and backup if not forcing
            if (file_exists($outputPath) && !$this->option('force')) {
                if (!$this->confirm('CSS file already exists. Do you want to overwrite it?')) {
                    $this->info('Operation cancelled.');
                    return 0;
                }
            }

            // Backup existing file
            if (file_exists($outputPath)) {
                copy($outputPath, $backupPath);
                $this->info('Existing file backed up to branding-variables.backup.css');
            }

            // Write new CSS file
            file_put_contents($outputPath, $css);

            $this->info('✅ Branding CSS variables generated successfully!');
            $this->line("📁 File location: {$outputPath}");
            $this->line("📏 File size: " . $this->formatBytes(filesize($outputPath)));
            
            // Show color preview
            $this->showColorPreview($branding);
            
            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Error generating CSS: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Generate CSS content from branding configuration
     */
    private function generateCssContent(array $branding): string
    {
        $timestamp = now()->format('Y-m-d H:i:s');
        
        $css = "/*\n";
        $css .= " * Government Website Branding Variables\n";
        $css .= " * Auto-generated from Admin Branding Settings\n";
        $css .= " * Generated: {$timestamp}\n";
        $css .= " * DO NOT EDIT MANUALLY - Will be overwritten\n";
        $css .= " */\n\n";

        $css .= ":root {\n";
        $css .= "    /* Brand Colors */\n";
        $css .= "    --brand-primary: {$branding['primary_color']};\n";
        $css .= "    --brand-secondary: {$branding['secondary_color']};\n";
        $css .= "    --brand-accent: {$branding['accent_color']};\n";
        $css .= "    \n";
        
        if (isset($branding['gradient_start']) && isset($branding['gradient_end'])) {
            $css .= "    /* Gradients */\n";
            $css .= "    --brand-gradient-start: {$branding['gradient_start']};\n";
            $css .= "    --brand-gradient-end: {$branding['gradient_end']};\n";
            $css .= "    --brand-gradient: linear-gradient(135deg, var(--brand-gradient-start) 0%, var(--brand-gradient-end) 100%);\n";
            $css .= "    \n";
        }

        $css .= "    /* Accessibility Colors */\n";
        $css .= "    --text-on-primary: #ffffff;\n";
        $css .= "    --text-on-secondary: #ffffff;\n";
        $css .= "    --text-on-accent: #ffffff;\n";
        $css .= "    \n";

        $css .= "    /* Component Colors */\n";
        $css .= "    --btn-primary: var(--brand-primary);\n";
        $css .= "    --btn-primary-hover: color-mix(in srgb, var(--brand-primary) 80%, black);\n";
        $css .= "    --btn-secondary: var(--brand-secondary);\n";
        $css .= "    --btn-secondary-hover: color-mix(in srgb, var(--brand-secondary) 80%, black);\n";
        $css .= "    \n";

        $css .= "    /* Layout Colors */\n";
        $css .= "    --header-bg: var(--brand-primary);\n";
        $css .= "    --footer-bg: color-mix(in srgb, var(--brand-primary) 90%, black);\n";
        $css .= "    --sidebar-bg: var(--brand-gradient, var(--brand-primary));\n";
        $css .= "}\n\n";

        // Add utility classes
        $css .= "/* Utility Classes */\n";
        $css .= ".bg-brand-primary { background-color: var(--brand-primary); }\n";
        $css .= ".bg-brand-secondary { background-color: var(--brand-secondary); }\n";
        $css .= ".bg-brand-accent { background-color: var(--brand-accent); }\n";
        $css .= ".bg-brand-gradient { background: var(--brand-gradient); }\n\n";

        $css .= ".text-brand-primary { color: var(--brand-primary); }\n";
        $css .= ".text-brand-secondary { color: var(--brand-secondary); }\n";
        $css .= ".text-brand-accent { color: var(--brand-accent); }\n\n";

        $css .= ".border-brand-primary { border-color: var(--brand-primary); }\n";
        $css .= ".border-brand-secondary { border-color: var(--brand-secondary); }\n";
        $css .= ".border-brand-accent { border-color: var(--brand-accent); }\n\n";

        // Add button styles
        $css .= "/* Button Styles */\n";
        $css .= ".btn-brand-primary {\n";
        $css .= "    background-color: var(--btn-primary);\n";
        $css .= "    color: var(--text-on-primary);\n";
        $css .= "    border: 1px solid var(--btn-primary);\n";
        $css .= "    transition: all 0.3s ease;\n";
        $css .= "}\n\n";

        $css .= ".btn-brand-primary:hover {\n";
        $css .= "    background-color: var(--btn-primary-hover);\n";
        $css .= "    border-color: var(--btn-primary-hover);\n";
        $css .= "}\n\n";

        // Add responsive design
        $css .= "/* Responsive Design */\n";
        $css .= "@media (max-width: 768px) {\n";
        $css .= "    .logo-header { max-height: 40px; }\n";
        $css .= "    .logo-footer { max-height: 30px; }\n";
        $css .= "}\n\n";

        // Add accessibility features
        $css .= "/* Accessibility */\n";
        $css .= "@media (prefers-contrast: high) {\n";
        $css .= "    :root {\n";
        $css .= "        --brand-primary: #000080;\n";
        $css .= "        --brand-secondary: #006400;\n";
        $css .= "        --brand-accent: #8B0000;\n";
        $css .= "    }\n";
        $css .= "}\n\n";

        $css .= "@media (prefers-reduced-motion: reduce) {\n";
        $css .= "    *, *::before, *::after {\n";
        $css .= "        animation-duration: 0.01ms !important;\n";
        $css .= "        animation-iteration-count: 1 !important;\n";
        $css .= "        transition-duration: 0.01ms !important;\n";
        $css .= "    }\n";
        $css .= "}\n";

        return $css;
    }

    /**
     * Minify CSS content
     */
    private function minifyCss(string $css): string
    {
        // Remove comments
        $css = preg_replace('!/\*.*?\*/!s', '', $css);
        
        // Remove whitespace
        $css = preg_replace('/\s+/', ' ', $css);
        
        // Remove unnecessary spaces around selectors and brackets
        $css = preg_replace('/\s*{\s*/', '{', $css);
        $css = preg_replace('/;\s*}/', '}', $css);
        $css = preg_replace('/;\s*/', ';', $css);
        
        return trim($css);
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * Show color preview in terminal
     */
    private function showColorPreview(array $branding): void
    {
        $this->line('');
        $this->info('🎨 Color Preview:');
        $this->line("Primary:   {$branding['primary_color']}");
        $this->line("Secondary: {$branding['secondary_color']}");
        $this->line("Accent:    {$branding['accent_color']}");
        
        if (isset($branding['gradient_start'])) {
            $this->line("Gradient:  {$branding['gradient_start']} → {$branding['gradient_end']}");
        }
        
        $this->line('');
        $this->comment('💡 Tips:');
        $this->comment('- Include this CSS file in your layout: <link rel="stylesheet" href="/css/branding-variables.css">');
        $this->comment('- Use CSS variables in your stylesheets: background-color: var(--brand-primary);');
        $this->comment('- Run with --minify flag for production use');
    }
}