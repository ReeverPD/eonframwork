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

require_once 'reever/log/LogTo.php';

/**
 * Classe de gerenciamento de Log de atividades
 * 
 * @package Reever
 * @subpackage Log
 * @since 28/10/2011 - 17:23:58
 * @copyright Reever P&D - Reever Pesquisa e Desenvolvimento
 * @filesource LotToDatabase.php
 * @license BSD
 * @version  0.0.1-alpha
 * @author  Iuri Andreazza {iuri@reeverpd.com.br}
 */
class LogToDatabase extends LogTo{
	
	protected $_dbLink = null;
	
	public function __construct($params){
		parent::__construct($params);
		$this->_dbLink = mysql_connect($params['server'], $params['user'], $params['pass']);
		if(($err = mysql_error($this->_dbLink)) != ''){
			throw new Exception($err, 500);
		}
		
		mysql_select_db($params['db'], $this->_dbLink);
		if(($err = mysql_error($this->_dbLink)) != ''){
			throw new Exception($err, 500);
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS ".$this->_params['table_prefix']."log_atividade
				(
				  	id_log_atividade INT NOT NULL AUTO_INCREMENT ,
				  	UID INT NULL ,
				  	MESSAGE VARCHAR(255) NULL ,
				  	PRIMARY KEY (id_log_atividade) 
				)
				ENGINE = MyISAM;";
		mysql_query($sql, $this->_dbLink);
		if(($err = mysql_error($this->_dbLink)) != ''){
			throw new Exception($err, 500);
		}
	}
	
	/* (non-PHPdoc)
	 * @see LogTo::clear()
	 */
	public function clear() {
		$sql = "TRUNCATE TABLE ".$this->_params['table_prefix']."log_atividade";
		mysql_query($sql, $this->_dbLink);
		if(($err = mysql_error($this->_dbLink)) != ''){
			throw new Exception($err, 500);
		}
	}

	/* (non-PHPdoc)
	 * @see LogTo::flush()
	 */
	public function flush() {
		//... nesse caso n�o tem problema
	}

	/* (non-PHPdoc)
	 * @see LogTo::getLogStream()
	 */
	public function getLogStream() {
		$sql = "SELECT 
					UID, 
					MESSAGE 
				FROM 
					".$this->_params['table_prefix']."log_atividade
				ORDER BY
					id_log_atividade DESC";
		$res = mysql_query($sql, $this->_dbLink);
		if(($err = mysql_error($this->_dbLink)) != ''){
			throw new Exception($err, 500);
		}
		
		$stream = array();
		while( ($line = mysql_fetch_assoc($res)) !== false){
			$stream[] = $line;
		}
		return $stream;
	}

	/* (non-PHPdoc)
	 * @see LogTo::log()
	 */
	public function log($str, $id_user) {
		$sql = "INSERT INTO ".$this->_params['table_prefix']."log_atividade(UID, MESSAGE) VALUES(".$id_user.", '".$str."')";
		mysql_query($sql, $this->_dbLink);
		if(($err = mysql_error($this->_dbLink)) != ''){
			return $err;
		}
		return true;
	}


	
	
}