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

require_once('reever/mvc/ReeverAttributeMetadata.php');
require_once('reever/mvc/ViewParser.php');
require_once('reever/data/annotations/AnnotationParser.php');

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
class ReeverBaseModel{
	
	/*
	 * Atributos 
	 */
	
	/**
	 * @var ReeverAttributeMetadata
	 */
	protected $_atributosMetadata = array();
	
	//BINDERs
	
	/**
	 * Carrega de um dicionario [key]=val
	 * e coloca nos atributos do model
	 * 
	 * @param array $dict
	 */
	public function BindModelForm($dict){
		foreach($dict as $key => $val){
			if(trim($val)!=""){
				$this->$key = $val;
			}
		}
	}
	
	/**
	 * Carrega 
	 * 
	 */
	public function BindModelEntity($entity){
		
	}
	
	/**
	 * Carrega do Model na Entidade
	 */
	public function BindEntityModel(){
		
	}
	
	
	//ENTITY
	
	/**
	 * Retorna a entidade do model 
	 * 
	 * @return Zend_Db_Table_Abstract
	 */
	public function GetEntity(){
		return $this->_entity;
	}
	
	
	/**
	 * @var Zend_Db_Table_Abstract
	 */
	protected $_entity;
	
	public function SetEntity($et){
		$this->_entity = $et;
	}

	
	//MODEL VALIDATION
	
	
	/**
	 * Valida o modelo contra as especificacoes
	 * 
	 * @return boolean 
	 */
	public function validate(){
		$valid = true;
		$ref = new ReflectionObject($this);
		foreach($ref->getProperties() as $prop){
			if(isset($this->_atributosMetadata[$prop->getName()])){
				$propMetadata = $this->_atributosMetadata[$prop->getName()];
				if($propMetadata->needValidate){
					$propMetadata->Validate($prop->getValue($this));
					if($propMetadata->isValid){
						$valid = $valid && $propMetadata->isValid; 
					}
				}
			}
		}
		return $valid;		
	}
	
	//#########################
	/* Metodos padroes para uso e tratamento dos Models */
	//#########################
	
	public function getAttrMetadata($attr){
		return @$this->_atributosMetadata[$attr->attrName];
	}
	
	protected function addAtributosMetadata(ReeverAttributeMetadata $attr){
		$this->_atributosMetadata[$attr->attrName] = $attr;
	}
	
	/**
	 * Seta o valor de uma propriedade
	 * 	
	 * @param string $prop
	 * @param mixed $val
	 */
	public function __set($prop, $val){
		$propMetadata = $this->_atributosMetadata[$prop];
		$propMetadata->Validate($val);
		if($propMetadata->isValid){
			throw new Exception($propMetadata->errMsg, $propMetadata->errCode);	
		}
		$this->$prop = $val;
	}
	
	/**
	 * Retorna o valor de uma propriedade
	 * @param $prop
	 */
	public function __get($prop){
		return $this->$prop;
	}
	
	/**
	 * Retorna a string do Model
	 * 
	 */
	public function __toString(){
		$ref = new ReflectionObject($this);
		$r = "[".$ref->getName()."]\n";
		foreach($ref->getProperties() as $prop){
			$r .= "\t - ".$prop->getName()."=".$prop->getValue()."\n";
		}
		return $r;
	}
	
}