@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-[#7A8F5C] focus:ring-[#7A8F5C] rounded-md shadow-sm']) !!}>
