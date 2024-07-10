<?php

namespace MoonShine\TinyMce\Tests\Unit;

use MoonShine\TinyMce\Fields\TinyMce;
use MoonShine\TinyMce\Tests\TestCase;
use MoonShine\UI\Fields\Textarea;

class TinyMceFieldTest extends TestCase
{
    private TinyMce $field;

    protected function setUp(): void {
        parent::setUp();

        $this->field = TinyMce::make('TinyMce');
    }

    public function testThatTextareaIsParent(): void
    {
        $this->assertInstanceOf(Textarea::class, $this->field);
    }

    public function testMake(): void
    {
        $this->assertEquals(config('moonshine_tinymce.token'), $this->getOption('token'));
        $this->assertEquals(implode(' ', config('moonshine_tinymce.plugins')), $this->getOption('plugins'));
        $this->assertEquals(config('moonshine_tinymce.menubar'), $this->getOption('menubar'));
        $this->assertEquals(config('moonshine_tinymce.toolbar'), $this->getOption('toolbar'));
        foreach (config('moonshine_tinymce.options', []) as $key => $value) {
            $this->assertEquals($value, $this->getOption($key));
        }
        foreach (config('moonshine_tinymce.callbacks', []) as $key => $value) {
            $this->assertEquals($value, $this->getCallback($key));
        }
    }

    public function testType(): void
    {
        $this->assertEmpty($this->field->getAttributes()->get('type'));
    }

    public function testHasAssets(): void {
        $this->assertNotEmpty($this->field->getAssets());
        $this->assertIsArray($this->field->getAssets());
    }

    public function testDefaultLocale(): void
    {
        $this->assertEquals(app()->getLocale(), $this->getOption('language'));
    }

    public function testSetLocale(): void
    {
        $this->field->locale('locale_test');
        $this->assertEquals('locale_test', $this->getOption('language'));
    }

    public function testSetPlugins(): void
    {
        $this->field->plugins(['plugin_1', 'plugin_2']);
        $this->assertContains('plugin_1', $this->field->getPlugins());
        $this->assertContains('plugin_2', $this->field->getPlugins());
        $this->assertIsString($this->getOption('plugins'));
        $this->assertEquals(implode(' ', ['plugin_1', 'plugin_2']), $this->getOption('plugins'));
    }

    public function testSetDoublePlugins(): void
    {
        $this->field->plugins(['plugin_1', 'plugin_1']);
        $this->assertCount(1, $this->field->getPlugins());
        $this->assertIsString($this->getOption('plugins'));
        $this->assertEquals('plugin_1', $this->getOption('plugins'));
    }

    public function testAddPlugins(): void
    {
        $this->field->addPlugins(['plugin_1', 'plugin_2']);
        $this->assertContains('plugin_1', $this->field->getPlugins());
        $this->assertContains('plugin_2', $this->field->getPlugins());
        $this->assertIsString($this->getOption('plugins'));
        $this->assertStringContainsString('plugin_1', $this->getOption('plugins'));
        $this->assertStringContainsString('plugin_2', $this->getOption('plugins'));
    }

    public function testRemovePlugins(): void
    {
        $this->field->plugins(['plugin_1', 'plugin_2']);

        $this->field->removePlugins(['plugin_1']);
        $this->assertNotContains('plugin_1', $this->field->getPlugins());
        $this->assertContains('plugin_2', $this->field->getPlugins());
        $this->assertStringNotContainsString('plugin_1', $this->getOption('plugins'));
        $this->assertStringContainsString('plugin_2', $this->getOption('plugins'));
    }

    public function testSetMenubar(): void
    {
        $this->field->menubar('menubar_test');
        $this->assertEquals('menubar_test', $this->getOption('menubar'));
        $this->assertIsString($this->getOption('menubar'));

        $this->field->menubar(false);
        $this->assertFalse($this->getOption('menubar'));
    }

    public function testSetToolbar(): void
    {
        $this->field->toolbar('toolbar_1 | toolbar_2');
        $this->assertIsString($this->getOption('toolbar'));
        $this->assertStringContainsString('toolbar_1', $this->getOption('toolbar'));
        $this->assertStringContainsString('toolbar_2', $this->getOption('toolbar'));

        $this->field->toolbar(['toolbar_1', 'toolbar_2']);
        $this->assertIsArray($this->getOption('toolbar'));
        $this->assertContains('toolbar_1', $this->getOption('toolbar'));
        $this->assertContains('toolbar_2', $this->getOption('toolbar'));

        $this->field->toolbar(false);
        $this->assertFalse($this->getOption('toolbar'));
    }

    public function testAddStringOption(): void
    {
        $this->field->addOption('option_test', 'test');
        $this->assertIsString($this->getOption('option_test'));
        $this->assertEquals('test', $this->getOption('option_test'));
    }

    public function testAddIntOption(): void
    {
        $this->field->addOption('option_test', 12);
        $this->assertIsInt($this->getOption('option_test'));
        $this->assertEquals(12, $this->getOption('option_test'));
    }

    public function testAddFloatOption(): void
    {
        $this->field->addOption('option_test', 5.6);
        $this->assertIsFloat($this->getOption('option_test'));
        $this->assertEquals(5.6, $this->getOption('option_test'));
    }

    public function testAddBoolOption(): void
    {
        $this->field->addOption('option_test', false);
        $this->assertIsBool($this->getOption('option_test'));
        $this->assertFalse($this->getOption('option_test'));


        $this->field->addOption('option_test', true);
        $this->assertIsBool($this->getOption('option_test'));
        $this->assertTrue($this->getOption('option_test'));
    }

    public function testAddJsonOption(): void
    {
        $this->field->addOption('option_test', json_encode(['key' => 'value'], true));
        $this->assertIsArray($this->getOption('option_test'));
        $this->assertEquals(['key' => 'value'], $this->getOption('option_test'));
    }

    public function testAddArrayOption(): void
    {
        $this->field->addOption('option_test', ['key' => 'value']);
        $this->assertIsArray($this->getOption('option_test'));
        $this->assertEquals(['key' => 'value'], $this->getOption('option_test'));
    }

    public function testAddReservedOption(): void
    {
        $this->field->addOption('selector', 'test');
        $this->assertEmpty($this->getOption('selector'));
    }

    public function testAddCallback(): void
    {
        $callback = '(editor) => console.log(editor)';

        $this->field->addCallback('setup', $callback);
        $this->assertIsString($this->getCallback('setup'));
        $this->assertEquals($callback, $this->getCallback('setup'));
    }

    public function testView(): void
    {
        $this->field
            ->locale('en')
            ->plugins(['code', 'image', 'link'])
            ->menubar('file')
            ->toolbar('undo redo')
            ->addOption('height', 500)
            ->addCallback('setup', '(editor) => console.log(editor)');

        $this->assertEquals('moonshine-tinymce::fields.tinymce', $this->field->getView());

        $html = $this->field->render()->toHtml();
        $this->assertStringContainsString('x-data', $html);
        $this->assertStringContainsString('tinymce', $html);
        $this->assertStringContainsString(htmlspecialchars(json_encode($this->field->getOptions())), $html);
        $this->assertStringContainsString(htmlspecialchars(json_encode($this->field->getCallbacks())), $html);
    }

    private function getOption(string $key, ?TinyMce $field = null)
    {
        if (!$field) {
            $field = $this->field;
        }

        $options = $field->getOptions();

        return $options[$key] ?? null;
    }

    private function getCallback(string $key, ?TinyMce $field = null)
    {
        if (!$field) {
            $field = $this->field;
        }

        $callbacks = $field->getCallbacks();

        return $callbacks[$key] ?? null;
    }
}
