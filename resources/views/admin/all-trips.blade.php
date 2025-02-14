<x-authenticated-theme-layout>
    <div class="row">
        <div class="card-body">
            <div class="col-sm-7">
                <div class="m-3">
                    <a class="btn btn-primary text-white w-100 w-sm-50" href="{{ route('admin.create-trip') }}">
                        Create Trip
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Trips Table -->
    @if (!$trips->isEmpty())
        <div class="card shadow-sm bg-white rounded">
            <h5 class="m-3">Here are all of your created trips</h5>

            @if (session('trip_deleted'))
                <div class="alert alert-success" role="alert">
                    {{ session('trip_deleted') }}
                </div>
            @endif

            <div class="table-responsive">
                <x-admincomponents.all-trips :trips="$trips" />
            </div>
        </div>
    @else
        <h3 class="text-secondary">No Available Trips. Go ahead and create one now</h3>
    @endif
    <!-- End Trips Table -->

    <!-- Catalog Items Table -->
    @if (!empty($catalogItems))
        <div class="card shadow-sm bg-white rounded mt-4">
            <h5 class="m-3">Here are all catalog items</h5>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Item ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Abbreviation</th>
                            <th>Product Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($catalogItems as $item)
                            @if ($item->getType() === 'ITEM' && $item->getItemData())
                                <tr>
                                    <td>{{ $item->getId() }}</td>
                                    <td>{{ $item->getItemData()->getName()['value'] ?? 'N/A' }}</td>
                                    <td>{{ $item->getItemData()->getDescription()['value'] ?? 'N/A' }}</td>
                                    <td>{{ $item->getItemData()->getAbbreviation()['value'] ?? 'N/A' }}</td>
                                    <td>{{ $item->getItemData()->getProductType() ?? 'N/A' }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <h3 class="text-secondary">No Catalog Items Available</h3>
    @endif
    <!-- End Catalog Items Table -->
</x-authenticated-theme-layout>
