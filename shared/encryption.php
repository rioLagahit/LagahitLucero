<?php
	
	function incrementLetter($letter) {

		if ($letter == 'z'){

			return 'a';
		}
		else{

			return ++$letter;
		}
	}

	function decrementLetter($letter) {

		if ($letter == 'a'){

			return 'z';
		}
		else{

			return chr(ord($letter) - 1 );
		}
	}

	function rot1_encrypt($string) {

		$newStr = "";

		for ($i=0; $i < strlen($string); $i++) { 
			
			$newStr .= incrementLetter($string[$i]);
		}

		return $newStr;
	}

	function transposition1_encrypt($string) {

		for ($i=0; $i < strlen($string); $i++) { 
			
			if ($i < strlen($string)-1) {

				$dummy = $string[$i];
				$string[$i] = $string[$i+1];
				$string[$i+1] = $dummy;

				$i++;
			}
		}

		return $string;
	}

	function transposition2_encrypt($string) {

		$newStr = "";

		for ($i=strlen($string)-1; $i >= 0; $i--) { 
			
			$newStr .= $string[$i];
		}

		return $newStr;
	}

	function rot1_decrypt($string) {

		$newStr = "";

		for ($i=0; $i < strlen($string); $i++) { 
			
			$newStr .= decrementLetter($string[$i]);
		}

		return $newStr;
	}

	function transposition1_decrypt($string) {

		for ($i=0; $i < strlen($string); $i++) { 
			
			if ($i < strlen($string)-1) {

				$dummy = $string[$i];
				$string[$i] = $string[$i+1];
				$string[$i+1] = $dummy;

				$i++;
			}
		}

		return $string;
	}

	function transposition2_decrypt($string) {

		$newStr = "";

		for ($i=strlen($string)-1; $i >= 0; $i--) { 
			
			$newStr .= $string[$i];
		}

		return $newStr;
	}

	function encrypt($string) {

		$string = rot1_encrypt($string);
		$string = transposition1_encrypt($string);
		$string = transposition2_encrypt($string);

		return $string;
	}

	function decrypt($string) {
		
		$string = rot1_decrypt($string);
		$string = transposition1_decrypt($string);
		$string = transposition2_decrypt($string);

		return $string;
	}
?>