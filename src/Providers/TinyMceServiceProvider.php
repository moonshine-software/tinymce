<?php

declare(strict_types=1);

namespace MoonShine\TinyMce\Providers;

use Illuminate\Support\ServiceProvider;

final class TinyMceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'moonshine-tinymce');

        $this->publishes([
            __DIR__ . '/../../public' => public_path('vendor/moonshine-tinymce'),
        ], ['moonshine-tinymce-assets', 'laravel-assets']);
    }
}
