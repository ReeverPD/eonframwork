<?php



class Reever_SecurityContext{
	
	protected $loggedUser = null;
	
	protected $_isLogged = false;
	
	/**
	 * @var SecurityConfig
	 */
	protected $_secConfig = null;
	
	public function __construct(){
		$this->loadSecuritySession();
	}
	
	protected function loadSecuritySession(){
		if(isset($_SESSION['security']['data'])){
			$this->loggedUser = unserialize($_SESSION['security']['data']);	
		}
		if(isset($_SESSION['security']['isLogged'])){
			$this->_isLogged = $_SESSION['security']['isLogged'];
		}
	}
	
	public function updateSecuritySession(){
		$_SESSION['security']['data'] = serialize($this->loggedUser);	
		$_SESSION['security']['isLogged'] = $this->_isLogged;
	}
	
	protected function _doLogin($user, $pass, $param = null){
		if($this->__loginObject != null){
			$rClass = new ReflectionObject($this->__loginObject);
			$method = $rClass->getMethod($this->__loginMethod);
			return $method->invokeArgs($this->__loginObject, $this->__loginArgs);
		}else{
			return true;
		}
	}
	
	private $__loginObject = null;
	private $__loginMethod = null;
	private $__loginArgs = null;
	public function setLoginObject($object, $method, $argNames){
		$this->__loginObject = $object;
		$this->__loginMethod = $method;
		$this->__loginArgs = $argNames;
	}
	
	public function gotoLogin(){
		if($this->_secConfig != null){
			header('location: '.Reever_Routes::GetRoute($this->_secConfig->getLoginRouteId()));
		}else{
			header('location: '.Reever_Routes::GetRoute("Login"));
		}
		exit();
	} 
	
	public function isLoginPage(){
		return ($_GET['page'] == 'login.php');
	}
	
	public function getLoggedUser(){
		return $this->loggedUser;
	}
	
	public function isLogged(){
		return $this->_isLogged;
	}
	
	
	public function doLogin($user, $pass, $param = null){
		$ret =  $this->_doLogin($user, $pass, $param);
		if($ret != false){
			$_SESSION['security']['data'] = serialize($ret);
			$_SESSION['security']['isLogged'] = true;
			$this->loadSecuritySession();
			return true;
		}
		return false;
	}
	
	public function doLogout(){
		unset($_SESSION['security']);
	}
	
	public function setSecConfig($secConfig){
		$this->_secConfig = $secConfig;
	}
	
}