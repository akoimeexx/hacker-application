#!/bin/bash

##
# TTY shell extras

##
# Environment EXPORTs
export EDITOR=vim

export DEVKITPRO=/media/disk/devel/devkitPRO
export DEVKITARM=${DEVKITPRO}/devkitARM
export PATH=$DEVKITARM/bin:$PATH

##
# Aliases
alias irc="irssi -c zelazny.freenode.net"
alias links="links -html-assume-codepage utf-8"
alias md5="md5sum"
alias say="espeak -g11 -k20 -s150"

# Custom directory listing function for great justice and my own amusement.
function ll () {
        # Suss out the directory we're trying to list
        if [ -d ${@:-1} ]
                then location=${@:-1}
                else location=`pwd`
        fi
        # Put together our directory listing, with custom headers.
        # Include additional arguments passed in from the function call.
        # Pipe the output through more
        {
                printf '\x1b\x5b34m<index \x1b\x5b32mlocation\x1b\x5b35m=\x1b\x5b33m\"%s\"\x1b\x5b34m>\x1b\x5b00m\n' "$location" &&
                ls -FGhlpv --group-directories-first --color=always $@ &&
                printf '\x1b\x5b34m</index>\x1b\x5b00m\n';
        } |more
}
