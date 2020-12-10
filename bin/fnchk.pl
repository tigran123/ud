#!/usr/bin/perl

use strict;

my %fn_a = ();
my %fn_b = ();
while (<>) {
  chomp;
  die if ! m,^([ab]) (\d+) (.*)$,;
  # (.*) allows e.g. roman page numbers
  # TODO use global page sequence number, and require the 3rd arg to be (\d+)
  if ($1 eq 'a') { # footnote attachment point
    die if exists $fn_a{$2};
    $fn_a{$2} = $3;
  } else { # footnote body
    die if exists $fn_b{$2};
    $fn_b{$2} = $3;
  }
}

for my $seq (sort {$a <=> $b} keys %fn_a) {
  if (! exists $fn_b{$seq}) {
    print STDERR "footnote $seq has attachment point but no body\n";
  } else {
    print STDERR "footnote $seq: attachment point on page $fn_a{$seq}, body on page $fn_b{$seq}\n"
      if $fn_b{$seq} ne $fn_a{$seq};
    delete $fn_b{$seq};
  }
}

for my $seq (sort {$a <=> $b} keys %fn_b) {
  print STDERR "footnote $seq has body but no attachment point\n";
}
