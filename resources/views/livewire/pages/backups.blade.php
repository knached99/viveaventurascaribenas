<div class="container my-4">
    @if($success)
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $success }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($error)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $error }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-start mb-4">
          <button wire:click="createBackup" class="btn btn-sm btn-primary">
                    <i class="fa-solid fa-circle-plus"></i>
                    Create Backup 
                    <div class="spinner-border " role="status" wire:loading wire:target="createBackup">
                    <span class="visually-hidden">Creating Backup...</span>
                    </div>
                    </button>
    </div>

    @if(isset($backups) && is_array($backups) && count($backups) > 0)
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach($backups as $backup)
        <div class="col">
            <div class="card h-100 shadow-sm border-0 backup-card transition">
                <div class="card-body">
                    <h5 class="card-title">{{ $backup['name'] }}</h5>
                    <p class="card-text">
                        Size: {{ number_format($backup['size'] / 1024, 2) }} KB<br>
                        {{ \Carbon\Carbon::createFromTimestamp($backup['modified'])->setTimezone('America/New_York')->toDayDateTimeString() }}
                    </p>
                </div>
                <div class="card-footer bg-transparent border-0 d-flex justify-content-end gap-2">

                    <button wire:click="restoreFromSelectedBackup('{{$backup['name']}}')" class="btn btn-sm btn-secondary">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    Restore Backup
                    <div class="spinner-border " role="status" wire:loading wire:target="restoreFromSelectedBackup">
                    <span class="visually-hidden">Restoring From Backup...</span>
                    </div>
                    </button>

                    
                    <button wire:click="deleteBackup('{{$backup['name']}}')" class="btn btn-sm btn-danger">
                    <i class="fa-solid fa-trash"></i>
                    Delete Backup 
                    <div class="spinner-border " role="status" wire:loading wire:target="deleteBackup">
                    <span class="visually-hidden">Deleting Backup...</span>
                    </div>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
        @else 
        <h5 class="text-lg fw-bold">No backups found</h5>
        @endif
    </div>
</div>
