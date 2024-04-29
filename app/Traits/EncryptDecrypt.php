<?php

namespace App\Traits;

trait EncryptDecrypt
{
    private $ciphering = 'AES-256-CBC';

    private $secret_iv = '241235VDFASDGFSDGSDGDFGDFHFGHF';

    private $options = 0;

    private function generateEncryptionIV(){
        return substr(hash('sha256', $this->secret_iv), 0, 16);
    }

    private function hashKey($secret_key){
        return hash('sha256', $secret_key);;
    }

    public function generateEncryptionKey($userID){

        return $userID . time();
    }

    public function encryptString($string, $secret_key){

        $key = $this->hashKey($secret_key);

        return openssl_encrypt( $string, $this->ciphering,
        $key, $this->options, $this->generateEncryptionIV() );
    }

    public function decryptString($encryptedString, $secret_key){

        $key = $this->hashKey($secret_key);

        return openssl_decrypt( $encryptedString, $this->ciphering,
        $key, $this->options, $this->generateEncryptionIV() );
    }

}
