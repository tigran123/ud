MOD = ud
SHELL = /bin/bash
LATEX = xelatex -halt-on-error $(MOD) < /dev/null > /dev/null 2>&1

all::		$(MOD).pdf

.PHONY:		clean

clean::		
		@rm -f $(MOD)*.{aux,bibtoc,fnchk,idx,ilg,ind,lof,log,out,pdf} select-book.tex missfont.log

$(MOD).pdf:	select-book.tex
		$(LATEX)
ifndef DRAFT
		@if test -f $(MOD).idx; then makeindex -q $(MOD); fi
		$(LATEX)
		@if test -f $(MOD).idx; then makeindex -q $(MOD); fi
		$(LATEX)
		@if test -f $(MOD).idx; then makeindex -q $(MOD); fi
endif

select-book.tex:	
ifdef LIST
	$(shell export LINE="\includeonly{" ; \
		for b in $(LIST) ; do \
			LINE="$${LINE}tex/$${b}," ; \
		done ; \
		echo $${LINE}} | sed "s/,}/}/" > select-book.tex \
	)
else
	@> select-book.tex
endif
