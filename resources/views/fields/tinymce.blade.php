@props([
    'value' => '',
    'label' => '',
    'config' => '',
])
<div class="tinymce">
    <x-moonshine::form.textarea
        :attributes="$attributes"
        x-data="tinymce({{ $config }})"
    >{!! $value ?? '' !!}</x-moonshine::form.textarea>
</div>
