# TinyMce field for [MoonShine Laravel admin panel](https://moonshine-laravel.com)

Extends [Textarea](https://moonshine-laravel.com/docs/resource/fields/fields-textarea) and has the same features

<picture>
    <source media="(prefers-color-scheme: dark)" srcset="https://raw.githubusercontent.com/moonshine-software/tinymce/main/art/tinymce_dark.png">
    <source media="(prefers-color-scheme: light)" srcset="https://raw.githubusercontent.com/moonshine-software/tinymce/main/art/tinymce.png">
    <img alt="windows" src="https://raw.githubusercontent.com/moonshine-software/tinymce/main/art/tinymce.png">
</picture>

> [!IMPORTANT]
> Before using this field, you must register on the site at [Tiny.Cloud](https://www.tiny.cloud), get the token and add it to the `.env`

```
TINYMCE_TOKEN="YOUR_TOKEN"
```

---

## Compatibility

| MoonShine | Moonshine TinyMce | Currently supported |
|:---------:|:-----------------:|:-------------------:|
|  >= v3.0  |     >= v1.0.0     |         yes         |
|  >= v4.0  |     >= v2.0.0     |         yes         |

## Installation

```shell
composer require moonshine/tinymce
```

## Usage

```php
use MoonShine\TinyMce\Fields\TinyMce;


TinyMce::make('Description')
```

## Default config

`TinyMce` field uses the most common settings such as plugins, menubar and toolbar by default

To change the default settings, you need to publish the configuration file:

```php
php artisan vendor:publish --tag="moonshine-tinymce-config"
```

You can also add additional options to the configuration file that will apply to all `TinyMce` fields

```php
'options' => [
    'forced_root_block' => 'div',
    'force_br_newlines' => true,
    'force_p_newlines' => false,
],
```

## Locale

The default is your application's locale, but using the `locale()` method you can define a specific locale

```php
locale(string $locale)
```

```php
TinyMce::make('Description')
    ->locale('ru');
```

English (en), Russian (ru) and Ukrainian (uk) are currently available, but we are always ready to add the others.

To add new localizations, create an issue or make a pull request

## Plugins

The `plugins()` method allows you to completely override the plugins that the field will use

```php
plugins(array $plugins)
```

```php
TinyMce::make('Description')
    ->plugins(['code', 'image', 'link', 'media', 'table'])
```

The `addPlugins()` method allows you to add new plugins to the default plugins

```php
addPlugins(array $plugins)
```

```php
TinyMce::make('Description')
    ->addPlugins(['wordcount'])
```

The `removePlugins()` method allows you to exclude plugins that the field will use

```php
removePlugins(array $plugins)
```

```php
TinyMce::make('Description')
    ->removePlugins(['autoresize'])
```

## Menubar

The `menubar()` method allows you to completely override menubar for a field

```php
menubar(string|bool $menubar)
```

```php
TinyMce::make('Description')
    ->menubar('file edit view')
```

## Toolbar

The `toolbar()` method allows you to completely override toolbar for a field

```php
toolbar(string|bool|array $toolbar)
```

```php
TinyMce::make('Description')
    ->toolbar('file edit view')
```

## Options

The `addOption()` method allows you to add additional options for a field

```php
addOption(string $name, string|int|float|bool|array $value)
```

```php
TinyMce::make('Description')
    ->addOption('forced_root_block', 'div')

```

The `addCallback()` method allows you to add callback options for a field

```php
addCallback(string $name, string $value)
```

```php
TinyMce::make('Description')
    ->addCallback('setup', '(editor) => console.log(editor)')
```

> [!NOTE]
> You can use string, number, boolean and array as values.

## File manager

If you want to use the file manager in TinyMce, you need to install the package [Laravel FileManager](https://github.com/UniSharp/laravel-filemanager)

### Installation

```php
composer require unisharp/laravel-filemanager

php artisan vendor:publish --tag=lfm_config
php artisan vendor:publish --tag=lfm_public
```

> [!NOTE]
> Be sure to set the 'use_package_routes' flag in the lfm config to false, otherwise caching routes will cause an error.

```php
// config/lfm.php

'use_package_routes' => false,
```

### Routes file

Create a routes file like `routes/moonshine.php` and register the LaravelFilemanager routes.

```php
use Illuminate\Support\Facades\Route;
use UniSharp\LaravelFilemanager\Lfm;

Route::prefix('laravel-filemanager')->group(function () {
    Lfm::routes();
});
```

### File registration

Register the generated routes file in `app/Providers/RouteServiceProvider.php`

> [!WARNING]
> The route file must be in the middleware `moonshine` group!

```php
public function boot()
{
    // ...

    $this->routes(function () {
        // ...

        Route::middleware('moonshine')
            ->namespace($this->namespace)
            ->group(base_path('routes/moonshine.php'));
    });
}
```

> [!IMPORTANT]
> In order to allow access only to users authorized in the admin panel you need to add middleware `MoonShine\Laravel\Http\Middleware\Authenticate.`

```php
use MoonShine\Laravel\Http\Middleware\Authenticate;

// ...

public function boot()
{
    // ...

    $this->routes(function () {
        // ...

        Route::middleware(['moonshine', Authenticate::class])
            ->namespace($this->namespace)
            ->group(base_path('routes/moonshine.php'));
    });
}
```

### Configuration

You need to add an option for the field

```php
TinyMce::make('Description')
    ->addOption('file_manager', 'laravel-filemanager')
```

or add in the `config/moonshine_tinymce.php` configuration file to apply to all `TinyMCe` fields

```php
'options' => [
    'file_manager' => 'laravel-filemanager',
],
```
