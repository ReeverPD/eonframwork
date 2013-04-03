#!/bin/bash
rm $2.c;
rm $2;
flex -o$2.c $1;
gcc $2.c -o $2
rm $2.c;

