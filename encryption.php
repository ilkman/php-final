<?php
function encryptAES($data, $password)
{
    $method = 'AES-256-CBC'; //algorithm
    $key = hash('sha256', $password, true); //256-bit key creation from password (sha256)
    $iv = openssl_random_pseudo_bytes(16);
    $cipher = openssl_encrypt($data, $method, $key, OPENSSL_RAW_DATA, $iv); //encrypting the data
    return base64_encode($iv . $cipher);
}

function decryptAES($encodedData, $password)
{
    $method = 'AES-256-CBC'; //algorithm
    $key = hash('sha256', $password, true); //getting the same key from the password
    $data = base64_decode($encodedData);
    $iv = substr($data, 0, 16);
    $cipher = substr($data, 16);
    return openssl_decrypt($cipher, $method, $key, OPENSSL_RAW_DATA, $iv); //decrypt and returnj the password
}