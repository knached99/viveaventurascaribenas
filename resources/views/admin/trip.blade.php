<x-authenticated-theme-layout>

    @if (isset($error))
        <h5 class="text-danger">{{ $error }}</h5>
    @else
        <livewire:forms.edit-trip-form :trip="$trip" :totalNetCost="$totalNetCost" :grossProfit="$grossProfit" :netProfit="$netProfit" />
    @endif
</x-authenticated-theme-layout>
