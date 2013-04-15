<?php
/**
 * Copyright (c) 2011
 *      Reever Pesquisa e Desenvolvimento (Reever P&D).  All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. All advertising materials mentioning features or use of this software
 *    must display the following acknowledgement:
 *      This product includes software developed by the University of
 *      California, Berkeley and its contributors.
 * 4. Neither the name of the University nor the names of its contributors
 *    may be used to endorse or promote products derived from this software
 *    without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE REGENTS AND CONTRIBUTORS ``AS IS'' AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED.  IN NO EVENT SHALL THE REGENTS OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
 * OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 * SUCH DAMAGE.
 * 
 * @author Iuri Andreazza {iuri@reeverpd.com.br, iuri.andreazza@gmail.com}
 * @since 01/2012
 * @version 0.0.1-alpha
 */


/**
 * Classe de execução de páginas
 * 
 * 
 * @package Reever
 * @subpackage Engines
 * @since 16/01/2012
 * @copyright Reever Pesquisa e Desenvolvimento (Reever P&D)
 * @filesource AppEngine.php
 * @license BSD
 * @version  0.1.0-alpha
 * @author  Iuri Andreazza {iuri@reeverpd.com.br}
 */

class Reever_AppEngine{
	
	/**
	 * @var Reever_Params
	 */
	private $__params = null;
	
	/**
	 * 
	 * @var Reever_Routes
	 */
	private $__route = null;
	
	/**
	 * @var Reever_SecurityContext
	 */
	private $__secContext = null;
	
	protected $_AuthUser = null;
	
	protected $_response_time_start = 0;
	protected $_response_time_end = 0;
	
	public function __construct(&$params){
		$this->_response_time_start = microtime(true);
		$this->__params = $params;
	}
	
	public function setRoute($route){
		$this->__route = $route;
	}
	
	/**
	 * Seta o Contexto de Segurança
	 * @param Reever_SecurityContext $secContext
	 */
	public function setSecurityContext($secContext){
		$this->__secContext = $secContext;
		if($this->__secContext->isLogged()){
			$this->_AuthUser = $this->__secContext->getLoggedUser();
		}
	}
	
	public function run(){
		$route_id = $_GET['id_r'];
		try{
			$route = $this->__route->GetRouteById($route_id);
		}catch (Exception $e){
			//@TODO: Redirecionar para o 404 (Rota não encontrada) ...
		}
		$loadPage = true;
		if($route->sec->GetRequireAuth()){
			$loadPage = $this->__secContext->isLogged() || $route->sec->getLoginRouteId() == $route_id;
		}
		if($loadPage){
			if($route->type == Reever_Routes::MVC){
				$r = explode('/',$route->file);
				$folder = "";
				foreach($r as $val){
					if($this->__route->IsSearchFolder($val)){
						$folder .= DIRECTORY_SEPARATOR.$val;
						array_shift($r);
					}
				}
				if(!isset($_GET['view'])){
					$_GET['view'] = "Index";
				}
				if($route->General === true){
					$ctrl = $_GET['controller'];
					$controller = $ctrl."Controller";
					$view = $_GET['view'];
				}else{
					$ctrl = array_shift($r);
					$controller = $ctrl."Controller";
					$view = array_shift($r);
				}
				
				if($view == null){
					$view = "Index";
				}
				$bindModel = false;
				if(count($_POST) > 0){
					$metodo = 'HttpPost_'.$view;
					$bindModel = true;
				}else{
					$metodo = $view;
				}
				
				//Verificar nas pastas se existe um controller especificado
				$controllerExists = false;
				foreach($this->__route->GetSearchFolders() as $folderSearch){
					$folderSearch = str_replace("~", ROOT, $folderSearch);
					if(is_file($folderSearch.'/Controllers/'.$controller.'.php')){
						$controllerExists = true;
						$folder = str_replace(ROOT, "", $folderSearch);
						//$folder = $folderSearch;
						break;
					}
				}
				
				if($controllerExists){
					set_include_path(get_include_path() . PATH_SEPARATOR . ROOT.$folder.'/Models');
					require_once('reever/mvc/ReeverBaseModel.php');
					require_once('reever/mvc/Reever_Controller.php');
					require_once('reever/mvc/ModelState.php');
					include_once(ROOT.$folder.'/Controllers/'.$controller.'.php');
					
					$controllerInstance = new $controller;
					$controllerInstance->setSecContext($this->__secContext);
					$controllerInstance->setViewName($view);
					$refClass = new ReflectionClass($controllerInstance);
					if($refClass->hasMethod($metodo)){
						$args = array();
						foreach($_REQUEST as $key => $val){
							if(!in_array($key, array('id_r', 'page'))){
								$args[$key] = $val;
							}
						}
						$metodoRef = $refClass->getMethod($metodo);
						$model = "";
						$controllerInstance->setViewLocation(ROOT.$folder.'/Views/'.$ctrl);
						$state = new ModelState();
						$state->validateModel($model);
						$controllerInstance->setModelState($state);
						$args['model'] = $model;
						$retornoMetodo = $metodoRef->invokeArgs($controllerInstance, $args);
						$controllerInstance->RenderView($retornoMetodo);
						
					}else{
						$code = 500.1;
						throw new Exception("Método {$metodo} não encontrado em {$controller}", $code);
					}
				}else{
					throw new Exception("{$controller} não encontrado na base de busca: ".ROOT.$folder, 500);
				}
				
				
			}else{
				//@TODO: Fazer o funcionamento para rotas SEM MVC
			}
			
		}else{
			$this->__secContext->setSecConfig($route->sec);
			$this->__secContext->gotoLogin();
		}
		
		/*$page = $_GET['page'];
		if($page != null){
			if(!is_file('views/'.$page)){
				require_once("views/errors/404.php");
			}
		}else{
			$route = $this->__route->GetRouteByPageId($page);
			$loadPage = true;
			if($route->sec->GetRequireAuth()){
				$loadPage = $this->__secContext->isLogged() || $this->__secContext->isLoginPage();
			}
			if($loadPage){
				require_once('code/'.$page);
				ob_start();
					require_once('views/'.$page);
				$page = ob_get_clean();
				$page = str_replace("~/", ROOT_SERVER_FMK."/", $page);
				if(strpos($page, "{head}")!== false){
					ob_start();
						require_once('views/base/index.php');
					$pageBase = ob_get_clean();
					list($head, $body) = explode("{body}", $page);
					$head = str_replace("{head}", "", $head);
					$pageBase = str_replace("{innerHead}", $head, $pageBase);
					$pageBase = str_replace("{innerPage}", $body, $pageBase);
					$pageBase = str_replace("~/", ROOT_SERVER_FMK."/", $pageBase);
					
					echo $pageBase;						
				}else{
					echo $page;
				}
			}else{
				$this->__secContext->setSecConfig($route->sec);
				$this->__secContext->gotoLogin();
			}
		}*/
		
		/*if($_GET['page'] != null){
			if(!is_file('views/'.$_GET['page'])){
				require_once("views/erro404.php");
			}else{
				if($this->__secContext->isLogged() || $this->__secContext->isLoginPage()){
					require_once('code/'.$_GET['page']);
					ob_start();
						require_once('views/'.$_GET['page']);
					$page = ob_get_clean();
					$page = str_replace("~/", ROOT_SERVER_FMK."/", $page);
					if(strpos($page, "{head}")!== false){
						ob_start();
							require_once('views/base/index.php');
						$pageBase = ob_get_clean();
						list($head, $body) = explode("{body}", $page);
						$head = str_replace("{head}", "", $head);
						$pageBase = str_replace("{innerHead}", $head, $pageBase);
						$pageBase = str_replace("{innerPage}", $body, $pageBase);
						$pageBase = str_replace("~/", ROOT_SERVER_FMK."/", $pageBase);
						
						echo $pageBase;						
					}else{
						echo $page;
					}
					
				}else{
					$this->__secContext->gotoLogin();
				}
			}
		}else{
			if($this->__secContext->isLogged()){
				//header('location: '.Reever_Routes::GetRoute("Escritorio", array("escritorio"=>$this->escritorio)));
			}else{
				header('location: '.Reever_Routes::GetRoute("Login"));
			}
			exit();
		}*/
	}
	
}
