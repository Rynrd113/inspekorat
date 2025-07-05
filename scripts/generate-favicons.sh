#!/bin/bash
# generate-favicons.sh - Generate favicons from logo
# Portal Inspektorat Papua Tengah

# Check if ImageMagick is installed
if ! command -v convert &> /dev/null; then
    echo "❌ ImageMagick is not installed!"
    echo "Please install ImageMagick first:"
    echo "  Ubuntu/Debian: sudo apt-get install imagemagick"
    echo "  macOS: brew install imagemagick"
    echo "  Windows: Download from https://imagemagick.org/script/download.php#windows"
    exit 1
fi

# Configuration
LOGO_FILE="public/logo.svg"
OUTPUT_DIR="public"

# Check if logo file exists
if [ ! -f "$LOGO_FILE" ]; then
    echo "❌ Logo file not found: $LOGO_FILE"
    echo "Please ensure you have logo.svg in the public directory"
    exit 1
fi

echo "🖼️ Generating favicons from $LOGO_FILE..."

# Create output directory if it doesn't exist
mkdir -p "$OUTPUT_DIR"

# Generate different sizes for standard favicons
echo "📱 Generating standard favicons..."
convert "$LOGO_FILE" -resize 16x16 "$OUTPUT_DIR/favicon-16x16.png"
convert "$LOGO_FILE" -resize 32x32 "$OUTPUT_DIR/favicon-32x32.png"
convert "$LOGO_FILE" -resize 48x48 "$OUTPUT_DIR/favicon-48x48.png"
convert "$LOGO_FILE" -resize 64x64 "$OUTPUT_DIR/favicon-64x64.png"
convert "$LOGO_FILE" -resize 128x128 "$OUTPUT_DIR/favicon-128x128.png"
convert "$LOGO_FILE" -resize 256x256 "$OUTPUT_DIR/favicon-256x256.png"

# Generate ICO file (multi-size icon)
echo "🔷 Generating favicon.ico..."
convert "$LOGO_FILE" -resize 32x32 "$OUTPUT_DIR/favicon.ico"

# Generate Apple Touch Icons
echo "🍎 Generating Apple Touch Icons..."
convert "$LOGO_FILE" -resize 57x57 "$OUTPUT_DIR/apple-touch-icon-57x57.png"
convert "$LOGO_FILE" -resize 60x60 "$OUTPUT_DIR/apple-touch-icon-60x60.png"
convert "$LOGO_FILE" -resize 72x72 "$OUTPUT_DIR/apple-touch-icon-72x72.png"
convert "$LOGO_FILE" -resize 76x76 "$OUTPUT_DIR/apple-touch-icon-76x76.png"
convert "$LOGO_FILE" -resize 114x114 "$OUTPUT_DIR/apple-touch-icon-114x114.png"
convert "$LOGO_FILE" -resize 120x120 "$OUTPUT_DIR/apple-touch-icon-120x120.png"
convert "$LOGO_FILE" -resize 144x144 "$OUTPUT_DIR/apple-touch-icon-144x144.png"
convert "$LOGO_FILE" -resize 152x152 "$OUTPUT_DIR/apple-touch-icon-152x152.png"
convert "$LOGO_FILE" -resize 180x180 "$OUTPUT_DIR/apple-touch-icon-180x180.png"

# Main Apple Touch Icon (default)
cp "$OUTPUT_DIR/apple-touch-icon-180x180.png" "$OUTPUT_DIR/apple-touch-icon.png"

# Generate Android Chrome Icons
echo "🤖 Generating Android Chrome Icons..."
convert "$LOGO_FILE" -resize 192x192 "$OUTPUT_DIR/android-chrome-192x192.png"
convert "$LOGO_FILE" -resize 512x512 "$OUTPUT_DIR/android-chrome-512x512.png"

# Generate Microsoft Tile Icons
echo "🪟 Generating Microsoft Tile Icons..."
convert "$LOGO_FILE" -resize 144x144 "$OUTPUT_DIR/mstile-144x144.png"
convert "$LOGO_FILE" -resize 150x150 "$OUTPUT_DIR/mstile-150x150.png"
convert "$LOGO_FILE" -resize 310x150 "$OUTPUT_DIR/mstile-310x150.png"
convert "$LOGO_FILE" -resize 310x310 "$OUTPUT_DIR/mstile-310x310.png"
convert "$LOGO_FILE" -resize 70x70 "$OUTPUT_DIR/mstile-70x70.png"

# Generate Web App Manifest
echo "📋 Generating Web App Manifest..."
cat > "$OUTPUT_DIR/site.webmanifest" << EOF
{
    "name": "Portal Inspektorat Papua Tengah",
    "short_name": "Inspektorat Papua",
    "description": "Portal resmi Inspektorat Provinsi Papua Tengah",
    "start_url": "/",
    "display": "standalone",
    "theme_color": "#003f7f",
    "background_color": "#ffffff",
    "icons": [
        {
            "src": "/android-chrome-192x192.png",
            "sizes": "192x192",
            "type": "image/png"
        },
        {
            "src": "/android-chrome-512x512.png",
            "sizes": "512x512",
            "type": "image/png"
        }
    ]
}
EOF

# Generate browserconfig.xml for Microsoft
echo "🔧 Generating browserconfig.xml..."
cat > "$OUTPUT_DIR/browserconfig.xml" << EOF
<?xml version="1.0" encoding="utf-8"?>
<browserconfig>
    <msapplication>
        <tile>
            <square70x70logo src="/mstile-70x70.png"/>
            <square150x150logo src="/mstile-150x150.png"/>
            <square310x310logo src="/mstile-310x310.png"/>
            <wide310x150logo src="/mstile-310x150.png"/>
            <TileColor>#003f7f</TileColor>
        </tile>
    </msapplication>
</browserconfig>
EOF

# Create HTML snippet for easy copy-paste
echo "📝 Generating HTML snippet..."
cat > "$OUTPUT_DIR/favicon-html.txt" << EOF
<!-- Standard Favicon -->
<link rel="icon" type="image/x-icon" href="/favicon.ico">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="48x48" href="/favicon-48x48.png">
<link rel="icon" type="image/png" sizes="64x64" href="/favicon-64x64.png">

<!-- Apple Touch Icons -->
<link rel="apple-touch-icon" href="/apple-touch-icon.png">
<link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png">

<!-- Android Chrome Icons -->
<link rel="icon" type="image/png" sizes="192x192" href="/android-chrome-192x192.png">
<link rel="icon" type="image/png" sizes="512x512" href="/android-chrome-512x512.png">

<!-- Web App Manifest -->
<link rel="manifest" href="/site.webmanifest">

<!-- Microsoft Tile -->
<meta name="msapplication-TileColor" content="#003f7f">
<meta name="msapplication-TileImage" content="/mstile-144x144.png">
<meta name="msapplication-config" content="/browserconfig.xml">

<!-- Theme Color -->
<meta name="theme-color" content="#003f7f">
EOF

echo "✅ Favicon generation completed!"
echo
echo "📁 Generated files:"
echo "   - Standard favicons (16x16 to 256x256)"
echo "   - Apple Touch Icons (57x57 to 180x180)"
echo "   - Android Chrome Icons (192x192, 512x512)"
echo "   - Microsoft Tile Icons"
echo "   - Web App Manifest (site.webmanifest)"
echo "   - Browser Config (browserconfig.xml)"
echo "   - HTML snippet (favicon-html.txt)"
echo
echo "📋 Next steps:"
echo "   1. Copy the HTML from favicon-html.txt"
echo "   2. Paste it into your layout template head section"
echo "   3. Test on different devices and browsers"
echo
echo "🌐 Online favicon checker:"
echo "   https://realfavicongenerator.net/favicon_checker"
