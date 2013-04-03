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

require_once('reever/security/SecurityConfig.php');

/**
 * Classe de gerenciamento de Log de atividades
 * 
 * 
 * @example Reever_Routes::GetRoute($idRoute, 
 * 									array("id_var"=>value, 
 * 											["id_var_2"=>value, ...])
 *								);
 *
 * @package Reever
 * @subpackage Routes
 * @since 13/01/2012
 * @copyright Reever Pesquisa e Desenvolvimento (Reever P&D)
 * @filesource Routes.php
 * @license BSD
 * @version  0.1.0-alpha
 * @author  Iuri Andreazza {iuri@reeverpd.com.br}
 */
class Reever_Routes {

	const NORMAL = 1;
	const MVC = 2;
	
	private static $_instance = null;
	
	protected $_rules = array();
	protected $_routes = array();
	protected $_ignoredRoutes = array();
	
	protected $useHTAccess = false;
	
	protected $_searchFolders = array();
	
	private function __construct(){}
	
	
	/**
	 * Adiciona uma rota de procura para os arquivos de Views e controllers
	 * @param unknown_type $folderPath
	 */
	public function AddSearchFolder($folderPath){
		$this->_searchFolders[] = $folderPath;
	}

	public function IsSearchFolder($folder){
		foreach($this->_searchFolders as $val){
			if(strpos($val, $folder)){
				return true;		
			}
		}
		return false;
	}
	
	/**
	 * @return Reever_Routes
	 */
	public static function getInstance(){
		if(self::$_instance  == null){
			self::$_instance = new Reever_Routes();
		}
		return self::$_instance;
	}

	/**
	 * Indica se o roteamento ira usar o HTAccess ou irá funcionar de forma absoluta
	 * @param boolean $val
	 */
	public function UseHTAccess($val){
		$this->useHTAccess = $val;
	}
	
	/**
	 * Adiciona Regras para substituir a expressao indicada na rota
	 * 
	 * @param $rule
	 * @param $posixExpr
	 */
	public function addRule($rule, $posixExpr){
		$this->_rules[$rule] = $posixExpr;
	}
	
	/**
	 * Add a Route to a especified page
	 * 
	 * @todo Suportar o MVC 
	 * 
	 * @param string $id_route
	 * @param string $route_expr
	 * @param string $view_file
	 * @param object [$params]
	 * @return void
	 */
	public function addRoute($id_route, $route_expr, $view_file, $params = null, $secConfig = null, $useMVC = false){
		$r = new stdClass();
		$r->id_route = $id_route;
		$r->expr = $route_expr;
		$r->file = $view_file;
		$r->params = $params;
		if($useMVC){
			$r->type = Reever_Routes::MVC;
		}else{
			$r->type = Reever_Routes::NORMAL;
		}
		
		if($secConfig != null){
			$r->sec  = $secConfig;
		}else{
			$r->sec = new SecurityConfig();
			$r->sec->RequireAuth(false);
		}
		
		$this->_routes[$id_route] = $r;
		$this->_routes[$id_route]->General = false;
	}
	
	public function addRouteGeneralRule($id_route, $route_expr, $view_file, $params = null, $secConfig = null, $useMVC = false){
		$this->addRoute($id_route, $route_expr, $view_file, $params, $secConfig, $useMVC);
		$this->_routes[$id_route]->General = true;
	}
	
	public function ignoreRoute($id_route, $route_expr){
		$this->_ignoredRoutes[$id_route] = $route_expr;
	}
	
	/**
	 * Retorna a URL
	 * 
	 * @param mixed $id_route
	 * @param array $params
	 * @throws Exception
	 */
	public static function GetRoute($id_route, $params = null){
		if(!in_array($id_route, array_keys(self::$_instance->_ignoredRoutes))){

			if(self::$_instance->useHTAccess){
				if(!is_file('.htaccess')){
					self::$_instance->generateHTAccess();
				}
			}
			if(!isset(self::$_instance->_routes[$id_route])){
				throw new Exception("Rota Inválida", 301);
			}
			$r = self::$_instance->_routes[$id_route];
			$expr = $r->expr;
			if(strpos($expr, "~/") !== false){
				$expr = str_ireplace("~/", ROOT_SERVER_FMK.ROOT_SITE_FMK.'/', $expr);
			}
			if($r->type == Reever_Routes::NORMAL){
				if(self::$_instance->useHTAccess){
					preg_match_all("({[a-zA-Z]+}+)", $expr, $matches);
					foreach($matches[0] as $match){
						$fid = substr($match, 1, strlen($match)-2);
						$expr = str_replace($match, $params[$fid], $expr);
						$i++;
					}
				}else{
					$file_expr = $route->file."?";
					$fexprs = array();
					
					preg_match_all("({[a-zA-Z]+}+)", $expr, $matches);
					$i = 1;
					foreach($matches[0] as $match){
						$fid = substr($match, 1, strlen($match)-2);
						$expr = str_replace($match, self::$_instance->_rules[$match], $expr);
						$fexprs[] .= $fid."=".$params[$fid];
						$i++;
					}
					
					$expr = $file_expr.implode("&", $fexprs);
				}
			
			}else if($r->type == Reever_Routes::MVC){
				//throw new Exception("Rotas para MVC não suportadas", 500);
				if(self::$_instance->useHTAccess){
					preg_match_all("({[a-zA-Z]+}+)", $expr, $matches);
					foreach($matches[0] as $match){
						$fid = substr($match, 1, strlen($match)-2);
						$expr = str_replace($match, $params[$fid], $expr);
						$i++;
					}
				}else{
					throw new Exception("Rotas para MVC não suportadas sem o uso do HTACCESS", 500);
				}
			}
			return $expr;
		} 
	}
	
	/**
	 * Método interno para gerar o HTAccess automatico.
	 */
	private function generateHTAccess(){
		$strFile = "<IfModule  mod_rewrite.c>".PHP_EOL;
		$strFile .= "RewriteEngine on".PHP_EOL;
		foreach($this->_routes as $id_route => $route){
			$expr = $route->expr;
			$expr = str_replace("~/", "^", $expr);
			
			$file_expr = "index.php?&id_r=".$id_route."&page=".$route->file."&";
			$fexprs = array();
			
			preg_match_all("({[a-zA-Z]+}+)", $expr, $matches);
			$i = 1;
			foreach($matches[0] as $match){
				$expr = str_replace($match, $this->_rules[$match], $expr);
				$fexprs[] .= substr($match, 1, strlen($match)-2)."=$".$i;
				$i++;
			}
			
			$file_expr .= implode("&", $fexprs);
			$rule = "RewriteRule $expr$ $file_expr&%{QUERY_STRING} [NC]".PHP_EOL;
			$strFile .= $rule;
		}
		$strFile .= "</IfModule>";
		
		file_put_contents(".htaccess", $strFile);
	}
	
	public function GetRouteById($id_route){
		if(isset(self::$_instance->_routes[$id_route])){
			return self::$_instance->_routes[$id_route];
		}else{
			throw new Exception("Rota não encontrada", 500);
		}
	}
	
	public function GetRouteByPageId($page){
		foreach(self::$_instance->_routes as $route){
			if($route->file == $page){
				return $route;
			}else{
				continue;
			}
		}
	}

}