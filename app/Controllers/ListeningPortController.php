<?php
namespace App\Controllers;

use Liman\Toolkit\OS\Distro;
use Liman\Toolkit\Shell\Command;
use App\Controllers\FirewallController;


class ListeningPortController
{

    public function get(){
		$raw = trim(Command::runSudo("lsof -i -P -n | grep LISTEN | awk -F' ' '{print $1,$2,$3,$5,$8,$9}'"));
		$listeningPorts = [];

		foreach(explode("\n", $raw) as $line){
			$row = explode(" ", $line);
			if(!empty($row)){
				$listeningPorts[] = [
					'processName' => $row[0],
					'processID' => $row[1],
					'userName' => $row[2],
					'ipVersion' => $row[3],
					'protocol' => $row[4],
					'port' => $row[5],
					'status' => FirewallController::checkStatus(parsePort($row[5]), $row[3], $row[4])
				];
			}
		}

        return view('components.listeningPorts-table', [
			'listeningPorts' => $listeningPorts
		]);
    }
    
    function getTotal(){
        return respond(Command::runSudo("lsof -i -P -n  | grep 'LISTEN' | wc -l"), 200);
    }

}