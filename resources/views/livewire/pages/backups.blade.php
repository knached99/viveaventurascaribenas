<div class="container py-4 px-3 px-sm-4">
    {{-- Intro Section --}}
    <div class="bg-white rounded-4 shadow-sm p-4 p-md-5 mx-auto mb-5 border" style="max-width: 900px;">
        <h5 class="fw-semibold mb-3 text-primary">
            Backup Overview
        </h5>
        <p class="text-muted mb-4">
            Backups are snapshots of the data stored on the server and can be used to restore critical information in
            case of data loss or other incidents. Use the instructions below to manage your backups efficiently.
        </p>

        <ol class="ps-3 text-dark">
            <li class="mb-2">
                To create a new backup, click the <strong>"Create Backup"</strong> button. This will generate a snapshot
                of your database in its current state.
            </li>
            <li class="mb-2">
                To restore data, locate the desired backup and click <strong>"Restore."</strong>
            </li>

            <li class="mb-2"> To delete a backup, simply locate the desired backup and click <strong> "Delete"</strong>
            </li>
        </ol>
    </div>

    {{-- Alert Messages --}}
    @if ($success)
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ $success }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($error)
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            {{ $error }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Backup Action Buttons --}}
    <div class="d-flex flex-column flex-sm-row flex-wrap align-items-start gap-2 mb-4">
        <button wire:click="createBackup" class="btn btn-primary d-flex align-items-center gap-2" wire:loading.remove
            wire:target="createBackup">
            <span>Create Backup</span>
            <div class="spinner-border spinner-border-sm" role="status" wire:loading wire:target="createBackup">
                <span class="visually-hidden">Creating Backup...</span>
            </div>
        </button>
    </div>

    {{-- Backup Cards --}}
    @if (isset($backups) && is_array($backups) && count($backups) > 0)
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3 overflow-scroll"
            style="max-height: 500px; max-width: 100%;">
            @foreach ($backups as $backup)
                <div class="col">
                    <h5 class="text-center fw-bold">Your Available Backups</h5>

                    <div class="card h-100 shadow-sm border-0 backup-card transition">
                        <div class="card-body">
                            <h5 class="card-title text-break" style="word-break: break-word;">{{ $backup['name'] }}</h5>

                            @php
                                $size = $backup['size']; // default is bytes
                                if ($size >= 1073741824) {
                                    $formattedSize = number_format($size / 1073741824, 2) . ' GB';
                                } elseif ($size >= 1048576) {
                                    $formattedSize = number_format($size / 1048576, 2) . ' MB';
                                } else {
                                    $formattedSize = number_format($size / 1024, 2) . ' KB';
                                }
                            @endphp
                            <p class="card-text small text-muted">
                                Size: {{ $formattedSize }}<br>
                                {{ \Carbon\Carbon::createFromTimestamp($backup['modified'])->setTimezone('America/New_York')->toDayDateTimeString() }}
                            </p>
                        </div>
                        <div
                            class="card-footer bg-transparent border-0 d-flex flex-column flex-md-row justify-content-end gap-2">
                            <button wire:click="restoreFromSelectedBackup('{{ $backup['name'] }}')"
                                class="btn btn-secondary d-flex align-items-center gap-2" wire:loading.remove
                                wire:target="restoreFromSelectedBackup('{{ $backup['name'] }}')">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                                <span>Restore</span>
                                <div wire:loading wire:target="restoreFromSelectedBackup('{{ $backup['name'] }}')"
                                    class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Restoring...</span>
                                </div>
                            </button>

                            <button wire:click="deleteBackup('{{ $backup['name'] }}')"
                                class="btn btn-danger d-flex align-items-center gap-2" wire:loading.remove
                                wire:target="deleteBackup('{{ $backup['name'] }}')">
                                <i class="fa-solid fa-trash"></i>
                                <span>Delete</span>
                                <div class="spinner-border spinner-border-sm" role="status" wire:loading
                                    wire:target="deleteBackup('{{ $backup['name'] }}')">
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
