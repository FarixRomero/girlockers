<button {{ $attributes->merge(['type' => 'submit', 'class' => 'bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl px-8 py-3 shadow-lg shadow-purple-100 hover:shadow-xl transition-all duration-300']) }}>
    {{ $slot }}
</button>
