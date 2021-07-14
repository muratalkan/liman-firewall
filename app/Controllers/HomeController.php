<?php
namespace App\Controllers;

class HomeController
{
	public function index()
	{
		return view('index');
	}

	function install()
	{
		return view('pages.install');
	}

}