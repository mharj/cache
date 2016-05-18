<?php
namespace mharj;

class StackCache extends Cache {
	private $keyIndex = array();
	private $cacheArray = array();
	public function __construct(array $cacheArray) {
		foreach ( $cacheArray AS $cache) {
			if ( ! ( $cache instanceof Cache)  ) {
				throw new \Exception(get_class($cache)." is not extending Cache class");
			}
		}
		$this->cacheArray = $cacheArray;
	}
	
	protected function setValue($key, $value) {
		foreach ( $this->cacheArray AS $cache ) {
			$cache->setValue($key,$value);
		}
		unset($this->keyIndex[$key]); // back to check all caches
	}
	
	protected function haveValue($key) {
		foreach ( $this->cacheArray AS $k => $cache ) {
			if( $cache->haveValue($key) == true ) {
				$this->keyIndex[$key]=$k;
				return true;
			} 
		}
		return false;
	}
	
	protected function getValue($key) {
		// try to read from last "haveValue==true" index
		if ( isset($this->cacheArray[$this->keyIndex[$key]]) && $this->cacheArray[$this->keyIndex[$key]]->haveValue($key) == true ) {
			$value = $this->cacheArray[$this->keyIndex[$key]]->getValue($key);
			$this->setValue($key,$value); // update all stack caches
			return $value;
		}
		foreach ( $this->cacheArray AS $cache ) {
			if( $cache->haveValue($key) == true ) {
				$value = $cache->getValue($key);
				$this->setValue($key,$value); // update all stack caches
				return $value;
			}
		}
	}
	
	public function delete($key) {
		foreach ( $this->cacheArray AS $cache ) {
			$cache->delete($key);
		}
	}
}
