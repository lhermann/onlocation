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

/usr/local/bin/weasyprint -r 300 -s style.css ../labels/src/$1.html ../labels/png/$1.png

/usr/local/bin/brother_ql print -l 62 ../labels/png/$1.png
echo "brother_ql print -l 62 ../labels/png/$1.png"



