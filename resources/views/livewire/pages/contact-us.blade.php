<?php

use App\Livewire\Forms\ContactForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public ContactForm $form;

    public function submitContactForm(): void
    {
        $this->validate();

        $this->form->submitContactForm();

        // Instead of redirecting, rely on Livewire's reactive properties
    }
}; ?>

<div>
    @if ($form->status)
        <div class="mb-4 text-success">
            {{ $form->status }}
        </div>
    @elseif($form->error)
        <div class="mb-4 text-danger">
            {{ $form->error }}
        </div>
    @endif

    <form wire:submit.prevent="submitContactForm" class="row g-3">
        <div class="col-12">
            <div class="form-group">
                <input type="text" wire:model="form.name" class="form-control" placeholder="Your Name">
                @error('form.name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <input type="email" wire:model="form.email" class="form-control" placeholder="Your Email">
                @error('form.email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <select wire:model="form.subject" class="form-control">
                    <option value="" disabled selected>Choose Subject</option>
                    <option value="general question">General Question</option>
                    <option value="Technical Support">Technical Support</option>
                    <option value="Question About a Booking">Question About a Booking</option>
                    <option value="Refund Process">Refund Process</option>
                </select>
                @error('form.subject')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <textarea wire:model="form.message" rows="7" class="form-control" placeholder="Message"></textarea>
                @error('form.message')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <button type="submit" class="btn btn-primary w-100 py-3">Send Message</button>
            </div>
        </div>
    </form>
</div>