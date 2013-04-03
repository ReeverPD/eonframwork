<?php
/**
 * Copyright (c) 2011
 *      Reever Pesquisa e Desenvolvimento (ReeverP&D).  All rights reserved.
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
 * @author Iuri Andreazza {iuri@reeverpd.com.br}
 * @since 01/2012
 * @version 0.0.1-alpha
 * 
 */
//Para mostrar os erros
error_reporting(E_ALL);
ini_set('display_errors', 'ON');

define('__ROOT__', dirname(__FILE__));
set_include_path(get_include_path() . PATH_SEPARATOR . '/Reever_Work/Dropbox/ambiente_desenv/source_code/Reever/EON/v3/');
set_include_path(get_include_path() . PATH_SEPARATOR . __ROOT__);
set_include_path(get_include_path() . PATH_SEPARATOR . __ROOT__.'/Models');
set_include_path(get_include_path() . PATH_SEPARATOR . __ROOT__.'/Controllers');
set_include_path(get_include_path() . PATH_SEPARATOR . __ROOT__.'/Views');
set_include_path(get_include_path() . PATH_SEPARATOR . __ROOT__.'/Library');

include_once('config.php');
include_once('Routes.php');
require_once('reever/String.php');
require_once('reever/Cache.php');
require_once('reever/Params.php');
require_once('reever/AppEngine.php');
//require_once('crowdsource/security/SecurityContext.php');

//Zend
if(!class_exists('Zend_Loader')){
	ini_set('include_path', ini_get('include_path').PATH_SEPARATOR.'Library');
	include_once('Zend/Loader.php');
}

if(!class_exists('Zend_Locale')){
	include_once('Zend/Locale.php');
}

if(!class_exists('Zend_Date')){
	include_once('Zend/Date.php');
}

if(!class_exists('Zend_Paginator')){
	include_once('Zend/Paginator.php');
}

if(!class_exists('Zend_Db')){
	require_once('Zend/Db.php');
}
if(!class_exists('Zend_Cache')){
	require_once('Zend/Cache.php');
}

if(!class_exists('Zend_Registry')){
	include_once('Zend/Registry.php');
}

if(!class_exists('Zend_Db_Table_Abstract')){
	require_once('Zend/Db/Table/Abstract.php');
}

if(!class_exists('Zend_Config_Ini')){
	require_once('Zend/Config/Ini.php');
	require_once('reever/config/ZendIniConfig.php');
}

$params = new Reever_Params('/blog_project/');
$engine = new Reever_AppEngine(&$params);
if(defined('use_route')){
	$classRoute = class_route;
	$engine->setRoute(@$$classRoute);//Tira o erro do uso da variavel...
}
//$engine->setSecurityContext(new Crowdsource_SecurityContext());
$engine->Run();