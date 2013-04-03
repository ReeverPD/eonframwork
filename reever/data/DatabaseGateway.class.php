<?php
if(!class_exists('Zend_Db')){
	require_once('Zend/Db.php');
}
if(!class_exists('Zend_Cache')){
	require_once('Zend/Cache.php');
}

if(!class_exists('Zend_Registry')){
	include_once('Zend/Registry.php');
}

if(!class_exists('Zend_Db_Table_Abstract')){
	require_once('Zend/Db/Table/Abstract.php');
}

if(!class_exists('Zend_Paginator')){
	include_once('Zend/Paginator.php');
}

class DatabaseGateway {
	
	private $db = array();
	
	public function __construct($cId = ''){
		if(!is_dir(EON_CACHE_DIR.'/_'.$cId.'_db')){
			mkdir(EON_CACHE_DIR.'/_'.$cId.'_db');
			chmod(EON_CACHE_DIR.'/_'.$cId.'_db', 777);
		}
		$frontendOptions = array(
			'caching' => true,
			'lifetime' => 3600 * 24 * 30,
			'automatic_serialization' => true
		);
		
		$backendOptions  = array(
			'cache_dir' => EON_CACHE_DIR.'/_'.$cId.'_db'
		);
					
		$cache = Zend_Cache::factory('Core',
		                             'File',
									 $frontendOptions,
									 $backendOptions);
									 
		Zend_Registry::set('cache', $cache);									 
		Zend_Db_Table_Abstract::setDefaultMetadataCache($cache);
	}
	
	public function getConnection($name = ''){
		if($name == ''){
			$name = 'default'; //Nome Default da conex�o
		}
		if(!isset($this->db[$name])){
			$this->connect($name);
		}
		return $this->db[$name];
	}

	/**
	 * Conecta a uma certa base de dados
	 * com os parametros informado no arquivo de configura��o
	 * 
	 * @param string $name
	 */
	public function connect($name){
		if(!isset($this->db[$name])){
			$dbId = DB_NAME;
			$config = new ZendIniConfig(INI_FILE, APPLICATION_ENV);
			//$cfg = EONKernel::getDBParams($name);
			$cfg = array();
			$cfg['type'	 ] = $config->resources->db->$dbId->params->server;
			$cfg['server'] = $config->resources->db->$dbId->params->host;
			$cfg['user'  ] = $config->resources->db->$dbId->params->user;
			$cfg['pass'  ] = $config->resources->db->$dbId->params->password;
			$cfg['db'    ] = $config->resources->db->$dbId->params->dbname;
			
			$type = $cfg['type'];
			$host = $cfg['server'];
			$user = $cfg['user'];
			$pass = $cfg['pass'];
			$db   = $cfg['db'];
			$this->db[$name] = Zend_Db::factory($type, array('host'=> $host,
				    							'username' => $user,
				    							'password' => $pass,
				    							'dbname'   => $db
									));
		}
		return $this->db[$name];
	}
	
}