@props([
    'value' => '',
    'label' => '',
    'options' => '',
    'callbacks' => '',
])
<div class="tinymce">
    <x-moonshine::form.textarea
        :attributes="$attributes"
        x-data="tinymce({{ $options }}, {{ $callbacks }})"
    >{!! $value ?? '' !!}</x-moonshine::form.textarea>
</div>
