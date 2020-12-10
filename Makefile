#
# Makefile for ud (Урантийские Документы)
# 

#SHELL = /bin/bash

MOD = ud

BOOKS = p000 p001 p002 p003 p004 p005 p006 p007 p008 p009 p010 p011 p012 p013 p014 p015 p016 p017 p018 p019 p020 p021 p022 p023 p024 p025 p026 p027 p028 p029 p030 p031 p032 p033 p034 p035 p036 p037 p038 p039 p040 p041 p042 p043 p044 p045 p046 p047 p048 p049 p050 p051 p052 p053 p054 p055 p056 p057 p058 p059 p060 p061 p062 p063 p064 p065 p066 p067 p068 p069 p070 p071 p072 p073 p074 p075 p076 p077 p078 p079 p080 p081 p082 p083 p084 p085 p086 p087 p088 p089 p090 p091 p092 p093 p094 p095 p096 p097 p098 p099 p100 p101 p102 p103 p104 p105 p106 p107 p108 p109 p110 p111 p112 p113 p114 p115 p116 p117 p118 p119 p120 p121 p122 p123 p124 p125 p126 p127 p128 p129 p130 p131 p132 p133 p134 p135 p136 p137 p138 p139 p140 p141 p142 p143 p144 p145 p146 p147 p148 p149 p150 p151 p152 p153 p154 p155 p156 p157 p158 p159 p160 p161 p162 p163 p164 p165 p166 p167 p168 p169 p170 p171 p172 p173 p174 p175 p176 p177 p178 p179 p180 p181 p182 p183 p184 p185 p186 p187 p188 p189 p190 p191 p192 p193 p194 p195 p196

MISCFILES = tex/intro.tex tex/title.tex tex/paper-titles.tex tex/quiz.tex

WORKDIR = $(TMPDIR)/$(MOD)

# otherwise makeindex won't work with our choice of WORKDIR
export openout_any = a

LATEX = xelatex -output-directory=$(WORKDIR) -halt-on-error $(MOD) < /dev/null > /dev/null 2>&1

ifdef LIST
	TEXFILES = $(MISCFILES) \
		   $(shell echo $(LIST) | sed "s/\([^ ][^ ]*\)/tex\/\1.tex/g")
	SUBSET=yes
else
	LIST = $(BOOKS)
	TEXFILES = $(wildcard tex/*.tex)
	SUBSET=no
endif

all::		$(MOD).pdf

.PHONY:		clean

clean::		
		@rm -rf $(WORKDIR) select-book.tex missfont.log

vclean:		clean
		@rm -f $(MOD)*.pdf

$(MOD).pdf:	tex $(MOD).tex select-book.tex $(TEXFILES)
		@mkdir -p $(WORKDIR)
		$(LATEX)
ifndef DRAFT
		@if test -f $(WORKDIR)/$(MOD).idx; then makeindex -q $(WORKDIR)/$(MOD); fi
		$(LATEX)
		@if test -f $(WORKDIR)/$(MOD).idx; then makeindex -q $(WORKDIR)/$(MOD); fi
		$(LATEX)
		@if test -f $(WORKDIR)/$(MOD).idx; then makeindex -q $(WORKDIR)/$(MOD); fi
		@if test -s $(WORKDIR)/$(MOD).fnchk; then perl bin/fnchk.pl < $(WORKDIR)/$(MOD).fnchk; fi
endif
		@mv $(WORKDIR)/$(MOD).pdf .

select-book.tex:	
ifeq ($(SUBSET),yes)
	$(shell export LINE="\includeonly{" ; \
		for b in $(LIST) ; do \
			LINE="$${LINE}tex/$${b}," ; \
		done ; \
		echo $${LINE}} | sed "s/,}/}/" > select-book.tex \
	)
else
	@> select-book.tex
endif
