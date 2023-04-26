<?php

namespace App\Utilities;

use Illuminate\Support\Facades\Crypt;

class EncryptionUtility
{
    /**
     * Encrypt data
     */
    public static function encryptString(string $sData)
    {
        return Crypt::encryptString($sData);
    }

    /**
     * Decrypt encrypted string 
     */
    public static function decryptString(string $sValue)
    {
        return Crypt::decryptString($sValue);
    }
}