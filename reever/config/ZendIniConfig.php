<?php
if(!class_exists('Zend_Config_Ini')){
	require_once('Zend/Config/Ini.php');
}

require_once('reever/config/config.interface.php');

final class ZendIniConfig extends Zend_Config_Ini implements IEONConfig{
	
	public function __construct($filename, $section, $options = array()){
		parent::__construct($filename, $section, $options);
	}
	
	public static function loadConfFile($iniFile, $path){
		//return new ZendIniConfig(Kernel::getRootPath()."/config/".$iniFile.'.app.config.ini', APPLICATION_ENV);
		try{
		return new ZendIniConfig($path.DS.$iniFile.'.app.ini', EON_APPLICATION_ENV);
		}catch(Exception $e){
			var_dump($e);
		}
	}
	
} 