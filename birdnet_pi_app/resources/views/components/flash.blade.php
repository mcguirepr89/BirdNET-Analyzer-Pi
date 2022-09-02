@if (session()->has('success'))
<div x-data="{ show: true }"
     x-init="setTimeout(() => show = false, 4000)"
     x-show="show"
     class="max-w-sm mx-auto text-center bg-emerald-400 text-black font-md py-2 px-4 rounded-xl text-lg"
>
    <p>{{ session('success') }}</p>
</div>
@endif