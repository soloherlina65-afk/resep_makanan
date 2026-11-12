<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#7A8F5C] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#5F7446] focus:bg-[#5F7446] active:bg-[#30402A] focus:outline-none focus:ring-2 focus:ring-[#7A8F5C] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
