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
 * @since 03/2013
 * @version 0.0.1-alpha
 */

require_once 'reever/Events.php';
require_once 'reever/events/Event.class.php';

/**
 * Classe de gestão de leitura e emissão de boletos
 * 
 * 
 * @package Reever
 * @subpackage Bancario
 * @since 22/01/2013
 * @copyright Reever Pesquisa e Desenvolvimento (Reever P&D)
 * @filesource BoletoManager.php
 * @license BSD
 * @version  0.1.0-alpha
 * @author  Iuri Andreazza {iuri@reeverpd.com.br}
 */
class AttributeMetadataEvent extends Event {
	
	const ON_VALIDADE = 1;
	const ON_SET_VALUE = 2;
	
}

/**
 * Classe de Metadados do model e coluna na base
 * 
 * @package Reever
 * @subpackage MVC
 * @since 29/03/2013
 * @copyright Reever Pesquisa e Desenvolvimento (Reever P&D)
 * @filesource ReeverAttributeMetadata.php
 * @license BSD
 * @version  0.1.0-alpha
 * @author  Iuri Andreazza {iuri@reeverpd.com.br}
 */
class ReeverAttributeMetadata extends ReeverEventDispatcher{
	
	/**
	 * Nome da coluna
	 * @var string
	 */
	public $colName = "";
	
	/**
	 * Nome do atributo no Model
	 * @var string
	 */
	public $attrName = "";
	
	/**
	 * Tipo de dados do atributo
	 * @var string (int|string|date)
	 */
	public $dataType = "";
	
	
	/**
	 * Tipo de dados da coluna
	 * @var string (varchar|char|integer|set)
	 */
	public $colType = "";
	
	/**
	 * Indica se precisa de validação
	 * 
	 * @var boolean ( false )	 
	 */
	public $needValidate = false;
	
	/**
	 * Indica se o valor do campo está nulo
	 * @var boolean ( true )
	 */
	public $isNull = true;
	
	/**
	 * Indica se o campo e atributo são requeridos
	 * 
	 * @var boolean ( false )
	 */
	public $required = false;
	
	/**
	 * Se houver erro, qual codigo de erro ele deve conter
	 * @var float
	 */
	public $errCode = 500.1;
	
	/**
	 * Indica se o campo está valido
	 * @var boolean ( true )
	 */
	public $isValid = true;
	
	/**
	 * Mensagem de erro contida da validação.
	 * @var string
	 */
	public $errMsg = "";
	
	/**
	 * Indica se o campo é chave primaria
	 * @var boolean
	 */
	public $isPrimary = false;
	
	/**
	 * Indica se o campo é chave estrangeira
	 * @var boolean
	 */
	public $isFK = false;
	
	
	
	/**
	 * Construtor
	 */
	public function __construct(){
		$this->addEvent(AttributeMetadataEvent::ON_VALIDADE);
		$this->addEvent(AttributeMetadataEvent::ON_SET_VALUE);
	}
	
	/**
	 * Adiciona um evento de Validação do campo
	 * @param function $onValidade nome da funcao ou método 
	 */
	public function addValidation($onValidade){
		$this->addEventListener(AttributeMetadataEvent::ON_VALIDADE, $onValidade);
	}
	
	/**
	 * Valida o atributo e o seu valor 
	 * 
	 * @param mixed $value
	 */
	public function Validate($value){
		$this->isValid = true;
		$this->errMsg = "";
		if($this->required){
			if(is_null($value)){
				$this->errMsg = "Este campo não pode ser nulo ou em branco";
				$this->isValid = false;
				$this->isNull = true;
			}
		}
		if(is_null($value)){
			$this->isNull = true;
		}
		$this->callEvent(AttributeMetadataEvent::ON_VALIDADE, array(&$this, $value));
	}
	
}


