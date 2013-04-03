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
 * Classe de gerenciamento de Log de atividades
 * 
 * 
 * @package Reever
 * @subpackage MVC
 * @since 13/01/2012
 * @copyright Reever Pesquisa e Desenvolvimento (Reever P&D)
 * @filesource ViewParser.php
 * @license BSD
 * @version  0.1.0-alpha
 * @author  Iuri Andreazza {iuri@reeverpd.com.br}
 */
 class ViewParser{
 	
 	/**
 	 * Interpreta e instancia o model da view usada...
 	 * @param string $viewContent
 	 * @param Reever_Controller $controller
 	 * @throws Exception
 	 * @return string
 	 */
 	public function ParseViewModel($viewContent, &$controller){
 		$viewContent = str_replace("~/", ROOT_SERVER_FMK.ROOT_SITE_FMK.'/', $viewContent);
 		$rootParams = array();
 		if(preg_match_all("/[a-zA-Z]*=\"\^\/[a-zA-Z0-9\/]*\"/", $viewContent, $matchRootTag) > 0){
 			$viewContent = substr($viewContent, strpos($viewContent, "\n")+1);
 			//Parse <reever.page> tag
 			foreach($matchRootTag[0] as $val){
 				$basePath = $val;
 				$basePath = explode("=",str_replace('"', "" , $basePath));
 				$basePath[1] = str_replace("^/", ROOT.'/', $basePath[1]);
 				$rootParams[$basePath[0]] = $basePath[1]; 	
 			}
 			//Model Directive
 			if(in_array("Model", array_keys($rootParams))){
				if(strpos(strtolower($rootParams['Model']), 'dynamic') === false){
					//Model is Defined
					$modelClassName = array_pop(explode("/", $rootParams['Model']))."Model";
					if(!is_file($rootParams['Model'].'.php')){
						throw new Exception("Model {$modelClassName} Class not found in: {$rootParams['Model']} ", 500);
					}
					include_once $rootParams['Model'].'.php';
					$model = new $modelClassName;
					foreach($_REQUEST as $key => $val){
						$model->$key = $val;
					}
					$model->validate();
					$controller->setModel($model);
				}else{
					//Model is Dynamic
					$model = new stdClass();
					foreach($_REQUEST as $key => $val){
						$model->$key = $val;
					}
					$controller->setModel($model);
				}
 			}
 		}
 		$viewContent = str_replace("^/", ROOT.'/', $viewContent);
 		return $viewContent;
 	}

 	/**
 	 * Interpreta e Executa a View
 	 * @param string $viewContent
 	 * @param Reever_Controller $controller
 	 * @return string 
 	 */
 	public function ParseView($viewContent, &$controller){
 		$viewContent = str_replace("~/", ROOT_SERVER_FMK.ROOT_SITE_FMK.'/', $viewContent);
 		$rootParams = array();
 		if(preg_match_all("/[a-zA-Z]*=\"\^\/[a-zA-Z0-9\/]*\"/", $viewContent, $matchRootTag) > 0){
 			$viewContent = substr($viewContent, strpos($viewContent, "\n")+1);
 			//Parse <reever.page> tag
 			foreach($matchRootTag[0] as $val){
 				$basePath = $val;
 				$basePath = explode("=",str_replace('"', "" , $basePath));
 				$basePath[1] = str_replace("^/", ROOT.'/', $basePath[1]);
 				$rootParams[$basePath[0]] = $basePath[1]; 	
 			}
 			
 			//Master Page Directive
 			if(in_array("BasePage", array_keys($rootParams))){
	 			ob_start();
	 			$controller->loadMasterPage($rootParams['BasePage'].'.php');
	 			//include_once($rootParams['BasePage'].'.php');
	 			$masterContent = ob_get_clean();
	 			
	 			$masterContent = str_replace("~/", ROOT_SERVER_FMK.ROOT_SITE_FMK.'/', $masterContent);
		 		
		 		if(preg_match_all("/<reever\.content[^>]*name=\"[a-z0-9]*\"[ ]*>(.*?)<\\/reever\.content>/si", $viewContent, $matches) > 0){
		 			foreach($matches[0] as $k => $val){
		 				preg_match_all('/name="([^"]*)"/si', $val, $matchesAttr);
		 				$value = $matchesAttr[1][0];
		 				$masterContent = preg_replace("/<reever\.content[^>]*name=\"".$value."\"[ ]*>(.*?)<\\/reever\.content>/si", $matches[1][$k], $masterContent);
		 			}
		 		}
		 		$masterContent = str_replace("^/", ROOT.'/', $masterContent);
		 		return $masterContent;
 			}
 		}
 		$viewContent = str_replace("^/", ROOT.'/', $viewContent);
 		return $viewContent;
 	}
 	
 }