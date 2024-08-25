
<div>
   @if ($status)
                <div class="mb-4 alert alert-success" role="alert">
                    {{$status }}
                </div>
            @elseif($error)
            <div class="mb-4 alert alert-danger" role="alert">
            {{$error}}
            </div>
            @endif
            
    <form wire:submit.prevent="submitContactForm" class="row g-3">
    <x-honeypot livewire-model="extraFields" />
        <div class="col-12">
            <div class="form-group">
                <input type="text" wire:model="name" class="form-control {{$errors->has('name') ? 'border border-danger' : ''}}" placeholder="Your Name">
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <input type="email" wire:model="email" class="form-control {{$errors->has('email') ? 'border border-danger': ''}}" placeholder="Your Email">
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <select wire:model="subject" class="form-control {{$errors->has('subject') ? 'border border-danger' : ''}}">
                    <option value="" disabled selected>Choose a Subject</option>
                    <option value="general question">General Question</option>
                    <option value="Question About a Booking">Question About a Booking</option>
                </select>
                @error('subject')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <textarea wire:model="message" rows="7" class="form-control  {{$errors->has('message') ? 'border border-danger' : '' }}"
                    placeholder="Please provide a detailed description of your inquiry."></textarea>
                @error('message')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <button type="submit" class="btn btn-primary w-100 py-3" wire:loading.remove>Send Message</button>

                <div class="spinner-border text-primary" role="status" wire:loading>
                </div>
            </div>
        </div>
    </form>
</div>
