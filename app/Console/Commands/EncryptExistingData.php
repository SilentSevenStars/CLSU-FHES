<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class EncryptExistingData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:encrypt-existing-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage encryption of existing data in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->choice('What do you want to do?', ['encrypt', 'decrypt_emails'], 0);

        if ($action === 'encrypt') {
            $this->encryptData();
        } elseif ($action === 'decrypt_emails') {
            $this->decryptEmails();
        }
    }

    private function encryptData()
    {
        $this->info('Starting encryption of existing data...');

        // Encrypt User data
        $this->encryptModel(\App\Models\User::class, 'Users');

        // Encrypt Applicant data
        $this->encryptModel(\App\Models\Applicant::class, 'Applicants');

        // Encrypt Evaluation remarks
        $this->encryptModel(\App\Models\Evaluation::class, 'Evaluations');

        // Encrypt Notification subjects and messages
        $this->encryptModel(\App\Models\Notification::class, 'Notifications');

        // Encrypt Representative names
        $this->encryptModel(\App\Models\Representative::class, 'Representatives');

        $this->info('Encryption completed successfully!');
    }

    private function decryptEmails()
    {
        $this->info('Decrypting user emails...');

        // Temporarily add encrypted cast for email
        $originalCasts = \App\Models\User::getCasts();
        \App\Models\User::mergeCasts(['email' => 'encrypted']);

        \App\Models\User::chunk(100, function ($users) {
            foreach ($users as $user) {
                $decryptedEmail = $user->email; // This decrypts it
                // Now save without cast
                $user->setCasts([]); // Remove casts temporarily
                $user->email = $decryptedEmail;
                $user->save();
            }
        });

        // Restore original casts
        \App\Models\User::setCasts($originalCasts);

        $this->info('Emails decrypted successfully!');
    }

    private function encryptModel($modelClass, $modelName)
    {
        $this->info("Encrypting {$modelName}...");

        $modelClass::chunk(100, function ($records) {
            foreach ($records as $record) {
                $record->save(); // This will encrypt the fields with 'encrypted' cast
            }
        });

        $this->info("{$modelName} encrypted.");
    }
}
