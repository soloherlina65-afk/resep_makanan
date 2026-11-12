<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-[#E4EBD8] border border-[#7A8F5C] rounded-md font-semibold text-xs text-[#5F7446] uppercase tracking-widest shadow-sm hover:bg-[#d5e0c5] focus:outline-none focus:ring-2 focus:ring-[#7A8F5C] focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
