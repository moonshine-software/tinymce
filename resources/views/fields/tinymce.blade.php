@props([
    'value' => '',
    'label' => '',
    'config' => '',
])
<div class="tinymce">
    <x-moonshine::form.textarea
        :attributes="$attributes->except('x-bind:id')"
        ::id="$id('tiny-mce')"
        x-data="tinymce({{ $config }})"
    >{!! $value ?? '' !!}</x-moonshine::form.textarea>
</div>
