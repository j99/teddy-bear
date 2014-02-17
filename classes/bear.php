<?php
/**
 * @name teddy-bear
 * @class bear
 * @url http://github.com/pxlsqre/teddy-bear
 * @git git://github.com/pxlsqre/teddy-bear.git
 * @author jason silberman (http://github.com/pxlsqre)
 */

class Bear {
	protected
		/**
		 * the key used for encryption
		 * @scope protected
		 * @var {String}
		 */
		$_key,

		/**
		 * the initializing vector
		 * @scope protected
		 * @var {String}
		 */
		$_iv;

	/**
	 * create a Bear instance
	 * @scope public
	 * @param {String} $key the key used for encryption
	 * @param {String} $iv  the initializing vector
	 */
	public function __construct($key, $iv) {
		$this->_key = $key;
		$this->_iv = $iv;
	}

	/**
	 * retrieve the key
	 * @scope public
	 * @return {String} the encryption key
	 */
	public function get_key() {
		return $this->_key;
	}

	/**
	 * retreive the iv
	 * @scope public
	 * @return {String} the initializing vecot
	 */
	public function get_iv() {
		return $this->_iv;
	}
}
