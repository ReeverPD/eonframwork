<?php
/**
 * Copyright (c) 2013
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
 * @since 01/2013
 * @version 0.1.0-alpha
 */

require_once('reever/Events/EventNotFound.exception.php');
require_once('reever/Events/Event.class.php');


/**
 * Classe Base do controlador de Eventos
 * 
 * 
 * @package Reever
 * @subpackage Eventos
 * @since 22/01/2013
 * @copyright Reever Pesquisa e Desenvolvimento (Reever P&D)
 * @filesource BoletoManager.php
 * @license BSD
 * @version  0.1.0-alpha
 * @author  Iuri Andreazza {iuri@reeverpd.com.br}
 */

class ReeverEventDispatcher {
	
	private $events = array();
	private $eventsListeners = array();
	
	private $canceledBubble = false;
	
	public function __construct(){
		
	}
	
	protected final function resumeBubble(){
		$this->canceledBubble = false;
	}
	
	protected final function cancelBubble(){
		$this->canceledBubble = true;
	}
	
	protected final function addEvent($eventType){
		$this->events[] = $eventType;
	}
	
	public final function addEventListener($eventType, $function){
		$this->eventsListeners[$eventType][$function] = $function;
	}
	
	protected final function callEvent($eventType, $args = array()){
		if(in_array($eventType, $this->events)){
			if(isset($this->eventsListeners[$eventType])) {
				foreach($this->eventsListeners[$eventType] as $event){
					if(!is_callable($event)){
						throw new EventNoFoundException($eventType);
					}
				}
			}
			foreach($this->eventsListeners[$eventType] as $event){
				if($this->hasMethod($event)){
					if(!$this->canceledBubble){
						$refClass = new ReflectionClass($this);
						$refMethod = $refClass->getMethod($method);
						$refMethod->invokeArgs($this, $args);
						//$this->$event();
					}
				}else{
					$refFunc = new ReflectionFunction($event);
					return $refFunc->invokeArgs($args);
					//return $event();
				}
			}
		}
	}
	
	private function hasMethod($method){
		try{
			$refClass = new ReflectionClass($this);
			$refMethod = $refClass->getMethod($method);
			if($refMethod == null){
				return false;
			}else{
				return true;
			}
		}catch(ReflectionException $e){
			return false;
		}
	}
	
	protected final function removeEvent($eventType, $function){
		unset($this->eventsListeners[$eventType][$function]);
	}
}
