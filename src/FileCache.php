<?php
namespace mharj;

class FileCache extends Cache {
	private $directory;
	/**
	 * Set cache directory
	 * @param string $directory
	 * @throws \Exception
	 */
	public function __construct($directory) {
		if ( ! is_dir($directory) ) {
			throw new \Exception("Cache directory does not exists");
		}
		$this->directory = $directory;
	}
	
	protected function getValue($key) {
		$fd = fopen($this->getFileName($key), "r");
		$value = fread($fd, filesize($this->getFileName($key)));
		fclose($fd);
		return unserialize($value);
	}

	protected function haveValue($key) {
		return is_file($this->getFileName($key));
	}

	protected function setValue($key, $value) {
		$fd=fopen($this->getFileName($key),'w');
		fwrite($fd,serialize($value));
		fclose($fd);
	}

	public function delete($key) {
		if ( is_file($this->getFileName($key))) {
			if ( unlink($this->getFileName($key)) == false ) {
				throw new \Exception("Can't delete cache file");
			}
		}
	}	
	
	/**
	 * Build filepath + name for cache file
	 * @param string $key
	 * @return string
	 */
	private function getFileName($key) {
		return $this->directory."/".preg_replace("/\W/","_",$key).".cache";
	}
}
