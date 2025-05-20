<x-authenticated-theme-layout>
<div class="container my-4">
    <div class="d-flex justify-content-start mb-4">
          <button wire:click="createBackup" class="btn btn-sm btn-primary">
                    <i class="fa-solid fa-circle-plus"></i>
                    Create Backup 
                    </button>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach($backups as $backup)
        <div class="col">
            <div class="card h-100 shadow-sm border-0 backup-card transition">
                <div class="card-body">
                    <h5 class="card-title">{{ $backup['name'] }}</h5>
                    <p class="card-text">
                        Size: {{ number_format($backup['size'] / 1024, 2) }} KB<br>
                        Last Modified: {{ \Carbon\Carbon::createFromTimestamp($backup['modified'])->toDayDateTimeString() }}
                    </p>
                </div>
                <div class="card-footer bg-transparent border-0 d-flex justify-content-end gap-2">
            
                    <button wire:click="deleteBackup('{{$backup['name']}}')" class="btn btn-sm btn-danger">
                    <i class="fa-solid fa-trash"></i>
                    Delete Backup 
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
</x-authenticated-theme-layout>