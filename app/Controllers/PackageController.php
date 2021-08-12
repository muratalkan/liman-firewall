<?php

namespace App\Controllers;

use Liman\Toolkit\OS\Distro;

class PackageController
{

	public static function verify() {
        
		$pckStr = "'ufw' 'lsof'";
		
		$check = (bool) Distro::debian(
			"dpkg -s ".$pckStr." 2>/dev/null 1>/dev/null && echo 1 || echo 0"
		)
			->centos(
				"rpm -q ".$pckStr." 2>/dev/null 1>/dev/null && echo 1 || echo 0"
			)
			->runSudo();

		return $check;
	}

	public function install() {

		return respond(
			view('components.task', [
				'onFail' => 'onTaskFail',
				'onSuccess' => 'onTaskSuccess',
				'tasks' => [
					0 => [
						'name' => 'InstallPackage',
						'attributes' => []
					]
				]
			]),
			200
		);
	}

}
