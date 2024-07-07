<?php

declare(strict_types=1);

namespace MoonShine\TinyMce\Providers;

use Illuminate\Support\ServiceProvider;

final class TinyMceServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'moonshine-tinymce');

        $this->publishes([
            __DIR__ . '/../../config/moonshine_tinymce.php' => config_path('moonshine_tinymce.php'),
        ], ['moonshine-tinymce-config']);

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/moonshine_tinymce.php',
            'moonshine_tinymce'
        );

        $this->publishes([
            __DIR__ . '/../../public' => public_path('vendor/moonshine-tinymce'),
        ], ['moonshine-tinymce-assets', 'laravel-assets']);
    }
}
