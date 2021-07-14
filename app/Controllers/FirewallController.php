<?php
namespace App\Controllers;

use Liman\Toolkit\OS\Distro;
use Liman\Toolkit\Shell\Command;


class FirewallController
{

    public function get(){
        $raw = Command::runSudo("ufw status numbered | tail -n +5 | tr -d '[]'");
        $parsedLines = explode("\n", $raw);
    
        $parsedRules=[];
        for ($i = 0; $i < count($parsedLines); $i++) {
            if(!empty($parsedLines[$i])){
                $ruleDetails = explode(" ", $parsedLines[$i]);
                $ruleDetails_new = array_values(array_filter($ruleDetails, 'strlen'));
            
                $col1 = $ruleDetails_new[0];
                for($j=0; $j<count($ruleDetails_new); $j++){
                    if($ruleDetails_new[$j]=='ALLOW' || $ruleDetails_new[$j]=='DENY'){
                        $temp = $ruleDetails_new;
                        $col3 = $ruleDetails_new[$j].' '.$ruleDetails_new[$j+1]; 
                        $col2 = implode(" ", array_splice($ruleDetails_new, 1, $j-1));
                        $col4 = implode(" ", array_splice($temp, $j+2, count($temp)-$j-2));
                    }
                }
            
                $parsedRules[] = [
                    'id' => $col1,
                    'toAddress' => $col2,
                    'status' => $col3,
                    'fromAddress' => $col4
                ];
            }
        
        }
        
        return view('components.firewallRules-table', [
			'firewallRulesData' => $parsedRules
		]);
    }

    public function fetchStatus(){
		$this->getUFWStatus();
		return respond(__('Firewall is active'), 200);
    }

    public function enable(){
        $result = Command::runSudo("echo y | sudo ufw enable 2>/dev/null 1>/dev/null && echo 1 || echo 0"); 
        if($result == 1){
            return respond(__('Firewall has been enabled'));
        }
        return respond(__('Firewall could not be enabled!'), 201);
    }
    
    public function disable(){
        $result = Command::runSudo("ufw disable 2>/dev/null 1>/dev/null && echo 1 || echo 0"); 
        if($result == 1){
            return respond(__('Firewall has been disabled'));
        }
        return respond(__('Firewall could not be disabled!'), 201);
    }

    public function getTotal(){
        $this->getUFWStatus();
        return respond(array(
            'allowCount' => Command::runSudo("ufw status | grep 'ALLOW' | wc -l"),
            'denyCount' => Command::runSudo("ufw status | grep 'DENY' | wc -l")
        ));
    }

    public function allowPort(){
        $this->getUFWStatus();
        $portNum = parsePort(request('port'));
		$result = Command::runSudo('ufw allow '.$portNum.' 2>/dev/null 1>/dev/null && echo 1 || echo 0');
        if($result == 1){
            return respond(__('The listening port has been allowed'));
        }
        return respond(__('The listening port could not be allowed!'), 201);
    }

    public function denyPort(){
        $this->getUFWStatus();
        $portNum = parsePort(request('port'));
        $result = Command::runSudo('ufw deny '.$portNum.' 2>/dev/null 1>/dev/null && echo 1 || echo 0');
        if($result == 1){
            return respond(__('The listening port has beed denied'));
        }
        return respond(__('The listening port could not be denied!'), 201);
    }

    public function addCustomRule(){
        $this->getUFWStatus();
		$result = Command::runSudo('ufw '. trim(request('ufwCmd')).' 2>/dev/null 1>/dev/null && echo 1 || echo 0');
        if($result == 1){
            return respond(__('New firewall rule has beed added'));
        }
        return respond(__('The firewall rule could not be added!'), 201);
    }

    public function deleteRule(){
		$result = Command::runSudo('echo y | sudo ufw delete "'.trim(request('ruleID')).'" 2>/dev/null 1>/dev/null && echo 1 || echo 0');
        if($result == 1){
            return respond(__('The firewall rule has been deleted'));
        }
        return respond(__('The firewall rule could not be deleted!'), 201);
    }

    public static function checkStatus($port, $ipVersion, $protocol){
		$ip = "-v '(v6)'";
		if(strtolower($ipVersion) == 'ipv6'){
			$ip = "'(v6)'";
		}

		$check = (bool) Command::runSudo("ufw status | grep 'ALLOW' | grep 'Anywhere' | grep '@{:servicePort}' | grep ".$ip." 2>/dev/null 1>/dev/null && echo 1 || echo 0",[
			'servicePort' => $port
		]);

		$result = -1; //not configured
		if($check){
			$result = 1; //allowed
		}else{
			$check = (bool) Command::runSudo("ufw status | grep 'DENY' | grep 'Anywhere' | grep @{:servicePort} | grep ".$ip." 2>/dev/null 1>/dev/null && echo 1 || echo 0",[
				'servicePort' => $port
			]);
			if($check){ //denied
				$result = 0;
			}
		}

		return $result;
	}

    private function getUFWStatus(){
        $ufwStatus = Command::runSudo('ufw status');
		if (stripos($ufwStatus, 'inactive') === false) {
			return true;
		}
        return abort(__('Firewall is not active!'), 201);
    }

}