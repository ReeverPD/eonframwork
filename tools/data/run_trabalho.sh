!#/bin/bash

ECHO "################################"
ECHO "Compilando LEX"
ECHO "################################"
./buildLex.sh eon_sql_to_zend_entity_convert.l eon_sql_to_zend_entity_convert

ECHO "################################"
ECHO "Iniciando Conversão"
ECHO "################################"
./eon_sql_to_zend_entity_convert $1
