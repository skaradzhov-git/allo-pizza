@if (filament()->hasDarkMode() && (! filament()->hasDarkModeForced()))
    <div
        class="fi-topbar-theme-switcher hidden sm:block"
        x-data="{
            theme: localStorage.getItem('theme') || @js(filament()->getDefaultThemeMode()->value),
        }"
        x-init="
            $watch('theme', (value) => {
                localStorage.setItem('theme', value);
                $dispatch('theme-changed', value);
            })
        "
    >
        <div class="fi-theme-switcher grid grid-flow-col gap-x-1 rounded-lg bg-gray-50 p-1 ring-1 ring-gray-950/5 dark:bg-white/5 dark:ring-white/10">
            @foreach ([
                ['theme' => 'light', 'icon' => 'heroicon-m-sun', 'label' => __('filament-panels::layout.actions.theme_switcher.light.label')],
                ['theme' => 'dark', 'icon' => 'heroicon-m-moon', 'label' => __('filament-panels::layout.actions.theme_switcher.dark.label')],
                ['theme' => 'system', 'icon' => 'heroicon-m-computer-desktop', 'label' => __('filament-panels::layout.actions.theme_switcher.system.label')],
            ] as $option)
                <button
                    type="button"
                    aria-label="{{ $option['label'] }}"
                    x-on:click="theme = @js($option['theme'])"
                    x-tooltip="{ content: @js($option['label']), theme: $store.theme }"
                    class="fi-theme-switcher-btn flex justify-center rounded-md p-2 outline-none transition duration-75 hover:bg-gray-50 focus-visible:bg-gray-50 dark:hover:bg-white/5 dark:focus-visible:bg-white/5"
                    x-bind:class="theme === @js($option['theme'])
                        ? 'fi-active bg-gray-50 text-primary-500 dark:bg-white/5 dark:text-primary-400'
                        : 'text-gray-400 hover:text-gray-500 focus-visible:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 dark:focus-visible:text-gray-400'"
                >
                    <x-filament::icon :icon="$option['icon']" class="h-5 w-5" />
                </button>
            @endforeach
        </div>
    </div>
@endif
