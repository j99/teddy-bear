<?php
/**
 * @name teddy-bear
 * @class teddy
 * @url http://github.com/pxlsqre/teddy-bear
 * @git git://github.com/pxlsqre/teddy-bear.git
 * @author jason silberman (http://github.com/pxlsqre)
 */

Class Teddy {
	protected
		/**
		 * the Bear instance
		 * @scope protected
		 * @var {Bear} object
		 */
		$_b,

		/**
		 * How many rounds to do when using the crypt function
		 * @scope protected
		 * @var {Integer}
		 */
		$_rounds = 10;

	/**
	 * create a Teddy instance
	 * @scope public
	 * @param {String|Bear} $key the private key, or, a pre-defined Bear instance
	 * @param {Integer} $rounds the default amount of rounds, default is 10
	 */
	public function __construct($key, $rounds = 10) {
		if (is_object($key)) {
			$this->_b = $key;
		} else {
			$this->set_rounds($rounds);
			$this->_b = new Bear($this->create_key($key), $this->create_iv());
		}
	}

	/**
	 * retrieve the Bear instance
	 * @scope public
	 * @return {Bear} object
	 */
	public function get() {
		return $this->_b;
	}

	/**
	 * encrypt given text
	 * @scope public
	 * @param  {String} $text the text to be encrypted
	 * @return {String}       the encrpyted string
	 */
	public function encrypt($text) {
		return base64_encode($this->_encrypt($this->_b, $text));
	}

	/**
	 * decrypt given encrypted text
	 * @scope public
	 * @param  {String} $text the encrypted text to be decrpyted
	 * @return {String}       the decrypted text
	 */
	public function decrypt($text) {
		return rtrim($this->_decrypt($this->_b, base64_decode($text)), "\0");
	}

	/**
	 * a sub-class of `crypt`
	 * @scope public
	 * @param  {String}  $str    the string to be hashed
	 * @param  {String}  $salt   the salt to be used
	 * @param  {Integer} $rounds how many rounds to do, default is $this->_rounds;
	 * @return {String}          the hashed string
	 */
	public function tcrypt($str, $salt, $rounds) {
		if (!$rounds or !is_int($rounds)) {
			$rounds = $this->_rounds;
		}
		return $this->_tcrypt($str, $rounds, $salt);
	}

	/**
	 * set the default number of rounds
	 * @param {Integer} $rounds how many rounds to do when encrypting
	 */
	public function set_rounds($rounds) {
		$this->_rounds = (int) $rounds;
	}

	/**
	 * --- protected methods ---
	 */

	/**
	 * encrypt given text
	 * @scope protected
	 * @param  {Bear} $bear   Bear object
	 * @param  {String} $string text to be encrypted
	 * @return {String}         the encrypted text
	 */
	protected function _encrypt($bear, $string) {
		$string = (string) $string;
		$iv = $bear->get_iv();
		$key = $bear->get_key();
		$e = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $string, MCRYPT_MODE_CBC, $iv);
		return $e;
	}

	/**
	 * decrypt given text
	 * @scope protected
	 * @param  {Bear} $bear   Bear object
	 * @param  {String} $string text to be decrypted
	 * @return {String}         the decrpyted text
	 */
	protected function _decrypt($bear, $string) {
		$string = (string) $string;
		$iv = $bear->get_iv();
		$key = $bear->get_key();
		$d = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $string, MCRYPT_MODE_CBC, $iv);
		return $d;
	}

	/**
	 * create an iv for encryption
	 * @scope protected
	 * @return {String} the initializing vector
	 */
	protected function create_iv() {
		return mcrypt_create_iv(32);
	}

	/**
	 * create a hashed key for encryption
	 * @scope protected
	 * @param  {String} $key the private key
	 * @return {String}      the hashed key for encryption
	 */
	protected function create_key($key) {
		$m = microtime(true);
		$k = $key;
		$a = defined('APP_KEY') ? APP_KEY : $this->random_str(32);
		$salt = substr(str_replace('+', '.', base64_encode(sha1(microtime(true), true))), 0, 22);
		$t = $this->_tcrypt($m.$k.$a, $this->_rounds, $salt);
		return md5($t);
	}

	/**
	 * a sub-class of `crypt`
	 * @scope protected
	 * @param  {String}  $str    the string to be hashed
	 * @param  {Integer} $rounds how many rounds to do
	 * @param  {String}  $salt   the salt to be used
	 * @return {String}          the hashed string
	 */
	protected function _tcrypt($str, $rounds, $salt) {
		return crypt($str, '$2a$' . $rounds . '$' . $salt);
	}

	/**
	 * generate a random string
	 * @param  {Integer} $l length of the string
	 * @return {String}     the random string
	 */
	protected function random_str($l = 16) {
		$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		return substr(str_shuffle(str_repeat($pool, 5)), 0, $l);
	}
}
