<?php
namespace mharj;

class MemcacheCache extends Cache {
	private $compress = 0;
	private $ttl = 0;
	private $memcache;
	public function __construct($memcache) {
		$this->memcache = $memcache;
	}
	
	protected function getValue($key) {
		return $this->memcache->get($key);
	}
	protected function haveValue($key) {
		return ! (empty($this->memcache->get($key)));
	}
	protected function setValue($key, $value) {
		$this->memcache->set($key,$value,$this->compress,$this->ttl);
	}
	
	public function delete($key) {
		$this->memcache->delete($key);
	}
	
	// memcache specific
	public function setCompress($value) {
		$this->compress = ($value==true?MEMCACHE_COMPRESSED:0);
	}
	
	public function setTTL($value) {
		$this->ttl = $value;
	}
}
