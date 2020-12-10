#!/bin/bash

declare -i MAXLINELEN=74

for ((i = 0 ; i <= 196 ; i++));
do
   I=$(printf "%03d" $i)
   if [ ! -f tex/p${I}.tex ] ; then
      continue
   fi
   sed -ne "s/^.usection{\([0-9][0-9]*\)\..bibnobreakspace \(.*\)}$/\\\usectiontitle{$i}{\1}{\2}/p" tex/p${I}.tex | sed -e "s%\\\\hyp{}%-%g" -e "s%\\\\\\\\% %g" > tex/p${i}-sections.tmp
   > tex/p${i}-sections.tex
   while read -r line
   do
      if [ ${#line} -gt $MAXLINELEN ] ; then
         newline="$(echo -E "$line" | cut -c1-$MAXLINELEN)...}" 
         echo -E "$newline" >> tex/p${i}-sections.tex
      else
         echo -E "$line" >> tex/p${i}-sections.tex
      fi
   done < tex/p${i}-sections.tmp > tex/p${i}-sections.tex
done
rm tex/p*-sections.tmp
