<x-authenticated-theme-layout>

    @if (isset($error))
        <h5 class="text-danger">{{ $error }}</h5>
    @else
        <livewire:forms.edit-trip-form :trip="$trip" :totalNetCost="$totalNetCost" :grossProfit="$grossProfit" :netProfit="$netProfit" :averageStartDate="$averageStartDate" :averageEndDate="$averageEndDate" :averageDateRange="$averageDateRange" />
    @endif
</x-authenticated-theme-layout>
