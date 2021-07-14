<?php

use Liman\Toolkit\OS\Distro;
use Liman\Toolkit\Shell\Command;

function getListeningPortCount(){
    return respond(Command::runSudo("lsof -i -P -n  | grep 'LISTEN' | wc -l"));
}

function getFirewallRulesCount(){
    return respond(Command::runSudo("ufw status |  wc -l"));
}