<?php

class RecupConf{

	// login for database
	private $dbName="dbtest";
	private $dbLogin="root";
	private $dbPasswd="";
	




	/*
	 *
	 *	Get dbName
	 *
	 */
	function getDbName(){

		return $this->dbName;

	}


	/*
	 *
	 *	Get dbLogin
	 *
	 */
	function getDbLogin(){

		return $this->dbLogin;

	}


	/*
	 *
	 *	Get dbPasswd
	 *
	 */
	function getDbPasswd(){

		return $this->dbPasswd;

	}

}

?>
