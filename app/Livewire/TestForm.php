<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;

class TestForm extends Component
{
    public $name = '';
    public $email = '';
    public $message = '';
    public $status = 'Form not submitted yet';

    public function submit()
    {
        $this->status = 'Submit function called at ' . now();
        Log::info('Test form submit function called from component class');
    }

    public function render()
    {
        return view('livewire.test-form');
    }
}
