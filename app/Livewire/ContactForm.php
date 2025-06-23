<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Mail;

class ContactForm extends Component
{
    public $name = '';
    public $email = '';
    public $message = '';
    public $success = false;
    public $error = false;
    public $submitting = false;

    protected $rules = [
        'name' => 'required|min:2',
        'email' => 'required|email',
        'message' => 'required|min:10',
    ];

    public function submit()
    {
        $this->validate();
        $this->submitting = true;
        try {
            Mail::to(config('mail.admin_address', 'contact@axel.com'))->send(new ContactFormMail(
                $this->name,
                $this->email,
                $this->message
            ));
            $this->success = true;
            $this->reset(['name', 'email', 'message']);
        } catch (\Exception $e) {
            $this->error = true;
        } finally {
            $this->submitting = false;
        }
    }

    public function render()
    {
        return view('livewire.contactform');
    }
}
