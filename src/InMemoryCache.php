<?php
namespace mharj;

class InMemoryCache extends Cache {
	private $data = array();
	protected function getValue($key) {
		return $this->data[$key];
	}
	
	protected function setValue($key, $value) {
		$this->data[$key] = $value;
	}
	protected function haveValue($key) {
		return isset($this->data[$key]);
	}
	public function delete($key) {
		if ( $this->haveValue($key) ) {
			unset($this->data[$key]); 
		}
	}
}
