#!/bin/bash

##
# Generate YiM Label
#
# Arguments:
# print.sh NAME POSITION ROOM FOOD COMMENT
#
# MEAL ***/early/late/-
# COMMENT:
#   U18 / Aufsichtsperson: Lukas Hermann (A2.201)
#   English Translation
#
# e.g. ./print.sh "Lukas" "TL" "2" "-" "Liz"


# Get user input
if [[ $1 == "" ]]
then
	echo "one arguments are mandatory"
    exit
fi

/usr/local/bin/weasyprint -s style.css ../labels/src/$1.html ../labels/pdf/$1.pdf

lpr -P Brother_QL-570 ../labels/pdf/$1.pdf
echo "lpr -P Brother_QL-570 ../labels/pdf/$1.pdf"




