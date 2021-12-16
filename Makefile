# Makefile for ud (Урантийские Документы)

MOD = ud

ifndef TMPDIR
	WORKDIR = /tmp/$(MOD)
else
	WORKDIR = $(TMPDIR)/$(MOD)
endif

# otherwise makeindex won't work with our choice of WORKDIR
export openout_any = a

LATEX = xelatex -output-directory=$(WORKDIR) -halt-on-error $(MOD) < /dev/null > /dev/null 2>&1

all::		$(MOD).pdf

.PHONY:		clean

clean::		
		@rm -rf $(WORKDIR) select-book.tex missfont.log $(MOD).pdf

$(MOD).pdf:	select-book.tex
		@mkdir -p $(WORKDIR)
		$(LATEX)
ifndef DRAFT
		@if test -f $(WORKDIR)/$(MOD).idx; then makeindex -q $(WORKDIR)/$(MOD); fi
		$(LATEX)
		@if test -f $(WORKDIR)/$(MOD).idx; then makeindex -q $(WORKDIR)/$(MOD); fi
		$(LATEX)
		@if test -f $(WORKDIR)/$(MOD).idx; then makeindex -q $(WORKDIR)/$(MOD); fi
endif
		@mv $(WORKDIR)/$(MOD).pdf .

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
