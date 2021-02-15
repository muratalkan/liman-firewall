<?php

use Liman\Toolkit\Shell\Command;

function index()
{
    $parsedProcesses = listeningPorts();
    $parsedRules = firewallRules();

    return view('index', [
        "procDetailsArr" => $parsedProcesses,
        "rulesArr" => $parsedRules
    ]);
}

function installPackages(){
    Command::run('sudo apt-get install ufw -y ');
    Command::run('sudo yum install epel-release -y');
    Command::run('sudo yum install --enablerepo="epel" ufw -y');
    
    Command::run('sudo yum install net-tools ');
    Command::run('sudo apt-get install net-tools ');
}

function listeningPorts(){
    $commandOutput = runCommand("sudo netstat -tulpn  | grep LISTEN");
    $parsedLines = explode("\n", $commandOutput);

    $parsedProcesses=[];
    $i=1;
    foreach($parsedLines as $line){
        $processDetails = explode(" ", $line);
        $processDetails_new = array_values(array_filter($processDetails, 'strlen'));
        
        array_push($parsedProcesses, [
            "ID" => $i,
            "process" => $processDetails_new[6],
            "localAddr" => $processDetails_new[3],
            "protocol" => $processDetails_new[0]
        ]);
        $i++;
    }
    return $parsedProcesses;
}

function firewallRules(){
    $commandOutput = runCommand("sudo ufw status numbered");
    $parsedLines = explode("\n", $commandOutput);

    $parsedRules=[];
    for ($i = 0; $i < count($parsedLines); $i++) {
        if($parsedLines[$i][0] == "["){
            if($parsedLines[$i][1] == " ") { 
                $parsedLines[$i][1] = "0"; 
            }

            $ruleDetails = explode(" ", $parsedLines[$i]);
            $ruleDetails_new = array_values(array_filter($ruleDetails, 'strlen'));
    
            $col1 = $ruleDetails_new[0];
            for($j=0; $j<count($ruleDetails_new); $j++){
                if($ruleDetails_new[$j]=="ALLOW" || $ruleDetails_new[$j]=="DENY"){
                    $temp = $ruleDetails_new;
                    $col3 = $ruleDetails_new[$j]." ".$ruleDetails_new[$j+1]; 
                    $col2 = implode(" ", array_splice($ruleDetails_new, 1, $j-1));
                    $col4 = implode(" ", array_splice($temp, $j+2, count($temp)-$j-2));
                }
            }
    
            array_push($parsedRules, [
                "id" => $col1,
                "toAddr" => $col2,
                "action" => $col3,
                "fromAddr" => $col4
            ]);
        }

    }

    return $parsedRules;
}


function runFirewallCommand(){
    $runningCommand = request("command");
    $output = runCommand($runningCommand);
    return respond($output);
}

function fetchUFWStatus()
{
    return respond(Command::run('sudo ufw status'), 200);
}

function enableUFWStatus(){
    return respond(Command::run('echo y | sudo ufw enable'), 200);
}

function disableUFWStatus(){
    return respond(Command::run('sudo ufw disable'), 200);
}

function getTotalUfwRules(){
    $commandOutput = runCommand("sudo ufw status numbered");
    return respond(array(
        'allowCount' => substr_count($commandOutput, 'ALLOW'),
        'denyCount' => substr_count($commandOutput, 'DENY')
    ));
}

function getTotalListeningPorts(){
    $commandOutput = runCommand("sudo netstat -tulpn  | grep LISTEN");
    $parsedLines = explode("\n", $commandOutput);

    return respond(count($parsedLines));
}