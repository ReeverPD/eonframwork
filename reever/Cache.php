<?php
class Reever_Cache{
	
	static private $_prefix = "--metacache-";
	static private $_basePath = "";
	
	static public function loadCache($key){
		if(Reever_Cache::$_basePath == ""){
			$p = pathinfo(__FILE__);
			Reever_Cache::$_basePath = $p['dirname'].'/../cache/';
		}
		if(is_file(Reever_Cache::$_basePath.Reever_Cache::$_prefix.$key)){
			return unserialize(file_get_contents(Reever_Cache::$_basePath.Reever_Cache::$_prefix.$key));
		}else{
			return false;
		}
	}
	
	static public function saveCache($key, $data){
		file_put_contents(Reever_Cache::$_basePath.Reever_Cache::$_prefix.$key, serialize($data));
	}
	
}