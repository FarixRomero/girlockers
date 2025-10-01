@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-purple-darker text-cream border-pink-vibrant/20 focus:border-pink-vibrant focus:ring-pink-vibrant rounded-md shadow-sm placeholder:text-cream/40']) }}>
