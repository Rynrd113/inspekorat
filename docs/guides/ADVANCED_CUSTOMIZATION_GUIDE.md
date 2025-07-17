# Advanced Customization & Extension Guide
## Portal Inspektorat Papua Tengah

### Table of Contents
1. [Advanced Theme Customization](#advanced-theme-customization)
2. [Plugin System](#plugin-system)
3. [Custom Middleware](#custom-middleware)
4. [Event System](#event-system)
5. [Notification System](#notification-system)
6. [File Upload & Management](#file-upload--management)
7. [Caching Strategies](#caching-strategies)
8. [Security Enhancements](#security-enhancements)
9. [Multi-language Support](#multi-language-support)
10. [Advanced Monitoring](#advanced-monitoring)

## Advanced Theme Customization

### Dynamic Theme System

#### Create Theme Service
```php
// app/Services/ThemeService.php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class ThemeService
{
    protected $themePath;
    protected $activeTheme;

    public function __construct()
    {
        $this->themePath = resource_path('themes');
        $this->activeTheme = config('app.theme', 'default');
    }

    public function getActiveTheme(): string
    {
        return $this->activeTheme;
    }

    public function setActiveTheme(string $theme): void
    {
        $this->activeTheme = $theme;
        Cache::put('active_theme', $theme, now()->addDays(30));
    }

    public function getThemeConfig(string $theme = null): array
    {
        $theme = $theme ?? $this->activeTheme;
        $configPath = $this->themePath . "/{$theme}/theme.json";
        
        if (File::exists($configPath)) {
            return json_decode(File::get($configPath), true);
        }
        
        return $this->getDefaultConfig();
    }

    public function getThemeAssets(string $theme = null): array
    {
        $theme = $theme ?? $this->activeTheme;
        $config = $this->getThemeConfig($theme);
        
        return [
            'css' => collect($config['assets']['css'] ?? [])->map(function ($file) use ($theme) {
                return asset("themes/{$theme}/css/{$file}");
            })->toArray(),
            'js' => collect($config['assets']['js'] ?? [])->map(function ($file) use ($theme) {
                return asset("themes/{$theme}/js/{$file}");
            })->toArray(),
        ];
    }

    public function compileTheme(string $theme): void
    {
        $sourcePath = $this->themePath . "/{$theme}/src";
        $distPath = public_path("themes/{$theme}");
        
        // Compile SCSS to CSS
        $this->compileSass($sourcePath, $distPath);
        
        // Minify JavaScript
        $this->compileJavaScript($sourcePath, $distPath);
        
        // Optimize images
        $this->optimizeImages($sourcePath, $distPath);
    }

    protected function getDefaultConfig(): array
    {
        return [
            'name' => 'Default Theme',
            'version' => '1.0.0',
            'colors' => [
                'primary' => '#3b82f6',
                'secondary' => '#64748b',
                'accent' => '#f59e0b',
            ],
            'typography' => [
                'font_family' => 'Inter, sans-serif',
                'font_sizes' => [
                    'xs' => '0.75rem',
                    'sm' => '0.875rem',
                    'base' => '1rem',
                    'lg' => '1.125rem',
                    'xl' => '1.25rem',
                ]
            ],
            'assets' => [
                'css' => ['app.css'],
                'js' => ['app.js']
            ]
        ];
    }
}
```

#### Theme Configuration File
```json
// resources/themes/inspektorat/theme.json
{
  "name": "Inspektorat Papua Tengah Theme",
  "version": "1.0.0",
  "description": "Official theme for Portal Inspektorat Papua Tengah",
  "author": "Development Team",
  "colors": {
    "primary": "#1e40af",
    "primary_dark": "#1e3a8a",
    "primary_light": "#3b82f6",
    "secondary": "#059669",
    "secondary_dark": "#047857",
    "secondary_light": "#10b981",
    "accent": "#dc2626",
    "warning": "#f59e0b",
    "success": "#10b981",
    "danger": "#ef4444",
    "info": "#06b6d4",
    "gray": {
      "50": "#f9fafb",
      "100": "#f3f4f6",
      "200": "#e5e7eb",
      "300": "#d1d5db",
      "400": "#9ca3af",
      "500": "#6b7280",
      "600": "#4b5563",
      "700": "#374151",
      "800": "#1f2937",
      "900": "#111827"
    }
  },
  "typography": {
    "font_family": "Inter, -apple-system, BlinkMacSystemFont, sans-serif",
    "font_sizes": {
      "xs": "0.75rem",
      "sm": "0.875rem",
      "base": "1rem",
      "lg": "1.125rem",
      "xl": "1.25rem",
      "2xl": "1.5rem",
      "3xl": "1.875rem",
      "4xl": "2.25rem"
    },
    "font_weights": {
      "light": 300,
      "normal": 400,
      "medium": 500,
      "semibold": 600,
      "bold": 700
    },
    "line_heights": {
      "none": 1,
      "tight": 1.25,
      "snug": 1.375,
      "normal": 1.5,
      "relaxed": 1.625,
      "loose": 2
    }
  },
  "spacing": {
    "xs": "0.25rem",
    "sm": "0.5rem",
    "md": "1rem",
    "lg": "1.5rem",
    "xl": "2rem",
    "2xl": "3rem",
    "3xl": "4rem"
  },
  "breakpoints": {
    "sm": "640px",
    "md": "768px",
    "lg": "1024px",
    "xl": "1280px",
    "2xl": "1536px"
  },
  "components": {
    "button": {
      "border_radius": "0.375rem",
      "padding": {
        "sm": "0.5rem 0.75rem",
        "md": "0.75rem 1rem",
        "lg": "1rem 1.5rem"
      }
    },
    "card": {
      "border_radius": "0.5rem",
      "shadow": "0 1px 3px 0 rgba(0, 0, 0, 0.1)",
      "padding": "1.5rem"
    },
    "form": {
      "input_height": "2.5rem",
      "border_radius": "0.375rem",
      "border_width": "1px"
    }
  },
  "assets": {
    "css": [
      "app.css",
      "components.css"
    ],
    "js": [
      "app.js",
      "components.js"
    ],
    "fonts": [
      "https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
    ]
  },
  "layout": {
    "container_max_width": "1200px",
    "sidebar_width": "280px",
    "header_height": "64px",
    "footer_height": "auto"
  },
  "animations": {
    "transition_duration": "200ms",
    "ease": "cubic-bezier(0.4, 0, 0.2, 1)"
  }
}
```

#### Theme Blade Directive
```php
// app/Providers/ThemeServiceProvider.php
<?php

namespace App\Providers;

use App\Services\ThemeService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ThemeService::class);
    }

    public function boot()
    {
        // Register theme directive
        Blade::directive('theme', function ($expression) {
            return "<?php echo app(App\Services\ThemeService::class)->getThemeConfig({$expression}); ?>";
        });

        // Register theme assets directive
        Blade::directive('themeAssets', function () {
            return "<?php
                \$assets = app(App\Services\ThemeService::class)->getThemeAssets();
                foreach (\$assets['css'] as \$css) {
                    echo '<link rel=\"stylesheet\" href=\"' . \$css . '\">';
                }
                foreach (\$assets['js'] as \$js) {
                    echo '<script src=\"' . \$js . '\"></script>';
                }
            ?>";
        });

        // Register theme color directive
        Blade::directive('themeColor', function ($expression) {
            return "<?php 
                \$config = app(App\Services\ThemeService::class)->getThemeConfig();
                \$keys = explode('.', {$expression});
                \$value = \$config['colors'];
                foreach (\$keys as \$key) {
                    \$value = \$value[\$key] ?? null;
                }
                echo \$value;
            ?>";
        });
    }
}
```

### Theme Usage in Templates
```php
<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Portal Inspektorat Papua Tengah')</title>
    
    <!-- Theme Assets -->
    @themeAssets
    
    <!-- Theme Variables -->
    <style>
        :root {
            --color-primary: @themeColor('primary');
            --color-secondary: @themeColor('secondary');
            --color-accent: @themeColor('accent');
            --font-family: '{{ @theme()['typography']['font_family'] }}';
        }
    </style>
</head>
<body class="font-sans bg-gray-50">
    <header class="bg-white shadow-sm" style="background-color: @themeColor('primary')">
        <!-- Header content -->
    </header>
    
    <main>
        @yield('content')
    </main>
    
    <footer class="bg-gray-800 text-white">
        <!-- Footer content -->
    </footer>
</body>
</html>
```

## Plugin System

### Plugin Architecture
```php
// app/Contracts/PluginInterface.php
<?php

namespace App\Contracts;

interface PluginInterface
{
    public function getName(): string;
    public function getVersion(): string;
    public function getDescription(): string;
    public function install(): void;
    public function uninstall(): void;
    public function activate(): void;
    public function deactivate(): void;
    public function isActive(): bool;
}
```

### Base Plugin Class
```php
// app/Core/BasePlugin.php
<?php

namespace App\Core;

use App\Contracts\PluginInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

abstract class BasePlugin implements PluginInterface
{
    protected $config;
    protected $path;

    public function __construct(string $path)
    {
        $this->path = $path;
        $this->loadConfig();
    }

    protected function loadConfig(): void
    {
        $configPath = $this->path . '/plugin.json';
        
        if (File::exists($configPath)) {
            $this->config = json_decode(File::get($configPath), true);
        }
    }

    public function getName(): string
    {
        return $this->config['name'] ?? 'Unknown Plugin';
    }

    public function getVersion(): string
    {
        return $this->config['version'] ?? '1.0.0';
    }

    public function getDescription(): string
    {
        return $this->config['description'] ?? '';
    }

    public function isActive(): bool
    {
        return Cache::get("plugin.{$this->getName()}.active", false);
    }

    public function activate(): void
    {
        Cache::put("plugin.{$this->getName()}.active", true);
        $this->onActivate();
    }

    public function deactivate(): void
    {
        Cache::put("plugin.{$this->getName()}.active", false);
        $this->onDeactivate();
    }

    protected function onActivate(): void
    {
        // Override in child classes
    }

    protected function onDeactivate(): void
    {
        // Override in child classes
    }

    protected function registerRoutes(): void
    {
        $routesPath = $this->path . '/routes.php';
        
        if (File::exists($routesPath)) {
            require $routesPath;
        }
    }

    protected function registerViews(): void
    {
        $viewsPath = $this->path . '/views';
        
        if (File::isDirectory($viewsPath)) {
            view()->addNamespace($this->getName(), $viewsPath);
        }
    }

    protected function publishAssets(): void
    {
        $assetsPath = $this->path . '/assets';
        $publicPath = public_path("plugins/{$this->getName()}");
        
        if (File::isDirectory($assetsPath)) {
            File::copyDirectory($assetsPath, $publicPath);
        }
    }
}
```

### Plugin Manager Service
```php
// app/Services/PluginManager.php
<?php

namespace App\Services;

use App\Core\BasePlugin;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class PluginManager
{
    protected $pluginsPath;
    protected $plugins;

    public function __construct()
    {
        $this->pluginsPath = base_path('plugins');
        $this->plugins = collect();
        $this->loadPlugins();
    }

    protected function loadPlugins(): void
    {
        if (!File::isDirectory($this->pluginsPath)) {
            File::makeDirectory($this->pluginsPath, 0755, true);
            return;
        }

        $pluginDirs = File::directories($this->pluginsPath);

        foreach ($pluginDirs as $dir) {
            $pluginFile = $dir . '/' . basename($dir) . '.php';
            
            if (File::exists($pluginFile)) {
                require_once $pluginFile;
                
                $className = 'Plugins\\' . basename($dir) . '\\' . basename($dir);
                
                if (class_exists($className)) {
                    $plugin = new $className($dir);
                    $this->plugins->put($plugin->getName(), $plugin);
                }
            }
        }
    }

    public function getAllPlugins(): Collection
    {
        return $this->plugins;
    }

    public function getActivePlugins(): Collection
    {
        return $this->plugins->filter(function (BasePlugin $plugin) {
            return $plugin->isActive();
        });
    }

    public function getPlugin(string $name): ?BasePlugin
    {
        return $this->plugins->get($name);
    }

    public function activatePlugin(string $name): bool
    {
        $plugin = $this->getPlugin($name);
        
        if ($plugin) {
            $plugin->activate();
            return true;
        }
        
        return false;
    }

    public function deactivatePlugin(string $name): bool
    {
        $plugin = $this->getPlugin($name);
        
        if ($plugin) {
            $plugin->deactivate();
            return true;
        }
        
        return false;
    }

    public function installPlugin(string $name): bool
    {
        $plugin = $this->getPlugin($name);
        
        if ($plugin) {
            $plugin->install();
            return true;
        }
        
        return false;
    }

    public function uninstallPlugin(string $name): bool
    {
        $plugin = $this->getPlugin($name);
        
        if ($plugin) {
            $plugin->uninstall();
            $this->plugins->forget($name);
            return true;
        }
        
        return false;
    }
}
```

### Example Plugin: Social Media Integration
```php
// plugins/SocialMedia/SocialMedia.php
<?php

namespace Plugins\SocialMedia;

use App\Core\BasePlugin;
use Illuminate\Support\Facades\Route;

class SocialMedia extends BasePlugin
{
    public function install(): void
    {
        // Create database tables
        \Artisan::call('migrate', [
            '--path' => 'plugins/SocialMedia/migrations'
        ]);
        
        $this->publishAssets();
    }

    public function uninstall(): void
    {
        // Drop database tables
        \Schema::dropIfExists('social_media_posts');
        \Schema::dropIfExists('social_media_accounts');
    }

    protected function onActivate(): void
    {
        $this->registerRoutes();
        $this->registerViews();
    }

    protected function registerRoutes(): void
    {
        Route::group([
            'prefix' => 'admin/social-media',
            'middleware' => ['web', 'auth'],
            'namespace' => 'Plugins\SocialMedia\Controllers'
        ], function () {
            Route::get('/', 'SocialMediaController@index')->name('admin.social-media.index');
            Route::post('/post', 'SocialMediaController@post')->name('admin.social-media.post');
            Route::get('/analytics', 'SocialMediaController@analytics')->name('admin.social-media.analytics');
        });
    }
}
```

## Custom Middleware

### Rate Limiting Middleware
```php
// app/Http/Middleware/CustomRateLimit.php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

class CustomRateLimit
{
    public function handle(Request $request, Closure $next, string $key = 'global', int $maxAttempts = 60, int $decayMinutes = 1)
    {
        $identifier = $this->resolveRequestSignature($request, $key);
        
        if (RateLimiter::tooManyAttempts($identifier, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($identifier);
            
            return response()->json([
                'error' => 'Too many requests',
                'retry_after' => $seconds
            ], 429);
        }
        
        RateLimiter::increment($identifier, $decayMinutes * 60);
        
        $response = $next($request);
        
        return $this->addHeaders(
            $response,
            $maxAttempts,
            RateLimiter::retriesLeft($identifier, $maxAttempts),
            RateLimiter::availableIn($identifier)
        );
    }

    protected function resolveRequestSignature(Request $request, string $key): string
    {
        return $key . ':' . $request->ip() . ':' . $request->userAgent();
    }

    protected function addHeaders($response, int $maxAttempts, int $remainingAttempts, int $retryAfter = null)
    {
        $response->headers->add([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => max(0, $remainingAttempts),
        ]);

        if ($retryAfter !== null) {
            $response->headers->add(['X-RateLimit-Reset' => time() + $retryAfter]);
        }

        return $response;
    }
}
```

### Security Headers Middleware
```php
// app/Http/Middleware/SecurityHeaders.php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Security headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Content Security Policy
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; " .
               "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
               "font-src 'self' https://fonts.gstatic.com; " .
               "img-src 'self' data: https:; " .
               "connect-src 'self';";
        
        $response->headers->set('Content-Security-Policy', $csp);
        
        // HSTS (only for HTTPS)
        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        return $response;
    }
}
```

### Activity Logging Middleware
```php
// app/Http/Middleware/LogUserActivity.php
<?php

namespace App\Http\Middleware;

use App\Models\UserActivity;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Log activity for authenticated users
        if (Auth::check() && $this->shouldLog($request)) {
            UserActivity::create([
                'user_id' => Auth::id(),
                'action' => $this->getAction($request),
                'resource' => $this->getResource($request),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'request_data' => $this->getRequestData($request),
                'response_status' => $response->getStatusCode(),
                'created_at' => now(),
            ]);
        }

        return $response;
    }

    protected function shouldLog(Request $request): bool
    {
        // Don't log certain routes
        $excludedRoutes = [
            'api/health',
            'api/ping',
            'horizon/*'
        ];

        foreach ($excludedRoutes as $pattern) {
            if ($request->is($pattern)) {
                return false;
            }
        }

        return true;
    }

    protected function getAction(Request $request): string
    {
        $method = $request->method();
        $routeName = $request->route()?->getName() ?? 'unknown';
        
        return "{$method} {$routeName}";
    }

    protected function getResource(Request $request): string
    {
        return $request->path();
    }

    protected function getRequestData(Request $request): array
    {
        $data = $request->except(['password', 'password_confirmation', '_token']);
        
        // Limit size to prevent database issues
        $json = json_encode($data);
        if (strlen($json) > 65535) {
            return ['message' => 'Request data too large to log'];
        }
        
        return $data;
    }
}
```

## Event System

### Custom Events
```php
// app/Events/WbsReportSubmitted.php
<?php

namespace App\Events;

use App\Models\Wbs;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WbsReportSubmitted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $wbsReport;

    public function __construct(Wbs $wbsReport)
    {
        $this->wbsReport = $wbsReport;
    }
}
```

### Event Listeners
```php
// app/Listeners/NotifyAdminOfWbsReport.php
<?php

namespace App\Listeners;

use App\Events\WbsReportSubmitted;
use App\Mail\WbsReportNotification;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotifyAdminOfWbsReport
{
    public function handle(WbsReportSubmitted $event)
    {
        $admins = User::where('role', 'admin')->get();
        
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(
                new WbsReportNotification($event->wbsReport, $admin)
            );
        }
    }
}
```

```php
// app/Listeners/LogWbsActivity.php
<?php

namespace App\Listeners;

use App\Events\WbsReportSubmitted;
use Illuminate\Support\Facades\Log;

class LogWbsActivity
{
    public function handle(WbsReportSubmitted $event)
    {
        Log::channel('wbs')->info('New WBS report submitted', [
            'report_id' => $event->wbsReport->id,
            'title' => $event->wbsReport->judul,
            'reporter' => $event->wbsReport->nama_pelapor,
            'submitted_at' => $event->wbsReport->created_at,
        ]);
    }
}
```

### Event Service Provider
```php
// app/Providers/EventServiceProvider.php
<?php

namespace App\Providers;

use App\Events\WbsReportSubmitted;
use App\Listeners\LogWbsActivity;
use App\Listeners\NotifyAdminOfWbsReport;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        WbsReportSubmitted::class => [
            NotifyAdminOfWbsReport::class,
            LogWbsActivity::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
```

## Notification System

### Database Notifications
```php
// app/Notifications/WbsStatusUpdated.php
<?php

namespace App\Notifications;

use App\Models\Wbs;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;

class WbsStatusUpdated extends Notification
{
    use Queueable;

    protected $wbsReport;
    protected $oldStatus;
    protected $newStatus;

    public function __construct(Wbs $wbsReport, string $oldStatus, string $newStatus)
    {
        $this->wbsReport = $wbsReport;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function via($notifiable)
    {
        return ['database', 'mail', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Status Laporan WBS Diperbarui',
            'message' => "Status laporan '{$this->wbsReport->judul}' diubah dari {$this->oldStatus} menjadi {$this->newStatus}",
            'action_url' => route('wbs.show', $this->wbsReport->id),
            'action_text' => 'Lihat Laporan',
            'icon' => 'bell',
            'type' => 'info',
            'data' => [
                'wbs_id' => $this->wbsReport->id,
                'old_status' => $this->oldStatus,
                'new_status' => $this->newStatus,
            ]
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Status Laporan WBS Diperbarui')
            ->greeting('Halo!')
            ->line("Status laporan WBS Anda telah diperbarui.")
            ->line("Judul: {$this->wbsReport->judul}")
            ->line("Status sebelumnya: {$this->oldStatus}")
            ->line("Status baru: {$this->newStatus}")
            ->action('Lihat Laporan', route('wbs.show', $this->wbsReport->id))
            ->line('Terima kasih telah menggunakan sistem kami.');
    }

    public function toBroadcast($notifiable)
    {
        return [
            'title' => 'Status Laporan WBS Diperbarui',
            'message' => "Status laporan '{$this->wbsReport->judul}' diubah menjadi {$this->newStatus}",
            'type' => 'info',
            'action_url' => route('wbs.show', $this->wbsReport->id),
        ];
    }
}
```

### Real-time Notifications with Pusher
```javascript
// resources/js/notifications.js
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});

class NotificationManager {
    constructor() {
        this.notifications = [];
        this.unreadCount = 0;
        this.init();
    }

    init() {
        this.loadNotifications();
        this.listenForNotifications();
        this.setupUI();
    }

    loadNotifications() {
        fetch('/api/notifications')
            .then(response => response.json())
            .then(data => {
                this.notifications = data.notifications;
                this.unreadCount = data.unread_count;
                this.updateUI();
            });
    }

    listenForNotifications() {
        if (window.authUserId) {
            Echo.private(`App.Models.User.${window.authUserId}`)
                .notification((notification) => {
                    this.addNotification(notification);
                    this.showToast(notification);
                });
        }
    }

    addNotification(notification) {
        this.notifications.unshift(notification);
        this.unreadCount++;
        this.updateUI();
    }

    markAsRead(notificationId) {
        fetch(`/api/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(() => {
            const notification = this.notifications.find(n => n.id === notificationId);
            if (notification && !notification.read_at) {
                notification.read_at = new Date().toISOString();
                this.unreadCount = Math.max(0, this.unreadCount - 1);
                this.updateUI();
            }
        });
    }

    showToast(notification) {
        const toast = document.createElement('div');
        toast.className = 'notification-toast';
        toast.innerHTML = `
            <div class="flex items-start space-x-3 p-4 bg-white border border-gray-200 rounded-lg shadow-lg">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-${notification.icon || 'bell'} text-white text-sm"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-medium text-gray-900">${notification.title}</h4>
                    <p class="text-sm text-gray-600">${notification.message}</p>
                    ${notification.action_url ? `
                        <a href="${notification.action_url}" class="text-sm text-blue-600 hover:text-blue-800">
                            ${notification.action_text || 'Lihat'}
                        </a>
                    ` : ''}
                </div>
                <button onclick="this.closest('.notification-toast').remove()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        document.getElementById('toast-container').appendChild(toast);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 5000);
    }

    updateUI() {
        // Update notification badge
        const badge = document.getElementById('notification-badge');
        if (badge) {
            badge.textContent = this.unreadCount;
            badge.style.display = this.unreadCount > 0 ? 'block' : 'none';
        }

        // Update notification dropdown
        this.renderNotificationDropdown();
    }

    renderNotificationDropdown() {
        const container = document.getElementById('notification-dropdown');
        if (!container) return;

        container.innerHTML = `
            <div class="max-h-96 overflow-y-auto">
                ${this.notifications.length > 0 ? this.notifications.map(notification => `
                    <div class="notification-item ${!notification.read_at ? 'unread' : ''}" 
                         data-id="${notification.id}">
                        <div class="flex items-start space-x-3 p-3 hover:bg-gray-50 cursor-pointer"
                             onclick="notificationManager.markAsRead('${notification.id}')">
                            <div class="flex-shrink-0">
                                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-${notification.icon || 'bell'} text-white text-xs"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium text-gray-900 truncate">
                                    ${notification.title}
                                </h4>
                                <p class="text-sm text-gray-600 line-clamp-2">
                                    ${notification.message}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    ${this.formatDate(notification.created_at)}
                                </p>
                            </div>
                            ${!notification.read_at ? '<div class="w-2 h-2 bg-blue-600 rounded-full"></div>' : ''}
                        </div>
                    </div>
                `).join('') : `
                    <div class="p-4 text-center text-gray-500">
                        Tidak ada notifikasi
                    </div>
                `}
            </div>
            ${this.notifications.length > 0 ? `
                <div class="border-t border-gray-200 p-2">
                    <a href="/notifications" class="block text-center text-sm text-blue-600 hover:text-blue-800">
                        Lihat semua notifikasi
                    </a>
                </div>
            ` : ''}
        `;
    }

    formatDate(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diff = now - date;
        
        if (diff < 60000) return 'Baru saja';
        if (diff < 3600000) return `${Math.floor(diff / 60000)} menit lalu`;
        if (diff < 86400000) return `${Math.floor(diff / 3600000)} jam lalu`;
        return `${Math.floor(diff / 86400000)} hari lalu`;
    }

    setupUI() {
        // Create toast container if not exists
        if (!document.getElementById('toast-container')) {
            const container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'fixed top-4 right-4 z-50 space-y-2';
            document.body.appendChild(container);
        }
    }
}

// Initialize notification manager
window.notificationManager = new NotificationManager();
```

---

*Advanced customization guide last updated: January 2025*
*For questions about advanced features, contact the development team.*
