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
        $this->error = false;
        
        try {
            Log::info('Contact form submission attempt', [
                'name' => $this->name,
                'email' => $this->email,
                'admin_address' => config('mail.admin_address', 'contact@axel.com')
            ]);
            
            Mail::to(config('mail.admin_address', 'contact@axel.com'))->send(new ContactFormMail(
                $this->name,
                $this->email,
                $this->message
            ));
            
            $this->success = true;
            $this->reset(['name', 'email', 'message']);
            
            Log::info('Contact form submitted successfully');
        } catch (\Exception $e) {
            $this->error = true;
            Log::error('Contact form submission failed: ' . $e->getMessage(), [
                'exception' => $e,
                'name' => $this->name,
                'email' => $this->email
            ]);
        } finally {
            $this->submitting = false;
        }
    }

    public function render()
    {
        return view('livewire.contactform');
    }
}
