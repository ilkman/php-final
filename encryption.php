<?php
function encryptAES($data, $password)
{
    $method = 'AES-256-CBC';
    $key = hash('sha256', $password, true);
    $iv = openssl_random_pseudo_bytes(16);
    $cipher = openssl_encrypt($data, $method, $key, OPENSSL_RAW_DATA, $iv);
    return base64_encode($iv . $cipher);
}

function decryptAES($encodedData, $password)
{
    $method = 'AES-256-CBC';
    $key = hash('sha256', $password, true);
    $data = base64_decode($encodedData);
    $iv = substr($data, 0, 16);
    $cipher = substr($data, 16);
    return openssl_decrypt($cipher, $method, $key, OPENSSL_RAW_DATA, $iv);
}