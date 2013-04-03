<?php
/**
 * Copyright (c) 2011, Reever Pesquisa e Desenvolvimento (Reever P&D)
 * All rights reserved.

 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. All advertising materials mentioning features or use of this software
 *    must display the following acknowledgement:
 *    This product includes software developed by the <organization>.
 * 4. Neither the name of the <organization> nor the
 *    names of its contributors may be used to endorse or promote products
 *    derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY <COPYRIGHT HOLDER> ''AS IS'' AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */


/**
 * Classe de configuracao do modelod e seguranca das paginas
 * 
 * @package Reever
 * @subpackage Security
 * @since 04/11/2011 - 17:56:58
 * @copyright Reever P&D - Reever Pesquisa e Desenvolvimento
 * @filesource Base.php
 * @license BSD
 * @version  0.0.1-alpha
 * @author  Iuri Andreazza {iuri.andreazza@gmail; iuri@reeverpd.com.br}
 */
 class SecurityConfig{

 	/**
 	 * @var boolean
 	 */
 	private $__requireAuth;
 	
 	
 	/**
 	 * Indica qual Rota deve usar para direcionar ou validar o login
 	 * @var string
 	 */
 	private $__loginPage = "";
 	
 	/**
 	 * Indicativo de niveis de acesso, se vazio sem restrição
 	 * 
 	 * @var int[]
 	 */
 	protected $_authLvls = array();
 	
 	/**
 	 * Seta o route Id to Login
 	 * 
 	 * @param string $routeIdLogin
 	 */
 	public function setLoginRoute($routeIdLogin){
 		$this->__loginPage = $routeIdLogin;
 	}
 	
 	/**
 	 * 
 	 * 
 	 * @return string
 	 */
 	public function getLoginRouteId(){
 		return $this->__loginPage;
 	}
 	
 	/**
 	 * Seta o niveis de autorização para acessar a pagina
 	 * 
 	 * @param int[] $lvls
 	 */
 	public function setAuthLvls($lvls){
 		$this->_authLvls = $lvls;
 	}
 	
 	/**
 	 * 
 	 * @param int $lvl
 	 */
 	public function hasRequiredLvl($lvl){
 		return in_array($lvl, $this->_authLvls);
 	}
 	
 	public function RequireAuth($v = true){
 		$this->__requireAuth = $v;
 	}
 	
 	public function GetRequireAuth(){
 		return $this->__requireAuth;
 	}
 	
 }
 
 
 