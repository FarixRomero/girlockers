@props(['youtubeId', 'title' => 'Video'])

<div class="aspect-video bg-black relative">
    <iframe
        src="https://www.youtube.com/embed/{{ $youtubeId }}?rel=0&modestbranding=1"
        title="{{ $title }}"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen
        class="w-full h-full"
        loading="lazy">
    </iframe>
</div>
