<?php 

namespace Mooo\System\Security;

/**
 * Class responsible for some sort of security operations on some sort
 * of data.
 * 
 * @author khernik
 */
class Security {
	
	/**
	 * Prevents xss attacks by encoding HTML tags
	 * 
	 * @param string $str
	 * @return string
	 */
	public static function xss_clean($str)
	{
		return htmlspecialchars($str);
	}
	
	/**
	 * Hashes given variable using blowfish methods.
	 *
	 * @param string $password
	 */
	public static function hash($password, $function)
	{
		return $function($password);
	}
	
	/**
	 * Method for sanitizing filenames
	 * 
	 * @param string $str
	 * 
	 * @return string
	 */
	public static function sanitize_Filename($str)
	{
		$replace_chars = [
			'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 
			'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
			'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 
			'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
			'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 
			'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
			'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
			'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
			'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 
			'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
			'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 
			'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
			'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 
			'ÿ'=>'y', 'ƒ'=>'f'
		];
		$f = strtr($f, $replace_chars);
		
		// convert & to "and", @ to "at", and # to "number"
		$f = preg_replace(array('/[\&]/', '/[\@]/', '/[\#]/'), array('-and-', '-at-', '-number-'), $f);
		
		// removes any special chars we missed
		$f = preg_replace('/[^(\x20-\x7F)]*/','', $f);
		
		// convert space to hyphen
		$f = str_replace(' ', '-', $f);
		
		// removes apostrophes
		$f = str_replace('\'', '', $f);
		
		// remove non-word chars (leaving hyphens and periods)
		$f = preg_replace('/[^\w\-\.]+/', '', $f);
		
		// converts groups of hyphens into one
		$f = preg_replace('/[\-]+/', '-', $f);
		
		return strtolower($f);
	}
	
	/**
	 * Strips image tags into the given replacement string
	 * 
	 * @param string $str
	 * @param string $to
	 * @return string
	 */
	public static function strip_image($str, $to = '(image)')
	{
		$str = preg_replace("/<img[^>]+\>/i", $to, $content);
		
		return $str;
	}
	
	/**
	 * Escapes PHP tags inside of the given string
	 * 
	 * @param string $str
	 * @return string
	 */
	public static function encode_php_tags($str)
	{
		return str_replace(['<?php', '<?PHP', '<?', '?>'],  ['&lt;?php', '&lt;?PHP', '&lt;?', '?&gt;'], $str);
	}
	                                                                                                                                                                                                                                                                                                                                                                  
} // End \Mooo\System\Security\Security
