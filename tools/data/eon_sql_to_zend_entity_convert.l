%option noyywrap
%{
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
	* @since 03/2013
	* @version 0.0.1-alpha
	* 
	*/
	
	#include <string.h>
	#include <math.h>
	#include <stdio.h>

	FILE *file; 
	
	//#define SAVE_TOKEN yylval.string = new std::string(yytext, yyleng)
	//#define TOKEN(t) (yylval.token = t)
	//extern "C" int yywrap() { }
	
	//CREATE  TABLE IF NOT EXISTS `crowdsource`.`reev_empresa`

%}

%s TABLE_DCL
%s TABLE_BODY

DIGIT		[0-9]
WORD		[a-zA-Z][a-zA-Z0-9_]*
N_REAL		{DIGIT}+"."{DIGIT}+
N_INT		{DIGIT}+

CL_TYPES	("VARCHAR"|"INTEGER"|"REAL"|"CHAR"|"BIT")
CL_ENGINES	("InnoDB"|"MyISAM")

KW_CREATE 	("CREATE"|"create")
KW_IF 		("IF"|"if")
KW_TABLE 	("TABLE"|"table")
KW_NOT 		("NOT"|"not")
KW_EX		("EXISTS"|"exists")
KW_NULL		("NULL"|"null")

O_OPN		"`"
L_PAR		"("
R_PAR		")"
STMT_END	";"

CR_TABLE	{KW_CREATE}[" "]+{KW_TABLE}[" "]+({KW_IF}[" "]+{KW_NOT}[" "]+{KW_EX}[" "]+)?
CL_DCL		{O_OPN}{WORD}{O_OPN}

KW_PK	"PRIMARY KEY"
KW_IDX	"INDEX"
KW_CTR	"CONSTRAINT"
KW_FK	"FOREIGN KEY"
KW_REF	"REFERENCES"
KW_OD	"ON DELETE"
KW_OU	"ON UPDATE"
KW_ENGINE "ENGINE = "{CL_ENGINES}

%%

{CR_TABLE}		{ 
	BEGIN(TABLE_DCL); 
	printf("T: %s\n", yytext); 
}

<TABLE_DCL>{O_OPN}{WORD}{O_OPN}\.{O_OPN}{WORD}{O_OPN}	{ 
	BEGIN(TABLE_BODY); 
	printf("\tSN: %s\n", yytext); 
}

<TABLE_BODY>{KW_CTR}[" "]*{O_OPN}{WORD}{O_OPN} {
	printf("\tSNCTR: %s\n", yytext); 
}

<TABLE_BODY>{CL_DCL} {
	printf("\tSNC: %s\n", yytext); 
}

<TABLE_BODY>{KW_IDX}[" "]*{CL_DCL} {
	printf("\tSNIDX: %s\n", yytext); 
}

<TABLE_BODY>{KW_PK}[" "]+{L_PAR}[" "]*{CL_DCL}[" "]*{R_PAR}	{
	printf("\tSNPK: %s\n", yytext); 
}

<TABLE_BODY>{KW_FK}[" "]+{L_PAR}[" "]*{CL_DCL}[" "]*{R_PAR}	{
	printf("\tSNFK: %s\n", yytext); 
}

<TABLE_BODY>{STMT_END}	{
	BEGIN(INITIAL); 
}




[ \t\n]                 ;
.						;

%%
/**
 * Conversor do SQL para Zend Entities
 * 
 * @author Iuri Andreazza {iuri@reeverpd.com.br}
 * @since 03/2013 
 * @version 0.0.1.-alpha
 */
int main(int argc, char *argv[]){
	int i = 0;
	
	yyin = fopen(argv[1], "r");
	yylex();
	fclose(yyin);
	
	//file = fopen(argv[1],"a+");
	//for(i = 2; i < argc; i++){
	//	printf("Arquivo: %s\n", argv[i]);
	//	yyin = fopen(argv[i], "r");
	//	yylex();
	//	fclose(yyin);
	//}
	//fclose(file);
  
  return 0;
}
