<?
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
 *  3. All advertising materials mentioning features or use of this software
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
 * @package Reever
 * @subpackage Tools
 * @example $> php SQL_2_ENTITIES.php file_sql prefix_class dir
 * @author Iuri Andreazza {iuri@reeverpd.com.br}
 * @since 03/2013
 * @version 0.0.1-alpha
 */

$argc = $_SERVER['argc'];
$argv = $_SERVER['argv'];

//Retirando o nome do script do argc e argv
$argc -= 1;
array_shift($argv);

system("clear");

if($argc <= 0){
	    echo Menu();
	    exit;
}

$file = $argv[0];
$prefix = $argv[1];
$dir = $argv[2];

echo "############################\n";
echo "## Lendo Arquivo $file \n";
echo "############################\n";

$fString = explode("\n",file_get_contents($file));

if($dir != null){
	mkdir($dir.'/tables', '0777', true);
}else{
	mkdir('Model/tables', '0777', true);
}


$templateDataTable 	= file_get_contents('Template_DataTable.tphp');
$templateModel 		= file_get_contents('Template_Model.tphp');


//RULES
$DIGIT		= '[0-9]';
$WORD		= '[a-zA-Z][a-zA-Z0-9_]*';
$N_REAL		= "$DIGIT+.$DIGIT+";
$N_INT		= "$DIGIT+";

$CL_TYPES	= '(VARCHAR|INTEGER|REAL|CHAR|BIT)';
$CL_ENGINES	= '(InnoDB|MyISAM)';

$KW_CREATE 	= '(CREATE|create)';
$KW_IF 		= '(IF|if)';
$KW_TABLE 	= '(TABLE|table)';
$KW_NOT 	= '(NOT|not)';
$KW_EX		= '(EXISTS|exists)';
$KW_NULL	= '(NULL|null)';

$O_OPN		= '`';
$L_PAR		= '(';
$R_PAR		= ')';
$STMT_END	= ';';

$CR_TABLE	= $KW_CREATE."[ ]+".$KW_TABLE."[ ]+(".$KW_IF."[ ]+$KW_NOT"."[ ]+$KW_EX"."[ ]+)?";
$CL_DCL_S		= $O_OPN.$WORD.$O_OPN.'.'.$O_OPN.$WORD.$O_OPN;
$CL_DCL		= $O_OPN.$WORD.$O_OPN;

$KW_PK	= 'PRIMARY KEY';
$KW_IDX	= 'INDEX';
$KW_CTR	= 'CONSTRAINT';
$KW_FK	= 'FOREIGN KEY';
$KW_REF	= 'REFERENCES';
$KW_OD	= 'ON DELETE';
$KW_OU	= 'ON UPDATE';
$KW_ENGINE = 'ENGINE = {CL_ENGINES}';



define('S_INITIAL', 0);
define('S_CREATE_TABLE', 1);

$state = S_INITIAL;


foreach($fString as $line){
	//Ignorar Linhas comentarios, ou instruções do SQL.
	if($line{0}.$line{1} == '--'){
		continue;
	}else if($line{0}.$line{1}.$line{2} == 'SET' || $line{0}.$line{1}.$line{2} == 'USE'){
		continue;
	}
	
	if($state == S_INITIAL){
		//Encontrou o criar Table (INICIAR ESTADO de DETECTAR)
		if(preg_match("/".$CR_TABLE."/i", $line, $matchs)){
			$stage = S_CREATE_TABLE;
			//Tenta dar match (
			if(preg_match("/".$CL_DCL_S."/i", $line, $matchs)){
				var_dump($matchs);		
			}else if(preg_match("/".$CL_DCL."/i", $line, $matchs)){
				var_dump($matchs);	
			}
			
		}
	}else if($state == S_CREATE_TABLE){
		
		//Encontrar o Final do Create Table
		if(preg_match("/".$CR_TABLE."/i", $line, $matchs)){
			$stage = S_INITIAL;
		}
		
	}
	var_dump(trim($line));
	
}

//{Description_Class}
//{PACKAGE}
//{SUBPACKAGE}
//{SINCE}
//{T_NAME}
//{M_NAME}



echo "\n\n\n\n";


function Menu(){
	echo "Uso do Script\n";
	echo "\t ex.: \$>php SQL_2_ENTITIES.php <nome do arquivo> <prefixo saida> <diretorio saida> \n";
}