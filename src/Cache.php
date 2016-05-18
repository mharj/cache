<?php
namespace mharj;

abstract class Cache {
	private $disabled = false;
	private $hitcount = 0;
	private $misscount = 0;
	protected $hit = false;
	
	/**
	 * Set cache value
	 * @param string $key
	 * @param mixed $value
	 */
	abstract protected function setValue($key,$value);
	
	/**
	 * Get cache value
	 * @param string $key
	 * @return mixed
	 */
	abstract protected function getValue($key);
	
	/**
	 * Cache have value
	 * @param string $key
	 * @return boolean
	 */
	abstract protected function haveValue($key);
	
	/**
	 * Delete cache key/value
	 * @param string $key
	 * @return boolean
	 */	
	abstract public function delete($key);
	
	/**
	 * Get data from cache else use callback function to get data
	 * @param string $key
	 * @param mixed param,param,param,...
	 * @param callable $function
	 * @return mixed
	 */
	public function get() {
		$args = func_get_args();
		$key = array_shift($args); // key
		$function = array_pop($args); // function
		if ( ! is_callable($function) ) {
			throw new \Exception("last parameter is not callable");
		}
		if ($this->disabled == true ) {
			$this->misscount++;
			return call_user_func_array($function,$args);
		}
		if ($this->haveValue($key)) {
			$this->hit = true;
			$this->hitcount++;
			return $this->getValue($key);
		}
		$this->misscount++;
		$this->hit = false;
		$value = call_user_func_array($function,$args);
		$this->setValue($key,$value);
		return $value;
	}

	/**
	 * if last get was from cache
	 * @return boolean
	 */
	public function wasHit() {
		return $this->hit;
	}
	
	/**
	 * get cache statistics
	 * @return array
	 */
	public function stats() {
		return array("hit"=>$this->hitcount,"miss"=>$this->misscount);
	}
	
	/**
	 * enable/disable cache
	 * @param boolean $value
	 */
	public function setDisabled($value) {
		$this->disabled = ($value==true?true:false);
	}
}
