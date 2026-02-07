<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class FileEncryptionService
{
    /**
     * Encrypt and store a file
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $path
     * @return string
     */
    public function encryptAndStore($file, $path = 'requirements')
    {
        // Read file contents
        $fileContents = file_get_contents($file->getRealPath());
        
        // Encrypt the file contents
        $encryptedContents = Crypt::encryptString($fileContents);
        
        // Generate unique filename
        $filename = time() . '_' . md5($file->getClientOriginalName()) . '.enc';
        $fullPath = $path . '/' . $filename;
        
        // Store encrypted file
        Storage::disk('private')->put($fullPath, $encryptedContents);
        
        return $fullPath;
    }

    /**
     * Decrypt and retrieve file
     *
     * @param string $filePath
     * @return string
     */
    public function decryptFile($filePath)
    {
        // Get encrypted contents
        $encryptedContents = Storage::disk('private')->get($filePath);
        
        // Decrypt and return
        return Crypt::decryptString($encryptedContents);
    }

    /**
     * Delete encrypted file
     *
     * @param string $filePath
     * @return bool
     */
    public function deleteEncryptedFile($filePath)
    {
        return Storage::disk('private')->delete($filePath);
    }

    /**
     * Check if file exists
     *
     * @param string $filePath
     * @return bool
     */
    public function fileExists($filePath)
    {
        return Storage::disk('private')->exists($filePath);
    }
}