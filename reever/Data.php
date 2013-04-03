<?php
/**
 * 
 * Copyright (c) 2009 Iuri Andreazza. (http://www.camaleaovesgo.com.br)
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 * 
 * @category   Eon
 * @package    Eon_Data
 * @copyright  Copyright (c) 2009 Iuri Andreazza. (http://www.camaleaovesgo.com.br)
 * @license    http://eonframework.camaleaovesgo.com.br/license/mit     MIT License
 * @version 0.0.2-alpha
 */


class EONDataPortal{
	
	static private $_conns = array();
	
	public static function getSQLDataConnection($id, $connCfgId = ''){
		$connId = $id;
		require_once('reever/data/DatabaseGateway.class.php');
		if(!isset(self::$_conns[$connId])){
			$aux = new DatabaseGateway($connId);
			if($connCfgId != ''){
				$aux->connect($connCfgId);
			}
			self::$_conns[$connId] = $aux;
		}else{
			if(!is_object(self::$_conns[$connId])){
				$aux = new DatabaseGateway($connId);
				if($connCfgId != ''){
					$aux->connect($connCfgId);
				}
				self::$_conns[$connId] = $aux;
			}
		}
		return self::$_conns[$connId];
	}
	
	public static function getXMLDataConnection($connId){
		//@TODO: Implementar Classe de manipula��o de XML
	}
	
}