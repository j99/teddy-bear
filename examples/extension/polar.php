<?

class Polar extends Teddy {
	public function encrypt($text) {
		return sha1($text);
	}
	public function decrypt($text) {
		return 'Oops';
	}
}
