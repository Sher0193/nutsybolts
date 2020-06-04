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
	/*
	public static function encrypt($text) {
		return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, ENCRYPTION_KEY, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
    }

	public static function decrypt($text) {
		return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, ENCRYPTION_KEY, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }
    */
    public static function raiseError($error_code) {
    	header('Location: /index.php?status=' . $error_code);
		exit;
    }
}

?>
