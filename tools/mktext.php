<?php
ini_set('memory_limit','300M');

system("rm -rf 25 ; mkdir 25");

$nfilename = "25/notes.html";

for ($i = 0; $i <= 41; $i++) {
   $ifilename = sprintf("../tex/p%03d.tex", $i);
   $ofilename = sprintf("25/p%03d.html", $i);
   $ilines = file($ifilename);
   $olines = [];
   $nlines = [];
   foreach($ilines as $iline) {
      if (preg_match('/^\\\\vs p\d* (\d{1,2}):(\d{1,3}) (.*)$/u', $iline, $matches)) { // text line
         $chap = $matches[1];
         $verse = $matches[2];
         $text = convert_text($matches[3]);
         if ( ($i == 134 && $chap ==  6 && $verse == 14) || ($i == 120 && $chap ==  3 && $verse == 12) ||
              ($i == 144 && $chap ==  5 && $verse == 11) || ($i == 144 && $chap ==  5 && $verse == 25) ||
              ($i == 144 && $chap ==  5 && $verse == 38) || ($i == 144 && $chap ==  5 && $verse == 54) ||
              ($i == 144 && $chap ==  5 && $verse == 73) || ($i == 144 && $chap ==  5 && $verse == 87) ){
            $olines[] = '<h4><a class="U'.$i.'_'.$chap.'_'.$verse.'" href=".U'.$i.'_'.$chap.'_'.$verse.'">***</a></h4>'.PHP_EOL;
            continue;
         } elseif ( ($i ==  31 && $chap == 10 && $verse ==  22) || ($i ==  56 && $chap == 10 && $verse ==  23)) {
            $olines[] = '<h4><a class="U'.$i.'_'.$chap.'_'.$verse.'" href=".U'.$i.'_'.$chap.'_'.$verse.'">* * * * *</a></h4>'.PHP_EOL;
            continue;
         }
         //if ($i == 41 && $chap ==  4 && $verse == 4) echo $text;
         $fn_total = preg_match_all('/\\\\fn[cs]t?{([^}]*)}/u', $text, $fnotes);
         for ($fn = 0; $fn < $fn_total; $fn++) {
             $nlines[] = '<p><a class="U'.$i.'_'.$chap.'_'.$verse.'_'.$fn.'" href=".U'.$i.'_'.$chap.'_'.$verse.'"><sup>'.$i.':'.$chap.'.'.$verse.'['.$fn.']</sup></a> '.$fnotes[1][$fn].PHP_EOL;
             $text = preg_replace('/\\\\fn[cs]t?{([^}]*)}/u', '<a href=".U'.$i.'_'.$chap.'_'.$verse.'_'.$fn.'"><sup>('.$fn.')</sup></a>', $text, 1);
         }
         $olines[] = '<p><a class="U'.$i.'_'.$chap.'_'.$verse.'" href=".U'.$i.'_'.$chap.'_'.$verse.'">' .
                          '<sup>'.$i.':'.$chap.'.'.$verse.'</sup></a> ' .  $text . PHP_EOL;
      } elseif (preg_match('/^\\\\author{(.*)}/u', $iline, $matches)) { // Extract author
         $author = $matches[1];
      } elseif (preg_match('/^\\\\upaper{\d+}{(.*)}/u', $iline, $matches)) { // Extract paper title
         $paper = $matches[1];
      } elseif (preg_match('/^\\\\usection{(.*)}/u', $iline, $matches)) { // Extract section title
         $section = convert_section($matches[1]);
         if ($i == 0 && $chap == 12) { // Acknowledgment "section" in the Foreword
            $verse = 10;
            $section = '<i>'.$section.'</i>';
         } else {
            $chap++;
            $verse = 0;
            $section = $chap.'. '.$section;
         }
         $olines[] = '<h4><a class="U'.$i.'_'.$chap.'_'.$verse.'" href=".U'.$i.'_'.$chap.'_'.$verse.'">'.$section.'</a></h4>'.PHP_EOL;
      }
   }
   file_put_contents($ofilename, $olines);
   file_put_contents($nfilename, $nlines, FILE_APPEND);
}

function convert_section($text) {
   $search = ['/\\\\bibnobreakspace/u', '/\\\\subtitlefont */u', '/\\\\\\\\/u', '/\\\hyp{}/u', '/---/u', '/\\\\,/u'];
   $replace = ['', '', '<br>', '-', '—', ' '];
   return preg_replace($search, $replace, $text);
}

function convert_text($text) {
   $search = ['/\\\\pc /u',
              '/\\\\sum\\\\limits\_{k=1}\^7/u',
              '/\\\\bibnobreakspace/u',
              '/\\\\kern0.3em /u',
              '/\\\\mathbb{N}/u',
              '/\\\\dt/u',
              '/\\\\int /u',
              '/\$\\\\{/u',
              '/\\\\}\$/u',
              '/<</u',
              '/>>/u',
              '/\\\\bibnobreakspace/u',
              '/\\\\tunemarkup{pg[^}]*}{[^}]*}/u',
              '/\\\\index{[^}]*}/u',
              '/\\\\in/u',
              '/\\\\li{([^}]*)}/u',
              '/\\\\cite{([^}]*)}/u',
              '/\\\\bibdf/u',
              '/\\\\hfill/u',
              '/\\\\Rightarrow/u',
              '/\.pdf/u',
              '/\\\\makebox\[4em\]\[l\]{\$(.*)\$}/u',
              '/\\\\hsetoff */u',
              '/ *\\\\times */u',
              '/\$/u',
              '/\\\\hyp{}/u',
              '/\\\\\'(.)/u',
              '/---/u',
              '/--/u',
              '/``/u',
              '/`/u',
              '/\'\'/u',
              '/\'/u',
              '/\\\\,/u',
              '/~/u',
              '/ *\\\\pm\\\\* */u',
              '/\\\\%/u',
              '/\\\\bibfrac{(\d+)}{(\d+)}/u',
              '/\\\\ldots\\\\/u',
              '/\\\\mathcal{M}/u',
              '/\\\\mathbb{R}/u',
              '/\\\\over/u',
              '/\\\\rightarrow/u',
              '/\\\\sqrt{([^}]*)}/u',
              '/\\\\subset/u',
              '/\\\\ae */u',
              '/\\\\pi\^\+/u',
              '/e\^\+/u',
              '/\\\\approx/u',
              '/\\\\tau/u',
              '/\\\\nu_e/u',
              '/\\\\epsilon_0/u',
              '/\\\\nu/u',
              '/\\\\lambda/u',
              '/\\\\pi/u',
              '/\\\\alpha/u',
              '/\\\\neq/u',
              '/\\\\rho/u',
              '/\\\\Delta */u',
              '/\\\\geqslant */u',
              '/\\\\hbar */u',
              '/N\_k/u',
              '/x\^n/u',
              '/x\^{\\\\mu}/u',
              '/p_{\\\\mu}/u',
              '/_{\\\\odot}/u',
              '/_{Antares}/u',
              '/p_(x|y)/u',
              '/\\\\mu/u',
              '/\\\\ldots{}/u',
              '/\\\\ldots/u',
              '/\\\\textsc{([^}]*)}/u',
              '/\^{?(-?N?\d*)}?/u',
              '/\_({?\d+}?)/u',
              '/\\\\bibref\[([^]]*)\]{p0*(\d{1,3}) (\d{1,2}):(\d{1,3})}/u',
              '/\\\\ts{([^}]*)}/u',
              '/\\\\(?:bibemph|textit|bibexpl){([^}]*)}/u',
              '/\\\\(?:mathbf|textbf|bibtextul){([^}]*)}/u',
              '/\\\\(?:texttt|textgreek|textchinese|textarm|textcolour{ubdarkred}){([^}]*)}/u',
              '/\\\\tunemarkup{(pictures|private)}{.*images\/([^}]*)}.*\\\\caption{([^}]*)}\\\end{figure}}$/u'];
   $replace = ['§§ ',
               '∑<sub>k=1</sub><sup>7</sup>',
               '',
               '',
               'ℕ',
               '&centerdot;',
               '∫',
               '',
               '',
               '«',
               '»',
               '',
               '',
               '',
               '∈',
               '$1 ',
               '$1',
               ' ...',
               ' ',
               '⇨',
               '.jpg',
               '$1',
               '',
               '×',
               '',
               '-',
               '<b>$1</b>',
               '—',
               '–',
               '“',
               '‘',
               '”',
               '’',
               ' ',
               ' ',
               '±',
               '%',
               '$1/$2',
               '...',
               '<i>M</i>',
               'ℝ',
               '/',
               '→',
               '√($1)',
               '⊂',
               'ae',
               'π<sup>+</sup>',
               'e<sup>+</sup>',
               '≈',
               'τ',
               'ν<sub>e</sub>',
               'ε<sub>0</sub>',
               'ν',
               'λ',
               'π',
               'α',
               '≠',
               'ρ',
               'Δ',
               '⩾',
               'ℏ',
               'N<sub>k</sub>',
               'x<sup>n</sup>',
               'x<sup>μ</sup>',
               'p<sub>μ</sub>',
               '<sub>Sun</sub>',
               '<sub>Antares</sub>',
               'p<sub>$1</sub>',
               'μ',
               '...',
               '...',
               '<span class="sc">$1</span>',
               '<sup>$1</sup>',
               '<sub>$1</sub>',
               '<a href=".U$2_$3_$4">$1</a>',
               '<sup>$1</sup>',
               '<i>$1</i>',
               '<b>$1</b>',
               '$1',
               '<figure class="$1"><img class="pictures" src="img/$2"><figcaption>$3</figcaption></figure>'];

   $stage1 =  preg_replace($search, $replace, $text);

   return preg_replace_callback('/\\\\textheb{([^}]*)}/u', // reverse Hebrew for RTL
             function($match) {return implode(array_reverse(explode(" ",$match[1]))," ");},
             $stage1);
}
?>
