@props(['name', 'class' => ''])

<i data-lucide="{{ $name }}" {{ $attributes->merge(['class' => 'lucide-icon ' . $class]) }}></i>
