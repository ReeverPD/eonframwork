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

if(class_exists('Zend_Paginator_Adapter_DbSelect')){
	require_once('Zend/Paginator/Adapter/_DbSelect.php');
}

/**
 * Classe de pagina��o padr�o dos Model
 * 
 * @package Reever
 * @subpackage Data
 * @since 04/11/2011 - 17:56:58
 * @copyright Reever P&D - Reever Pesquisa e Desenvolvimento
 * @filesource Paginator.php
 * @license BSD
 * @version  0.0.1-alpha
 * @author  Iuri Andreazza {iuri.andreazza@gmail; iuri@reeverpd.com.br}
 */
class Reever_Model_Paginator extends Zend_Paginator_Adapter_DbSelect
{
    /**
     * Object mapper
     *
     * @var Reever_Mapper
     */
    protected $_mapper = null;

    /**
     * Constructor.
     *
     * @param Zend_Db_Select $select The select query
     * @param Reever_Mapper $mapper The mapper associated with the object type
     */
    public function __construct(Zend_Db_Select $select, Reever_Mapper $mapper)
    {
        $this->_mapper = $mapper;
        parent::__construct($select);
    }

    /**
     * Returns an array of items as objects for a page.
     *
     * @param  integer $offset Page offset
     * @param  integer $itemCountPerPage Number of items per page
     * @return array An array of Default_ModelAbstract objects
     */
    public function getItems($offset, $itemCountPerPage)
    {
        $items = parent::getItems($offset, $itemCountPerPage);
        $objects = array();

        foreach ($items as $item) {
            $objects[] = $this->_mapper->loadModel($item, null);
        }

        return $objects;
    }
}