<div class="container-fluid p-3 p-sm-4">
    {{-- Intro Section --}}
    <div class="bg-light rounded-3 shadow-sm p-4 w-100 w-md-75 mx-auto mb-4">
        <h6 class="fw-bold mb-3">
            This page provides an overview of all available backups.
        </h6>
        <p class="text-muted mb-3">
            Backups serve as snapshots of the data stored on the server and can be used to restore critical information in the event of data loss or other incidents.
        </p>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                To create a new backup, click the <strong>"Create Backup"</strong> button. This will generate a snapshot of your database in its current state.
            </li>
            <li class="list-group-item">
                To restore data, locate the desired backup and click <strong>"Restore."</strong>
            </li>
        </ul>
    </div>

    {{-- Alert Messages --}}
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

    {{-- Backup Action Buttons --}}
    <div class="d-flex flex-column flex-sm-row justify-content-start align-items-start mb-4 gap-2">
        <button wire:click="createBackup" class="btn btn-primary d-flex align-items-center gap-2">
            <span>Create Backup</span>
            <div class="spinner-border spinner-border-sm" role="status" wire:loading wire:target="createBackup">
                <span class="visually-hidden">Creating Backup...</span>
            </div>
        </button>
    </div>

    {{-- Backup Cards --}}
    @if(isset($backups) && is_array($backups) && count($backups) > 0)
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3">
            @foreach($backups as $backup)
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 backup-card transition">
                        <div class="card-body">
                            <h5 class="card-title text-break">{{ $backup['name'] }}</h5>
                            <p class="card-text small text-muted">
                                Size: {{ number_format($backup['size'] / 1024, 2) }} KB<br>
                                {{ \Carbon\Carbon::createFromTimestamp($backup['modified'])->setTimezone('America/New_York')->toDayDateTimeString() }}
                            </p>
                        </div>
                        <div class="card-footer bg-transparent border-0 d-flex flex-column flex-md-row justify-content-end gap-2">
                            <button wire:click="restoreFromSelectedBackup('{{ $backup['name'] }}')" class="btn btn-secondary d-flex align-items-center gap-2">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                                <span>Restore</span>
                                <div class="spinner-border spinner-border-sm" role="status" wire:loading wire:target="restoreFromSelectedBackup('{{ $backup['name'] }}')">
                                    <span class="visually-hidden">Restoring...</span>
                                </div>
                            </button>

                            <button wire:click="deleteBackup('{{ $backup['name'] }}')" class="btn btn-danger d-flex align-items-center gap-2">
                                <i class="fa-solid fa-trash"></i>
                                <span>Delete</span>
                                <div class="spinner-border spinner-border-sm" role="status" wire:loading wire:target="deleteBackup('{{ $backup['name'] }}')">
                                    <span class="visually-hidden">Deleting...</span>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="mt-4">
            <h5 class="fw-bold">No backups found</h5>
        </div>
    @endif
</div>
