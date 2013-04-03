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
 * O Manager dos logs é capaz de fazer log para mais de uma saida simultaneamente.
 * 
 * ActivityLogManager::init([chave do mecanismo de log], [tipo do log], [parametros do mecanismo de log]);
 * 
 * require_once 'reever/log/ActivityLogManager.php';
 * 
 * - Usando o Log para escrever um TXT
 * 	
 * 	ActivityLogManager::init(); //Chamada padrão (log padrao para aquivo de texto (activity.log)
 * 
 *  ActivityLogManager::init('chave', ActivityLogManager::LOG_TO_FILE, array('file_name'=>'nome_do_arquivo.ext'));
 *  
 *  ActivityLogManager::clearLogStream(); /Limpa o log
 *  
 *  ActivityLogManager::log("<MENSAGEM>", <CODIGO>, 'chave'); //ou
 *  
 *  ActivityLogManager::log("<MENSAGEM>", <CODIGO>); //deixa a chave padrao do manager (chave = default)
 *  
 *  
 * - Usando o Log para escrever em uma base de dados
 * 
 *  
 *  $configLog = array( 'server'		=>'localhost',
 *  
 *					'table_prefix' 	=> 'prefixo_tabela_',
 * 
 * 					'db'			=> 'base_dados',
 * 
 * 					'user'			=> 'USER',
 * 
 * 					'pass'			=> 'PASSWORD'
 * 
 *				   );
 *
 *  
 *  ActivityLogManager::init('default', ActivityLogManager::LOG_TO_DATABASE, $configLog);
 *  
 *  ActivityLogManager::clearLogStream();
 *  
 *  ActivityLogManager::log("atividade teste!", 12541);
 *  
 *  
 *  - Buscando o stream do log
 *  
 *  $ret = ActivityLogManager::getLogStream();
 *  
 *  print_r($ret);
 * 
 * 
 * @package Reever
 * @subpackage Log
 * @since 28/10/2011 - 10:56:20
 * @copyright Reever P&D - Reever Pesquisa e Desenvolvimento
 * @filesource ActivityLogManager.php
 * @license BSD
 * @version  0.0.1-alpha
 * @author  Iuri Andreazza {iuri@reeverpd.com.br, iuri.andreazza@gmail.com}
 */
class ActivityLogManager {
 
	CONST LOG_TO_DATABASE 	= 1;
	CONST LOG_TO_PLAIN_FILE = 2;
	CONST LOG_TO_XML_FILE 	= 3;
	CONST LOG_TO_WEBSERVICE = 4;
	
	/**
	 * @var ActivityLogManager
	 */
	private static $_instance = array();
	
	/**
	 * Ponteiro para o Objeto instanciado no Init que escreve o log de atividade
	 * 
	 * @var LogTo
	 */
	private $_lPtr = null;

	/**
	 * Construtor
	 * @param LogTo $lPtr
	 */
	private function __construct($lPtr){
		$this->_lPtr = $lPtr;
	}
	
	/**
	 * Loga para a midia selecionada no init do Manager
	 * Caso n�o consiga escrever o log ir� retornar uma exception
	 * @throws FDDLogException
	 * @param string $message
	 * @param int $id_user
	 */
	public function logTo($message, $id_user){
		return $this->_lPtr->log($message, $id_user);
	}
	
	public function flush(){
		return $this->_lPtr->flush();
	}
	
	public function clear(){
		return $this->_lPtr->clear();
	}
	
	public function getStream(){
		return $this->_lPtr->getLogStream();
	}
	
	/* METODOS ESTATICOS */
	
	public static function init($logId = 'default', $logType = 2, $params = array()){
		switch ($logType) {
			case self::LOG_TO_DATABASE:
				require_once 'reever/log/LogToDatabase.php';
				$lPtr = new LogToDatabase($params);
				break;
			case self::LOG_TO_PLAIN_FILE:
				require_once 'reever/log/LogToPlainFile.php';
				$lPtr = new LogToPlainFile($params);
				break;
			case self::LOG_TO_XML_FILE:
				throw new Exception("Implementar Log de Atividade para XML", -1);
				break;
			case self::LOG_TO_WEBSERVICE:
				throw new Exception("Implementar Log de Atividade para Webservices", -1);
				break;
			default:
				;
			break;
		}
		
		if(!array_key_exists($logId, self::$_instance)){
			self::$_instance[$logId] = null;
		}
		
		if(self::$_instance[$logId] == null){
			self::$_instance[$logId] = new ActivityLogManager($lPtr);
		}
	}
	
	/**
	 * M�todo que registra a ativdade de um usuario especifico (ir� gravar uma
	 * mensagem juntamente com o seu ID).
	 * @param string $message
	 * @param int $id_user
	 */
	public static function log($message, $id_user, $logId = 'default') {
	 	$ret = self::$_instance[$logId]->logTo($message, $id_user);
	 	self::$_instance[$logId]->flush();
	 	return $ret;
	}

	
	/**
	 * Exporta o Log que o gerenciador maneja para um formato de intercambio.
	 * 
	 * @param const $to (LOG_TO_DATABASE, LOG_TO_PLAIN_FILE, LOG_TO_XML_FILE, LOG_TO_WEBSERVICE)
	 * @param array $params
	 */
	public static function exportLog($to, $params = array()){
		
	}
	
	/**
	 * Retorna o fluxo de log das atividades do Log
	 * @param string [$logId = default]
	 */
	public static function getLogStream($logId = 'default'){
		return self::$_instance[$logId]->getStream();
	}
	
	/**
	 * Limpa o Log
	 * @param string [$logId = default]
	 */
	public static function clearLogStream($logId = 'default'){
		self::$_instance[$logId]->clear();
	}
	
}
 