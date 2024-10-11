<?php

declare(strict_types=1);

namespace MoonShine\TinyMce\Fields;

use Closure;
use MoonShine\AssetManager\Css;
use MoonShine\AssetManager\Js;
use MoonShine\UI\Fields\Textarea;

class TinyMce extends Textarea
{
    const VERSION = '7';

    protected string $view = 'moonshine-tinymce::fields.tinymce';

    protected array $plugins = [];

    protected string|bool $menubar = '';

    protected string|bool|array $toolbar = '';

    protected array $options = [];

    protected array $callbacks = [];

    public string $locale = '';

    protected array $reservedOptions = [
        'selector',
        'path_absolute',
        'relative_urls',
        'branding',
        'skin',
        'language',
        'plugins',
        'menubar',
        'toolbar',
    ];

    public function __construct(string|Closure|null $label = null, ?string $column = null, ?Closure $formatted = null)
    {
        $this->plugins = config('moonshine_tinymce.plugins', []);
        $this->menubar = config('moonshine_tinymce.menubar', '');
        $this->toolbar = config('moonshine_tinymce.toolbar', '');
        $this->options = config('moonshine_tinymce.options', []);
        $this->callbacks = config('moonshine_tinymce.callbacks', []);

        parent::__construct($label, $column, $formatted);
    }

    public function getAssets(): array
    {
        $assets = [
            Js::make('vendor/moonshine-tinymce/tinymce.min.js'),
            Js::make('vendor/moonshine-tinymce/init.js'),
            Css::make('vendor/moonshine-tinymce/tinymce.css'),
        ];

        if ($this->getToken()) {
            $assets[] = Js::make("https://cdn.tiny.cloud/1/{$this->getToken()}/tinymce/{$this->getVersion()}/plugins.min.js");
        }

        return $assets;
    }

    protected function getToken(): string
    {
        return config('moonshine-tinymce.token', '');
    }

    protected function getVersion(): string
    {
        return self::VERSION;
    }

    public function locale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function plugins(array $plugins): self
    {
        $this->plugins = $plugins;

        return $this;
    }

    public function addPlugins(array $plugins): self
    {
        $this->plugins = array_merge($this->plugins, $plugins);

        return $this;
    }

    public function removePlugins(array $plugins): self
    {
        $this->plugins = array_diff($this->plugins, $plugins);

        return $this;
    }

    public function getPlugins(): array
    {
        return array_unique($this->plugins);
    }

    public function menubar(string|bool $menubar): self
    {
        $this->menubar = $menubar;

        return $this;
    }

    public function toolbar(string|bool|array $toolbar): self
    {
        $this->toolbar = $toolbar;

        return $this;
    }

    public function addOption(string $name, string|int|float|bool|array $value): self
    {
        $name = str($name)->lower()->value();

        if (in_array($name, $this->reservedOptions)) {
            return $this;
        }

        if (is_string($value) && str($value)->isJson()) {
            $value = json_decode($value, true);
        }

        $this->options[$name] = $value;

        return $this;
    }

    public function addCallback(string $name, string $value): self
    {
        $name = str($name)->lower()->value();

        if (in_array($name, $this->reservedOptions)) {
            return $this;
        }

        $this->callbacks[$name] = $value;

        return $this;
    }

    public function getOptions(): array
    {
        return [
            'toolbar_mode' => 'sliding',
            'language' => !empty($this->locale) ? $this->locale : app()->getLocale(),
            'plugins' => implode(' ', $this->getPlugins()),
            'menubar' => $this->menubar,
            'toolbar' => $this->toolbar,
            ...$this->options,
        ];
    }

    public function getCallbacks(): array
    {
        return $this->callbacks;
    }

    protected function resolveValue(): string
    {
        return str($this->toValue())->replace(
            ['&amp;', '&lt;', '&gt;', '&nbsp;', '&quot;'],
            ['&amp;amp;', '&amp;lt;', '&amp;gt;', '&amp;nbsp;', '&amp;quot;']
        )->value();
    }

    /**
     * @return array<string, mixed>
     */
    protected function viewData(): array
    {
        return [
            'options' => json_encode($this->getOptions()),
            'callbacks' => json_encode($this->getCallbacks())
        ];
    }
}
