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

error_reporting(E_ERROR);
ini_set('display_errors', 1);

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

$dirT = '';
$dirM = '';

if($dir != null){
	mkdir($dir.'/tables', '0777', true);
	$dirT = $dir.'/tables';
	$dirM = $dir;
}else{
	mkdir('Model/tables', '0777', true);
	$dirT = 'Model/tables';
	$dirM = 'Model';
}


$templateDataTable 	= file_get_contents('Template_DataTable.tphp');
$templateModel 		= file_get_contents('Template_Model.tphp');


//RULES
$DIGIT		= '[0-9]';
$WORD		= '[a-zA-Z][a-zA-Z0-9_]*';
$N_REAL		= "$DIGIT+.$DIGIT+";
$N_INT		= "$DIGIT+";

$CL_TYPES	= '(VARCHAR|INTEGER|REAL|CHAR|BIT|INT|VARCHAR\('.$N_INT.'\))';
$CL_ENGINES	= '(InnoDB|MyISAM)';

$KW_CREATE 	= '(CREATE|create)';
$KW_IF 		= '(IF|if)';
$KW_TABLE 	= '(TABLE|table)';
$KW_NOT 	= '(NOT|not)';
$KW_EX		= '(EXISTS|exists)';
$KW_NULL	= '(NULL|null)';

$O_OPN		= '`';
$L_PAR		= '\(';
$R_PAR		= '\)';
$STMT_END	= ';';

$R_SPACE 	= "[ ]+";

$KW_PK		= 'PRIMARY KEY';
$KW_IDX		= 'INDEX';
$KW_CTR		= 'CONSTRAINT';
$KW_FK		= 'FOREIGN KEY';
$KW_REF		= 'REFERENCES';
$KW_OD		= 'ON DELETE';
$KW_OU		= 'ON UPDATE';
$KW_ENGINE 	= "ENGINE = $CL_ENGINES;";


$CR_TABLE	= $KW_CREATE."[ ]+".$KW_TABLE."[ ]+(".$KW_IF."[ ]+$KW_NOT"."[ ]+$KW_EX"."[ ]+)?";
$CL_DCL_S	= $O_OPN.$WORD.$O_OPN.'.'.$O_OPN.$WORD.$O_OPN;
$CL_DCL		= $O_OPN.$WORD.$O_OPN;

$CC_DCL 	= $O_OPN.$WORD.$O_OPN.$R_SPACE.$CL_TYPES;
$PK_DCL		= $KW_PK.$R_SPACE.$L_PAR.$O_OPN.$WORD.$O_OPN.$R_PAR;
$FK_DCL		= $KW_FK.$R_SPACE.$L_PAR.$O_OPN.$WORD.$O_OPN.$R_SPACE.$R_PAR;
$RF_DCL		= $KW_REF.$R_SPACE.$O_OPN.$WORD.$O_OPN.'\.'.$O_OPN.'('.$WORD.')'.$O_OPN.$R_SPACE.$L_PAR.$O_OPN.'('.$WORD.')'.$O_OPN.$R_SPACE.$R_PAR;



define('S_INITIAL', 0);
define('S_CREATE_TABLE', 1);

$state = S_INITIAL;


$rule_descriptionClass 		= "{Description_Class}";
$rule_package 				= "{PACKAGE}";
$rule_subPackage 			= "{SUBPACKAGE}";
$rule_since 				= "{SINCE}";
$rule_tableName 			= "{T_NAME}";
$rule_modelName 			= "{M_NAME}";
$rule_modelProperties 		= "{PROPRIEDADES}";
$rule_modelPropertiesMeta 	= "{PROPRIEDADES_META}";
$rule_modelPropertiesVinc 	= "{PROPRIEDADES_VINCULADOS}";



$modelStr = "";
$tableStr = "";
$model = "";
$table = '';
$tSufix = "";
foreach($fString as $numLine => $line){
	$line = trim($line);
	//Ignorar Linhas comentarios, ou instruções do SQL, ou mesmo linhas em branco.
	if($line{0}.$line{1} == '--'){
		continue;
	}else if($line{0}.$line{1}.$line{2} == 'SET' || $line{0}.$line{1}.$line{2} == 'USE'){
		continue;
	}else if($line == ''){
		continue;
	}
	
	
	if($state == S_INITIAL)
	{
		//Encontrou o criar Table (INICIAR ESTADO de DETECTAR)
		if(preg_match("/".$CR_TABLE."/i", $line, $matchs))
		{
			echo "---------\n";
			$state = S_CREATE_TABLE;
			
			if(preg_match("/".$CL_DCL_S."/i", $line, $matchs))
			{
				//Tenta dar match no nome da base + table
				$table = str_replace($O_OPN, '', array_pop(explode('.', $matchs[0])));
			}
			else if(preg_match("/".$CL_DCL."/i", $line, $matchs))
			{
				//Tenta dar Match somente no nome da table
				$table = str_replace($O_OPN, '', $matchs[0]);
			}
			
			if($table != "")
			{
				
				/*$names = explode('_',$table);
				array_shift($names);
				foreach($names as $k => $name)
				{
					$name = strtoupper($name{0}).substr($name, 1);
					$names[$k] = $name;
				}
				
				$tSufix = array_shift(explode('_', $table));
				$model = strtoupper($tSufix).'_'.implode('', $names).'Model';*/
				
				$model = getModelName($table);
				
				$tableStr = $templateDataTable;
				$tableStr = str_replace($rule_tableName, $table, $tableStr);
				$tableStr = str_replace($rule_modelName, $model, $tableStr);
				
				$modelStr = $templateModel;
				$modelStr = str_replace($rule_tableName, $table, $modelStr);
				$modelStr = str_replace($rule_modelName, $model, $modelStr);
				file_put_contents($dirT.'/'.$table.'.php', $tableStr);
				echo "Iniciando Parser dos Objetos\n";
			}
			
		}
	}else if($state == S_CREATE_TABLE){
		
		//Regra da COLUNA
		if(preg_match("/".$CC_DCL."/i", $line, $matchs)){
			
			$dlcLine = array_shift($matchs);
			$dlcLine = str_replace($O_OPN, '', $dlcLine);
			$dType = array_pop($matchs);
			$dlcLine = str_replace($dType, '', $dlcLine);
			$cLine = trim($dlcLine); 
			//Nome da coluna é separada por _
			if(strpos($dlcLine, '_') !== false){
				$idrs = explode('_', $dlcLine);
				$dlcLine = "";
				foreach($idrs as $id){
					$dlcLine .= strtoupper($id{0}).substr($id, 1);
				}	
			}
			
			$dlcLine = trim(strtoupper($dlcLine{0}).substr($dlcLine, 1));
			echo "\tAtributo: $dlcLine -> $cLine \n";
			
			$sAttr[$dlcLine] = "\t".'protected $'.$dlcLine.";\n";
			$sAttrMetadata[$dlcLine] = "\n\t\t\t".' $this->_atributosMetadata[] = $attrMetadata = new ReeverAttributeMetadata();'."\n";
			$sAttrMetadata[$dlcLine] .= "\t\t\t".'$attrMetadata->attrName = "'.$dlcLine.'";'."\n";
			$sAttrMetadata[$dlcLine] .= "\t\t\t".'$attrMetadata->colName = "'.$cLine.'";'."\n";
			$sAttrMetadata[$dlcLine] .= "\t\t\t".'$attrMetadata->dataType = "'.$dType.'";'."\n";
			$sAttrMetadata[$dlcLine] .= "\t\t\t".'$attrMetadata->colType = "'.$dType.'";'."\n";
			$sAttrMetadata[$dlcLine] .= "\t\t\t".'$attrMetadata->needValidate = true;'."\n";
			if(preg_match("/".$CC_DCL."/i", $line, $matchs)){
				$sAttrMetadata[$dlcLine] .= "\t\t\t".'$attrMetadata->required = true;'."\n";
			}else{
				$sAttrMetadata[$dlcLine] .= "\t\t\t".'$attrMetadata->required = false;'."\n";
			}
			
			//var_dump($dlcLine);
			//var_dump($sAttrMetadata[$dlcLine]);
		}

		//Regra do PK
		if(preg_match("/".$PK_DCL."/i", $line, $matchs)){
			
			$dlcLine = array_shift($matchs);
			$dlcLine = str_replace($O_OPN, '', $dlcLine);
			$dlcLine = str_replace($KW_PK, '', $dlcLine);
			$dlcLine = str_replace("(", '', $dlcLine);
			$dlcLine = str_replace(")", '', $dlcLine);
			//Nome da coluna é separada por _
			if(strpos($dlcLine, '_') !== false){
				$idrs = explode('_', $dlcLine);
				$dlcLine = "";
				foreach($idrs as $id){
					$dlcLine .= strtoupper($id{0}).substr($id, 1);
				}	
			}
			$dlcLine = trim(strtoupper($dlcLine{0}).substr($dlcLine, 1));
			$sAttrMetadata[$dlcLine] .= "\t\t\t".'$attrMetadata->isPrimary = true;'."\n";
			//var_dump($dlcLine);
			//var_dump($sAttrMetadata[$dlcLine]);
			//var_dump($dlcLine);			
			//var_dump($matchs);
		}
		
		//Regra de FK
		if(preg_match("/".$FK_DCL."/i", $line, $matchs)){
			
			$dlcLine = array_shift($matchs);
			$dlcLine = str_replace($O_OPN, '', $dlcLine);
			$dlcLine = str_replace($KW_FK, '', $dlcLine);
			$dlcLine = str_replace("(", '', $dlcLine);
			$cl = trim($dlcLine = str_replace(")", '', $dlcLine));
			//Nome da coluna é separada por _
			if(strpos($dlcLine, '_') !== false){
				$idrs = explode('_', $dlcLine);
				$dlcLine = "";
				foreach($idrs as $id){
					$dlcLine .= strtoupper($id{0}).substr($id, 1);
				}	
			}
			$dlcLine = trim(strtoupper($dlcLine{0}).substr($dlcLine, 1));
			$sAttrMetadata[$dlcLine] .= "\t\t\t".'$attrMetadata->isFK = true;'."\n";
			
			//Consumir Linha seguinte esperando um references
			//var_dump(next($fString));
			//var_dump($RF_DCL);
			if(preg_match("/".$RF_DCL."/i", trim($fString[$numLine+1]), $matchs)){
				array_shift($matchs);
				$tableVinculado = $matchs[0];
				$modelVinculado = getModelName($tableVinculado);
				$colVinculado = $matchs[1];
				$colVinculadoFilho = $cl;

				$propVinc = "
				/**
				 * Retorna o $modelVinculado listado como FK no banco
				 * @return $modelVinculado
				 */
				function get$dlcLine(){
					if(is_null(\$this->$dlcLine)){
						\$this->$dlcLine =  new $modelVinculado(new $tableVinculado());
						return \$this->{$dlcLine}->getById(\$this->$dlcLine);
					}else{
						return \$this->$dlcLine;
					}
				}
				";
				
				$modelStr = str_replace($rule_modelPropertiesVinc, $rule_modelPropertiesVinc.$propVinc, $modelStr);
				//var_dump($modelVinculado);
				//$fString[$numLine+1]
				//var_dump($fString[$numLine+1]);
				//var_dump($matchs);
				//var_dump($dlcLine);
				//var_dump($cl);
			}
			
			
		}
		
		//Encontrar o Final do Create Table
		if(preg_match("/".$KW_ENGINE."/i", $line, $matchs)){
			if($model != ''){
				//Limpa as expansões finais
				echo "Gerando Model -> $model \n";
				echo "Gerando Table -> $table \n";
				$modelStr = str_replace($rule_modelPropertiesVinc, '', $modelStr);
				$modelStr = str_replace($rule_modelProperties, implode("\n", $sAttr), $modelStr);
				$modelStr = str_replace($rule_modelPropertiesMeta, implode("\n", $sAttrMetadata), $modelStr);
				file_put_contents($dirM.'/'.$model.'.php', $modelStr);
				$model = '';
			}
			$state = S_INITIAL;
		}
		
		
		
	}

}



echo "\n\n\n\n";

function getModelName($table){
	$names = explode('_',$table);
	array_shift($names);
	
	foreach($names as $k => $name)
	{
		$name = strtoupper($name{0}).substr($name, 1);
		$names[$k] = $name;
	}
	
	$tSufix = array_shift(explode('_', $table));
	return strtoupper($tSufix).'_'.implode('', $names).'Model';
}

function GetTableName(){
	
}

function Menu(){
	echo "Uso do Script\n";
	echo "\t ex.: \$>php SQL_2_ENTITIES.php <nome do arquivo> <prefixo saida> <diretorio saida> \n";
}