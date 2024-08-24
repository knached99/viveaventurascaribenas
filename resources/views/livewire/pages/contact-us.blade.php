<?php

use App\Livewire\Forms\ContactForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public ContactForm $form;

    public function submitContactForm(): void
    {
        // $this->validate();

        $this->form->submitContactForm();

        // Instead of redirecting, rely on Livewire's reactive properties
    }
}; ?>

<div>
    @if ($form->status)
        <div class="mb-4 alert alert-success" role="alert">
            {{ $form->status }}
        </div>
    @elseif($form->error)
      <div class="mb-4 alert alert-danger" role="alert">
       {{$form->error}}
       </div>
    @endif

    <form wire:submit.prevent="submitContactForm" class="row g-3">
    <x-honeypot livewire-model="extraFields" />
        <div class="col-12">
            <div class="form-group">
                <input type="text" wire:model="form.name" class="form-control {{$errors->has('form.name') ? 'border border-danger' : ''}}" placeholder="Your Name">
                @error('form.name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <input type="email" wire:model="form.email" class="form-control {{$errors->has('form.email') ? 'border border-danger': ''}}" placeholder="Your Email">
                @error('form.email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <select wire:model="form.subject" class="form-control {{$errors->has('form.subject') ? 'border border-danger' : ''}}">
                    <option value="" disabled selected>Choose a Subject</option>
                    <option value="general question">General Question</option>
                    <option value="Question About a Booking">Question About a Booking</option>
                </select>
                @error('form.subject')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <textarea wire:model="form.message" rows="7" class="form-control " {{$errors->has('form.message') ? 'border border-danger' : '' }}
                    placeholder="Please provide a detailed description of your inquiry."></textarea>
                @error('form.message')
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
