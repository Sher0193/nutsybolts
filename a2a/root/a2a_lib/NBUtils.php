<?php

class NBUtils {
	public static function shuffle($array) {
		if (!is_array($array)) return $array;
		$length = count($array);
		
		$i = $length - 1;
		while ($i > 0) {
			$random = mt_rand(0, $i);
			$temp = $array[$random];
			$array[$random] = $array[$i];
			$array[$i] = $temp;			
			$i--;
		}
		
		return $array;
	}
	
	public static function getPassword($length) {
		$password_chars = 'abcdefghijklmnopqrstuvwxyz1234567890';
		$password = '';
		for ($i = 0; $i < $length; $i++)
			$password .= substr($password_chars, mt_rand(0, strlen($password_chars)), 1);
			
		return $password;
	}
	
	public static function getPlayerColor() {
		$valid = false;
		
		while (!$valid) {
			$color = dechex(mt_rand(0, pow(2, 24)));
			while (strlen($color) < 6)
				$color = '0' . $color;
			
			// make sure the color isn't too close to white, otherwise it won't show up on the background.
			if ($color[0] != 'f' && $color[2] != 'f' && $color[4] != 'f')
				$valid = true;
		}
			
		return $color;
	}
	
	public static function encrypt($text) {
		// hash
		$key = hash('sha256', ENCRYPTION_KEY);
        // iv hash
		$iv = substr(hash('sha256', ENCRYPTION_IV), 0, 16);
		
		return trim(base64_encode(openssl_encrypt($text, ENCRYPTION_METHOD, $key, 0, $iv)));
		
    }

	public static function decrypt($text) {
        // hash
		$key = hash('sha256', ENCRYPTION_KEY);
        // iv hash
		$iv = substr(hash('sha256', ENCRYPTION_IV), 0, 16);
	
		return trim(openssl_decrypt(base64_decode($text), ENCRYPTION_METHOD, $key, 0, $iv));
    }
    
    public static function raiseError($error_code) {
    	header('Location: /index.php?status=' . $error_code);
		exit;
    }
    
}

?>
