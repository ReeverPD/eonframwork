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
 * 
 */

/**
 * Classe com funções especiais para strings
 * 
 * @package Reever
 * @subpackage Base
 * @since 04/11/2011 - 17:56:58
 * @copyright Reever Pesquisa e Desenvolvimento (Reever P&D)
 * @filesource Base.php
 * @license BSD
 * @version  0.0.1-alpha
 * @author  Iuri Andreazza {iuri.andreazza@gmail; iuri@reeverpd.com.br}
 */
class Reever_String
{
	
	static public function br2nl($text){
		return $text;
	}
	
	/**
	 * Corta o texto para mostrar na listagem e colocar os 3 pontos.
	 * 
	 * @param string $text
	 * @param int $length
	 * @return string
	 */
	static public function resize($text, $length) 
	{
		$new_text = '';
		foreach(explode(' ', $text) as $word)
		{
			$new_text .= $word.' ';
			
			if(strlen($new_text) >= $length)
			{
				return $new_text.'[...]';
			}
		}
		
		return $new_text;
	}
	
	/**
	 * Testa se uma url � v�lida.
	 * 
	 * @param string $url
	 * @return boolean
	 */
	static public function is_valid_url($url) 
	{
		return preg_match('|^http(s)?://[a-z0-9-]+(\.[a-z0-9-]+)*(:[0-9]+)?(.)$|i', $url);
	}
	
	/**
	 * Testa se uma url é um post válido.
	 * 
	 * @param string $url
	 * @return boolean
	 */
	static public function is_valid_url_post($url) 
	{
		return /*preg_match('|^http(s)?://[a-z0-9-]+(\.[a-z0-9-]+)*(:[0-9]+)?(/*.)$|i', $url)*/true;
	}
	
	/**
	 * Testa se um e-mail é válido.
	 * 
	 * @param string $email
	 * @return boolean
	 */
	static public function is_valid_email($email)
	{
		return preg_match('/[.+a-zA-Z0-9_-]+@[a-zA-Z0-9-]+.[a-zA-Z]+/', $email);
	}

	/**
	 * Fun��o que valida se um ip é válido.
	 * 
	 * @param string $ip_addr
	 * @return boolean
	 */
	static function is_valid_ip_address($ip_addr)
	{
		//first of all the format of the ip address is matched
		if(preg_match("/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/",$ip_addr))
		{
			//now all the intger values are separated
			$parts=explode(".",$ip_addr);
			//now we need to check each part can range from 0-255
			foreach($parts as $ip_parts)
			{
				if(intval($ip_parts)>255 || intval($ip_parts)<0)
				{
					return false; //if number is not within range of 0-255
				}
			}
			return true;
		}
		else
		{
			return false; //if format of ip address doesn't matches
		}
	}
	
	/**
	 * Retorna o nome de um m�s.
	 * 
	 * @param int $month
	 * @return String 
	 */
	static public function get_month($month)
	{
		switch ($month) 
		{
			case 1  : return "janeiro"; break;
			case 2  : return "fevereiro"; break;
			case 3  : return "março"; break;
			case 4  : return "abril"; break;
			case 5  : return "maio"; break;
			case 6  : return "junho"; break;
			case 7  : return "julho"; break;
			case 8  : return "agosto"; break;
			case 9  : return "setembro"; break;
			case 10 : return "outubro"; break;
			case 11 : return "novembro"; break;
			case 12 : return "dezembro"; break;
			default : return ''; break;
		}
	}
	
	/**
	 * Função que retorna uma string aleatória com 
	 * o tamanho passado por parametro. 
	 * 
	 * @param int $length
	 * @return String $rndstring
	 */
	static public function random_string($length) 
	{
		$template = "1234567890abcdefghijklmnopqrstuvwxyz";
	   	for ($a = 0; $a < $length; $a++) 
	   	{
	  		$b = rand(0, strlen($template) - 1);
	  		$rndstring .= $template[$b];
		}
		return $rndstring;
	} 
	
	/**
	 * Função que retorna a extensão de um arquivo.
	 * 
	 * @param array $file
	 * @return String
	 */
	static public function get_file_extension($file)
	{
		return end(explode('.', $file['name']));
	}
}