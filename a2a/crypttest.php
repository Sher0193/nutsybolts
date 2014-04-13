<?php

define('KEY', 'Thank you Mario, but our Princess is in another castle!');
   
    function encrypt($text)
    {
        return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, KEY, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
    }

    function decrypt($text)
    {
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, KEY, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    } 
    
$encrypt = encrypt("This is a test message.");
print $encrypt . "<br/>\n";

print decrypt($encrypt) . "<br/>\n";
    
    
?>