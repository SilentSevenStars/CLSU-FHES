<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Config;

class EncryptionHealthCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'encryption:health {--verbose}';
    protected $description = 'Check encryption setup (APP_KEY, Crypt, OpenSSL)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('🔒 Encryption Health Check');

        // APP_KEY check
        $appKey = Config::get('app.key');
        if (empty($appKey) || $appKey === 'base64:') {
            $this->error('❌ APP_KEY missing or invalid!');
            $this->line('  Run: php artisan key:generate');
            return self::FAILURE;
        }
        $this->info('✅ APP_KEY configured');

        // OpenSSL extension
        if (!extension_loaded('openssl')) {
            $this->error('❌ PHP OpenSSL extension not loaded!');
            $this->line('  VPS Hostinger: apt install php-openssl && service apache2 restart (or nginx)');
            return self::FAILURE;
        }
        $this->info('✅ OpenSSL extension loaded');

        // Crypt test
        $testData = 'encryption-test-' . time();
        try {
            $encrypted = Crypt::encrypt($testData);
            $this->line('  Encrypted OK');

            $decrypted = Crypt::decrypt($encrypted);
            if ($decrypted === $testData) {
                $this->info('✅ Crypt encrypt/decrypt working perfectly');
            } else {
                $this->error('❌ Decryption mismatch!');
                return self::FAILURE;
            }
        } catch (\Throwable $e) {
            $this->error('❌ Crypt operation failed: ' . $e->getMessage());
            Log::error('Encryption health check failed: ' . $e->getMessage());
            return self::FAILURE;
        }

        $this->info("\n🎉 All encryption checks passed! Data will be encrypted properly.");
        $this->warn('Deploy to VPS: Ensure .env has APP_KEY and run this command to verify.');
        return self::SUCCESS;
    }
}

