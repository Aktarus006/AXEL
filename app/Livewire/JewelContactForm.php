<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use App\Mail\JewelInquiry;
use Illuminate\Support\Facades\Log;

class JewelContactForm extends Component
{
    public $name = '';
    public $email = '';
    public $message = '';
    public $jewel = null;
    public $showForm = false;
    public $success = false;
    public $submitting = false;
    public $error = false;

    protected $rules = [
        'name' => 'required|min:2',
        'email' => 'required|email',
        'message' => 'required|min:10',
    ];

    public function mount($jewel)
    {
        $this->jewel = $jewel;
        $this->message = "Je suis intéressé(e) par {$jewel->name} au prix de €" . number_format($jewel->price, 2) . ".";
    }

    public function submit()
    {
        $this->validate();
        
        $this->submitting = true;

        try {
            // Send email
            Mail::to(config('mail.admin_address', 'contact@axel.com'))->send(new JewelInquiry(
                $this->name,
                $this->email,
                $this->message,
                $this->jewel
            ));

            $this->success = true;
            $this->reset(['name', 'email', 'message']);
            $this->showForm = false;
            
            Log::info('Jewel inquiry submitted successfully for jewel: ' . $this->jewel->name);
        } catch (\Exception $e) {
            $this->error = true;
            Log::error('Jewel inquiry email failed: ' . $e->getMessage());
        } finally {
            $this->submitting = false;
        }
    }

    public function render()
    {
        return view('livewire.jewel-contact-form');
    }
}
