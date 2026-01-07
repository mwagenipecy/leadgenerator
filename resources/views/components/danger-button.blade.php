<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2 bg-sidebar-green border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-sidebar-green active:bg-sidebar-green-light focus:outline-none focus:ring-2 focus:ring-sidebar-green focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
