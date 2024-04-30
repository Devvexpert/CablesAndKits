<?php

/**
 * EncryptDecrypt.php
 *
 * This trait provides methods for encrypting and decrypting.
 */

namespace App\Traits;

trait EncryptDecrypt
{
    private $_ciphering = 'AES-256-CBC';

    private $_secret_iv = '241235VDFASDGFSDGSDGDFGDFHFGHF';

    private $_options = 0;


     /**
     * Generate the encryption initialization vector (IV).
     *
     * @return string
     */
    private function _generateEncryptionIV()
    {
        return substr(hash('sha256', $this->_secret_iv), 0, 16);
    }

    /**
     * Hash the encryption key.
     *
     * @param string $secret_key The encryption key.
     * 
     * @return string
     */
    private function _hashKey($secret_key)
    {
        return hash('sha256', $secret_key);;
    }

    /**
     * Generate an encryption key based on the user ID and current time.
     *
     * @param int $user_id The user ID.
     * 
     * @return string
     */
    public function generateEncryptionKey($user_id)
    {
        return $user_id . time();
    }

    /**
     * Encrypt a string using AES encryption.
     *
     * @param string $string The string to encrypt.
     * @param string $secret_key The encryption key.
     * 
     * @return string|false The encrypted string, or false on failure.
     */
    public function encryptString($string, $secret_key)
    {
        $key = $this->_hashKey($secret_key);

        return openssl_encrypt( 
            $string,
            $this->_ciphering,
            $key,
            $this->_options,
            $this->_generateEncryptionIV() 
        );
    }

    /**
     * Decrypt a string using AES decryption.
     *
     * @param string $encrypted_string The encrypted string.
     * @param string $secret_key The encryption key.
     * 
     * @return string|false The decrypted string, or false on failure.
     */
    public function decryptString($encrypted_string, $secret_key)
    {
        $key = $this->_hashKey($secret_key);

        return openssl_decrypt( 
            $encrypted_string, 
            $this->_ciphering,
            $key, 
            $this->_options, 
            $this->_generateEncryptionIV() 
        );
    }
}
