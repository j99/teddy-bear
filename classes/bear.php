<?
/**
 * @name teddy-bear
 * @class bear
 * @url http://github.com/j99/teddy-bear
 * @git git://github.com/j99/teddy-bear.git
 * @author jason silberman (http://github.com/j99)
 */

class Bear {
	protected $_key, $_iv;
	public function __construct($key, $iv) {
		$this->_key = $key;
		$this->_iv = $iv;
	}
	public function get_key() {
		return $this->_key;
	}
	public function get_iv() {
		return $this->_iv;
	}
}