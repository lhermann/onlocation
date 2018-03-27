#!/bin/bash

##
# Generate YiM Label
#
# Test commands:
# /usr/local/bin/weasyprint -s style.css ../labels/src/8262.html ../labels/pdf/8262.pdf
# sudo -u www-data php triggerprint.php


# Get user input
if [[ $1 == "" ]]
then
	echo "one argument is mandatory"
    exit
fi

/usr/local/bin/weasyprint -s style.css ../labels/src/$1.html ../labels/pdf/$1.pdf

lpr -P Brother_QL-570 ../labels/pdf/$1.pdf
echo "lpr -P Brother_QL-570 ../labels/pdf/$1.pdf"




