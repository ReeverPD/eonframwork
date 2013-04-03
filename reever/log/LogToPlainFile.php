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

require_once 'reever/log/LogTo.php';

/**
 * Classe de gerenciamento de Log de atividades
 * 
 * @package Reever
 * @subpackage Log
 * @since 28/10/2011 - 14:15:15
 * @copyright Reever P&D - Reever Pesquisa e Desenvolvimento
 * @filesource LogToPlainFile.php
 * @license BSD
 * @version  0.0.1-alpha
 * @author  Iuri Andreazza {iuri.andreazza@gmail.com, iuri@reeverpd.com.br}
 */
 class LogToPlainFile extends LogTo{
 	/**
 	 * Nome do arquivo de log
 	 * @var string
 	 */
 	protected $_fName = "activity.log";
 	
 	/**
 	 * Atributo que contem a ultima mensagem de log
 	 * @var string
 	 */
 	protected $log = "";
 	
 	
	/* (non-PHPdoc)
	 * @see LogTo::clear()
	 */
	public function clear() {
		$this->verifyConfigs();
		ftruncate(fopen($this->_fName, 'w'), 0);
	}

	/* (non-PHPdoc)
	 * @see LogTo::flush()
	 */
	public function flush() {
		$this->verifyConfigs();
		$ret = file_put_contents($this->_fName, $this->log, FILE_APPEND);
		if($ret === false){
			throw new Exception("Erro ao escrever o arquivo de log", 1525);
		}
		return true;
	}

	/* (non-PHPdoc)
	 * @see LogTo::getLogStream()
	 */
	public function getLogStream() {
		$this->verifyConfigs();
		return file($this->_fName);
	}

	/* (non-PHPdoc)
	 * @see LogTo::log()
	 */
	public function log($str, $id_user) {
		$this->log = $id_user."\t".$str.PHP_EOL;
	}

 	private function verifyConfigs(){
 		if(key_exists("file_name", $this->_params)){
 			$this->_fName = $this->_params['file_name'];
 		}
 	}
 }