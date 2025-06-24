<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {recipient?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test email to verify mail configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $recipient = $this->argument('recipient') ?: config('mail.admin_address', 'contact@axel.com');
        
        $this->info("Sending test email to: {$recipient}");
        
        try {
            Mail::to($recipient)->send(new ContactFormMail(
                'Test User',
                'test@example.com',
                'This is a test message to verify email configuration is working correctly.'
            ));
            
            $this->info('✅ Test email sent successfully!');
            $this->info('Mail configuration:');
            $this->table(
                ['Setting', 'Value'],
                [
                    ['Mailer', config('mail.default')],
                    ['Host', config('mail.mailers.smtp.host')],
                    ['Port', config('mail.mailers.smtp.port')],
                    ['Username', config('mail.mailers.smtp.username')],
                    ['Encryption', config('mail.mailers.smtp.encryption')],
                    ['From Address', config('mail.from.address')],
                    ['From Name', config('mail.from.name')],
                ]
            );
        } catch (\Exception $e) {
            $this->error('❌ Failed to send test email: ' . $e->getMessage());
            $this->error('Full error: ' . $e->__toString());
        }
    }
}
