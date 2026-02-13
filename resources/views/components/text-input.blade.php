@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-white text-gray-900 border-gray-200 focus:border-purple-500 focus:ring-purple-500 rounded-xl shadow-sm placeholder:text-gray-400']) }}>
