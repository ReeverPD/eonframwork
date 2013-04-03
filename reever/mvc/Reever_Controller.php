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

require_once('reever/mvc/ViewParser.php');

/**
 * Classe de gerenciamento de Log de atividades
 * 
 * 
 * @package Reever
 * @subpackage MVC
 * @since 13/01/2012
 * @copyright Reever Pesquisa e Desenvolvimento (Reever P&D)
 * @filesource Reever_Controller.php
 * @license BSD
 * @version  0.1.0-alpha
 * @author  Iuri Andreazza {iuri@reeverpd.com.br}
 */
 class Reever_Controller{

 	private $vParser = null;
 	
 	//Atributos de execucao basica
 	protected $_viewName 	= "";
 	protected $_viewLocation = "";
 	
 	/**
	 * @var Reever_SecurityContext
	 */
 	protected $_secContext = null;
 	
 	//Atributos
 	
 	/**
 	 * @var ModelState
 	 */
 	protected $ModelState 	= null;
 	protected $Model 		= null;
 	
 	
 	//Dados Extras
 	protected $ViewBag 	= null;
 	protected $ViewData = null;
 	
 	public function __construct(){
 		$this->ViewBag = new stdClass();
 		$this->ViewData = new stdClass();
 	}
 	
 	public function RenderView($content){
 		echo $content;
 	}
 	
 	public function setSecContext($ctx){
 		$this->_secContext = $ctx;
 	}
 	
 	public function setModelState($state){
 		$this->ModelState = $state;
 	}
 	
 	public function setModel(&$model){
 		$this->Model = $model;
 	}
 	
 	/**
 	 * @param string $val
 	 */
 	public function setViewName($val){
 		$this->_viewName = $val;
 	}
 	
 	
 	public function setViewLocation($val){
 		$this->_viewLocation = $val;
 		//if(!is_file($this->_viewLocation.'/'.$this->_viewName.'.php')){
 		//	throw new Exception("View {$this->_viewName} não encontrado em ".$this->_viewLocation, 500);
 		//}
 		$this->vParser = new ViewParser();
 		$this->vParser->ParseViewModel(file_get_contents($this->_viewLocation.'/'.$this->_viewName.'.php', "r"), $this);
 	}
 	
 	public function loadMasterPage($masterFile){
 		include_once($masterFile);
 	}
 	
 	/**
 	 * @param string $viewName
 	 */
 	public function View($viewName = null){
 		if($viewName != null){
 			$_view = $viewName;
	 	}else{
	 		$_view = $this->_viewName;
	 	}
 		
 		if(is_file($this->_viewLocation.'/'.$_view.'.php')){
			ob_start();
				include($this->_viewLocation.'/'.$_view.'.php');
			$viewContent = ob_get_clean();
			return $this->vParser->ParseView($viewContent, $this);
		}else{
			throw new Exception("View {$_view} não encontrado em ".$this->_viewLocation, 500);	
		}
 	}
 	
 	/**
 	 * @param mixed $data
 	 */
 	public function JsonResult($data){
 		return json_encode($data);
 	}

 	/**
 	 * @param string $id_route
 	 */
 	public function RedirectToRoute($id_route){
 		header('location: '.Reever_Routes::GetRoute($id_route));
 		exit();
 	}
 	
 	/**
 	 * @param string $actionName
 	 */
 	public function RedirectToAction($actionName, $controller = null){
 		if($controller != null){
 			throw new Exception("Redirecionamento para Controller não implementado", 500);
 		}else{
 			$this->_viewName = $actionName;
 			$rClass = new ReflectionObject($this);
 			$method = $rClass->getMethod($actionName);
 			return $method->invoke($this);
 		}
 	}
 	
 }