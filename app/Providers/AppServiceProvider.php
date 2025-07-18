<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register SVG icon directives
        $this->registerIconDirectives();
    }

    /**
     * Register custom Blade directives for SVG icons
     */
    private function registerIconDirectives(): void
    {
        // @icon directive for general icon usage
        Blade::directive('icon', function ($expression) {
            return "<?php echo '<x-icons.' . {$expression} . ' />'; ?>";
});

// Specific icon directives for common icons
Blade::directive('iconFilter', function ($expression) {
return "<?php echo '<x-icons.filter' . ({$expression} ? ' ' . {$expression} : '') . ' />'; ?>";
});

Blade::directive('iconImage', function ($expression) {
return "<?php echo '<x-icons.image' . ({$expression} ? ' ' . {$expression} : '') . ' />'; ?>";
});

Blade::directive('iconThreeDots', function ($expression) {
return "<?php echo '<x-icons.three-dots' . ({$expression} ? ' ' . {$expression} : '') . ' />'; ?>";
});

Blade::directive('iconEdit', function ($expression) {
return "<?php echo '<x-icons.edit' . ({$expression} ? ' ' . {$expression} : '') . ' />'; ?>";
});

Blade::directive('iconDelete', function ($expression) {
return "<?php echo '<x-icons.delete' . ({$expression} ? ' ' . {$expression} : '') . ' />'; ?>";
});

        Blade::directive('iconPlusCircle', function ($expression) {
            return "<?php echo '<x-icons.plus-circle' . ({$expression} ? ' ' . {$expression} : '') . ' />'; ?>";
        });

        Blade::directive('iconPlus', function ($expression) {
            return "<?php echo '<x-icons.plus' . ({$expression} ? ' ' . {$expression} : '') . ' />'; ?>";
        });

        Blade::directive('iconCheck', function ($expression) {
            return "<?php echo '<x-icons.check' . ({$expression} ? ' ' . {$expression} : '') . ' />'; ?>";
        });

        Blade::directive('iconX', function ($expression) {
            return "<?php echo '<x-icons.x' . ({$expression} ? ' ' . {$expression} : '') . ' />'; ?>";
        });
}
}