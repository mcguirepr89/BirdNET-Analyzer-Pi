<x-forms.form submit="/config">
    <x-slot name="title">
        {{ __('BirdNET Configuration') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your BirdNET Configuration.') }}
    </x-slot>

    <x-slot name="form">
        @foreach ( $configVars[0] as $setting => $value )
            @if ($setting != 'id' && !strpos($setting, 'at'))
                <div class="col-span-6 md:col-span-5 drop-shadow-xl sm:rounded-xl p-2">
                    <form action="#" method="POST">
                        @csrf
                        <x-jet-label class="pt-6 text-slate-900 dark:text-gray-300" for="{{ $setting }}" value="{{ $setting }}" />
                        <x-jet-input name="{{ $setting }}" id="{{ $setting }}" type="text" class="mt-1 block w-full" value="{{ $value }}" />
                        <x-jet-input-error for="{{ $setting }}" class="mt-2" />
                </div>
            @endif
        @endforeach
            <button type="submit" class="col-span-6 md:col-span-5 bg-emerald-400 text-slate-50 dark:text-slate-900 dark:font-semibold hover:text-black mt-5 border dark:border-black py-2 px-6 rounded-xl">Update Settings</button>
        </form>
    </x-slot>
</x-forms.form>