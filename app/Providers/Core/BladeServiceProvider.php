<?php

namespace App\Providers\Core;

use App\Models\GeneralSetting; // Make sure to import your model
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // 🌟 Define the custom Blade Directive: @settings('key')
        Blade::directive('settings', function ($expression) {
            // Remove the quotes around the key (e.g., 'app_name' becomes app_name)
            $key = trim($expression, "'\"");

            // ⚠️ Ensure the GeneralSetting model is properly optimized (e.g., using caching)
            // The GeneralSetting::current() call will run every time this directive is used
            return "<?php echo \App\Models\GeneralSetting::current()->{$key}; ?>";
        });
    }
}
