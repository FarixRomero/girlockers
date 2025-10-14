@props(['videoId', 'title' => '', 'autoplay' => false, 'loop' => false])

@php
    $libraryId = config('bunny.library_id');
    $cdnHostname = config('bunny.cdn_hostname');

    $params = [];
    if ($autoplay) $params[] = 'autoplay=true';
    if ($loop) $params[] = 'loop=true';

    $queryString = !empty($params) ? '?' . implode('&', $params) : '';
@endphp

<div {{ $attributes->merge(['class' => 'relative w-full']) }} style="padding-top: 56.25%;">
    <iframe
        src="https://iframe.mediadelivery.net/embed/{{ $libraryId }}/{{ $videoId }}{{ $queryString }}"
        loading="lazy"
        style="border: none; position: absolute; top: 0; left: 0; height: 100%; width: 100%;"
        allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;"
        allowfullscreen="true"
        title="{{ $title }}">
    </iframe>
</div>
