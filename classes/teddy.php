<?
/**
 * @name teddy-bear
 * @class teddy
 * @url http://github.com/j99/teddy-bear
 * @git git://github.com/j99/teddy-bear.git
 * @author jason silberman (http://github.com/j99)
 */

Class Teddy {
	protected $_b, $_rounds = 10;

	public function __construct($key) {
		if (is_object($key)) {
			$this->_b = $key;
		} else {
			$this->_b = new Bear($this->create_key($key), $this->create_iv());
		}
	}

	public function get() {
		return $this->_b;
	}

	public function encrypt($text) {
		return base64_encode($this->_encrypt($this->_b, $text));
	}

	public function decrypt($text) {
		return rtrim($this->_decrypt($this->_b, base64_decode($text)), "\0");
	}

	public function tcrypt($str, $salt, $rounds = 10) {
		return $this->_tcrypt($str, $rounds, $salt);
	}

	protected function _encrypt($bear, $string) {
		$string = (string) $string;
		$iv = $bear->get_iv();
		$key = $bear->get_key();
		$e = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $string, MCRYPT_MODE_CBC, $iv);
		return $e;
	}

	protected function _decrypt($bear, $string) {
		$string = (string) $string;
		$iv = $bear->get_iv();
		$key = $bear->get_key();
		$d = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $string, MCRYPT_MODE_CBC, $iv);
		return $d;
	}

	protected function create_iv() {
		return mcrypt_create_iv(32);
	}

	protected function create_key($key) {
		$m = microtime(true);
		$k = $key;
		$a = defined('APP_KEY') ? APP_KEY : $this->random_str(32);
		$salt = substr(str_replace('+', '.', base64_encode(sha1(microtime(true), true))), 0, 22);
		$t = $this->_tcrypt($m.$k.$a, $this->_rounds, $salt);
		return md5($t);
	}

	protected function _tcrypt($str, $rounds, $salt) {
		return crypt($str, '$2a$' . $rounds . '$' . $salt);
	}

	protected function random_str($l = 16) {
		$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		return substr(str_shuffle(str_repeat($pool, 5)), 0, $l);
	}
}