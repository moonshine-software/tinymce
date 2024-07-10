<?php

namespace MoonShine\TinyMce\Tests;

use MoonShine\Laravel\Providers\MoonShineServiceProvider;
use MoonShine\TinyMce\Providers\TinyMceServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            MoonShineServiceProvider::class,
            TinyMceServiceProvider::class,
        ];
    }
}
