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
 * @since 10/2011
 * @version 0.0.1-alpha
 * 
 */

require_once('reever/base.php');

class Reever_Params extends Reever_Base{
	
	/* ATRIBUTOS */
	private $__baseUrl = ""; 
	private $__urlParams = array();
	
	/* METHODOS */
	
	/* PUBLIC */
	public function __construct($baseUrl = ''){
		$this->__baseUrl = $baseUrl;
		$this->__parseUrlParams();
	}
	
	public function getUrlParam($idx){
		if(isset($this->__urlParams[$idx])){
			return $this->__urlParams[$idx];
		}else{
			return false;
		}
	}
	
	public function getUrlParams(){
		return $this->__urlParams;
	}
	
	public function cleanParam($item, $k){
		$this->__urlParams[$k] = strip_tags($item);
	}

	
	/* PRIVATE */
	private function __parseUrlParams(){
		$this->__urlParams = explode('/', str_ireplace($this->__baseUrl, '', $_SERVER['REQUEST_URI']));
		array_walk($this->__urlParams, array($this, 'cleanParam'));
	}
	
}
