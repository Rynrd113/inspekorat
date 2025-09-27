<!DOCTYPE html>
<html>
<head>
    <title>Branding Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ccc; }
        .color-box { width: 50px; height: 50px; display: inline-block; margin: 5px; }
    </style>
</head>
<body>
    <h1>🎨 Branding System Test</h1>
    
    <div class="test-section">
        <h2>1. Branding Service Test</h2>
        <?php
        try {
            require_once __DIR__ . '/../bootstrap/app.php';
            $app = require_once __DIR__ . '/../bootstrap/app.php';
            
            echo "<p>✅ Laravel Bootstrap: OK</p>";
            
            $brandingService = $app->make(\App\Services\BrandingService::class);
            echo "<p>✅ BrandingService: OK</p>";
            
            $branding = $brandingService->getBrandingConfig();
            echo "<p>✅ Branding Config Loaded</p>";
            
            echo "<h3>Colors:</h3>";
            echo "<div class='color-box' style='background: {$branding['primary_color']}'></div> Primary: {$branding['primary_color']}<br>";
            echo "<div class='color-box' style='background: {$branding['secondary_color']}'></div> Secondary: {$branding['secondary_color']}<br>";
            echo "<div class='color-box' style='background: {$branding['accent_color']}'></div> Accent: {$branding['accent_color']}<br>";
            
            echo "<h3>Presets:</h3>";
            $presets = \App\Services\BrandingPresetService::getAccessiblePresets();
            foreach ($presets as $key => $preset) {
                echo "<p><strong>{$preset['name']}</strong>: {$preset['description']}</p>";
            }
            
        } catch (Exception $e) {
            echo "<p>❌ Error: " . $e->getMessage() . "</p>";
            echo "<p>File: " . $e->getFile() . ":" . $e->getLine() . "</p>";
        }
        ?>
    </div>
    
    <div class="test-section">
        <h2>2. CSS Variables Test</h2>
        <link rel="stylesheet" href="/css/branding-variables.css">
        <div style="background: var(--brand-primary); color: white; padding: 10px;">
            Primary Color Box (using CSS variable)
        </div>
        <div style="background: var(--brand-secondary); color: white; padding: 10px;">
            Secondary Color Box (using CSS variable)
        </div>
    </div>
    
    <div class="test-section">
        <h2>3. Direct Test Links</h2>
        <p><a href="/admin/branding">🔗 Admin Branding Page</a></p>
        <p><a href="/admin/branding/debug">🔗 Debug JSON</a></p>
        <p><a href="/admin/branding/presets">🔗 Color Presets JSON</a></p>
    </div>
</body>
</html>