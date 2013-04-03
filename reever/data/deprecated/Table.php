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

//require_once('reever/base.php');
//Essa classe ir� herdar do zend_db_table_abstract
if(!class_exists('Zend_Db_Table_Abstract')){
	require_once('Zend/Db/Table.php');
}

/**
 * Classe base do Model, representando uma entidade de dados dentro do sistema
 * 
 * @package Reever
 * @subpackage Data
 * @since 04/11/2011 - 17:56:58
 * @copyright Reever P&D - Reever Pesquisa e Desenvolvimento
 * @filesource Table.php
 * @license BSD
 * @version  0.0.1-alpha
 * @author  Iuri Andreazza {iuri.andreazza@gmail; iuri@reeverpd.com.br}
 */
abstract class Reever_Base_Table extends Zend_Db_Table_Abstract{
	
	/**
     * $_name - Nome da tabela
     *
     * @var string
     */
    protected $_name;

    /**
     * $_id - The primary key name(s)
     *
     * @var string|array
     */
    protected $_id;

    /**
     * Returns the primary key column name(s)
     *
     * @return string|array
     */
    public function getPrimaryKeyName() {
        return $this->_id;
    }

    /**
     * Returns the table name
     *
     * @return string
     */
    public function getTableName() {
        return $this->_name;
    }

    /**
     * Returns the number of rows in the table
     *
     * @return int
     */
    public function countAllRows() {
        $query = $this->select()->from($this->_name, 'count(*) AS all_count');
        $numRows = $this->fetchRow($query);

        return $numRows['all_count'];
    }

    /**
     * Returns the number of rows in the table with optional WHERE clause
     *
     * @param $where string Where clause to use with the query
     * @return int
     */
    public function countByQuery($where = '') {
        $query = $this->select()->from($this->_name, 'count(*) AS all_count');

        if (! empty($where)) {
            $query->where($where);
        }

        $row = $this->getAdapter()->query($query)->fetch();

        return $row['all_count'];
    }

    /**
     * Generates a query to fetch a list with the given parameters
     *
     * @param $where string Where clause to use with the query
     * @param $order string Order clause to use with the query
     * @param $count int Maximum number of results
     * @param $offset int Offset for the limited number of results
     * @return Zend_Db_Select
     */
    public function fetchList($where = null, $order = null, $count = null, $offset = null ) {
        $select = $this->select()
            				->order($order)
            				->limit($count, $offset);

        if (! empty($where)) {
            $select->where($where);
        }

        return $select;
    }
} 