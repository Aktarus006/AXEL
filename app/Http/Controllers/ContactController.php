<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use App\Mail\JewelInquiry;
use App\Models\Jewel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function sendContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|min:2',
            'email' => 'required|email',
            'message' => 'required|min:10',
        ]);
        
        try {
            Mail::to(config('mail.admin_address', 'contact@axel.com'))->send(new ContactFormMail(
                $validated['name'],
                $validated['email'],
                $validated['message']
            ));
            
            Log::info('Contact form email sent successfully');
            return back()->with('success', 'Message sent successfully!');
        } catch (\Exception $e) {
            Log::error('Contact form email failed: ' . $e->getMessage());
            return back()->with('error', 'Error sending message. Please try again.');
        }
    }
    
    public function sendJewelInquiry(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|min:2',
            'email' => 'required|email',
            'message' => 'required|min:10',
            'jewel_id' => 'required|exists:jewels,id',
        ]);
        
        try {
            $jewel = Jewel::findOrFail($validated['jewel_id']);
            
            Mail::to(config('mail.admin_address', 'contact@axel.com'))->send(new JewelInquiry(
                $validated['name'],
                $validated['email'],
                $validated['message'],
                $jewel
            ));
            
            Log::info('Jewel inquiry email sent successfully for jewel: ' . $jewel->name);
            return back()->with('success', 'Message sent successfully!');
        } catch (\Exception $e) {
            Log::error('Jewel inquiry email failed: ' . $e->getMessage());
            return back()->with('error', 'Error sending message. Please try again.');
        }
    }
}
