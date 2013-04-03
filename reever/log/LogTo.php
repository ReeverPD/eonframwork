<?php
/**
 * Copyright (c) 2011, Reever P&D - Reever Pesquisa e Desenvolvimento
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
 * Classe de gerenciamento de Log de atividades
 * 
 * @package Reever
 * @subpackage Log
 * @since 28/10/2011 - 14:26:21
 * @copyright Reever P&D - Reever Pesquisa e Desenvolvimento
 * @filesource LogTo.php
 * @license BSD
 * @version  0.1.0-alpha
 * @author  Iuri Andreazza {iuri@reeverpd.com.br}
 */

abstract class LogTo{

	/**
	 * Lista de parametros (Esses parametros são diferentes em relação as classes que escrevem o log em midias diferentes)
	 * @var array()
	 */
	protected $_params = array();
	
	/**
	 * Construtor da Classe que sabe como escrever o log
	 * @param array [$params]
	 */
	public function __construct($params = array()){
		$this->_params = $params;
	}
	
	/**
	 * força a escrita dos logs
	 */
	abstract public function flush();
	
	/**
	 * Método que gera a entrada no LogStream
	 * @param string $str
	 * @param int $id_user
	 */
	abstract public function log($str, $id_user);
	
	/**
	 * Limpa os logs de atividades
	 */
	abstract public function clear();
	
	/**
	 * Retorna a lista de atividades no stream
	 * @return mixed
	 */
	abstract public function getLogStream();
} 
 