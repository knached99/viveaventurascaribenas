<?php

use App\Livewire\Forms\ContactForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public ContactForm $form;

    /**
     * Handle the form submission.
     */
    public function submitContactForm(): void
    {
        $this->validate();

        $this->form->submitContactForm();

        $this->redirect(route('contact', absolute: false));
    }
}; ?>

<div>
    @if (session('status'))
        <div class="mb-4 text-success">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit.prevent="submitContactForm">
        <div class="form-group">
            <input type="text" wire:model="form.name" class="form-control" placeholder="Your Name">
            @error('form.name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <input type="email" wire:model="form.email" class="form-control" placeholder="Your Email">
            @error('form.email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <input type="text" wire:model="form.subject" class="form-control" placeholder="Subject">
            @error('form.subject')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <textarea wire:model="form.message" cols="30" rows="7" class="form-control" placeholder="Message"></textarea>
            @error('form.message')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <input type="submit" value="Send Message" class="btn btn-primary py-3 px-5">
        </div>
    </form>
</div>
