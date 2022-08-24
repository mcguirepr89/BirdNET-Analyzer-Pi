<x-forms.config-form submit="write_config">
    <x-slot name="title">
        {{ __('BirdNET Configuration') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your BirdNET Configuration.') }}
    </x-slot>

    <x-slot name="form">
        @foreach ( $settings as $setting => $values )
            <div class="col-span-6 drop-shadow-xl rounded-xl p-2">
                <div class="text-lg">{{ $setting }}</div>
                <form action="/config/form" method="POST">
                    @csrf
                    @foreach ( $values as $key => $value)
                        <x-jet-label class="pt-6" for="{{ $key }}" value="{{ $key }}" />
                        <x-jet-input name="{{ $key }}" id="{{ $key }}" type="text" class="mt-1 block w-full" value="{{ $value }}" />
                        <x-jet-input-error for="{{ $key }}" class="mt-2" />
                    @endforeach
                    <div class="flex justify-end">
                    <button type="submit" class=" bg-emerald-400 text-slate-50 hover:text-black mt-5 border py-2 px-6 rounded-xl">Update {{ $setting }}</button>
                    </div>
                </form>
                <x-jet-section-border />
            </div>
        @endforeach
    </x-slot>
</x-forms.config-form>