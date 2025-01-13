<?php

    function cryptId($string, $action = 'enc')
    {
        // you may change these values to your own
        $secret_key = '_0120RxTNF9THmRHu5xlvL99GjAxWakgURiYe0120';
        $secret_iv = 'user_uid__' . auth()->id();
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'enc') {
            $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
        } else if ($action == 'dec') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }
