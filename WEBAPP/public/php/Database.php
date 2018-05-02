<?php

class Database{

	// database name
	private $dbName;

	// database login
	private $dbLogin;

	// database passwd
	private $dbPasswd;


	/*
	 *
	 *	Database constructor get connection info
	 *
	 */
	function Database($dbName, $dbLogin, $dbPasswd){

		$this->dbName = $dbName;
		$this->dbLogin = $dbLogin;
		$this->dbPasswd = $dbPasswd;

	}


	/*
	 *
	 *	Make the connection with the database
	 *
	 */
	function getHandler(){
		try{

			// Create connection with the database
			$handler = new PDO('mysql:host=127.0.0.1;dbname='.$this->dbName, $this->dbLogin, $this->dbPasswd);
			$handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch(PDOException $e){

			echo $e->getMessage();
			die();

		}
		return $handler;
		
	}


	/*
	 *
	 *	Select in Database 
	 *	arg[0] : table
	 *	arg[1] : column
	 *	if arg[2] : condition1
	 *	if arg[3] : condition2
	 *
	 */
	function select(){

		$j=0;
		$private = "";
		$handler = $this->getHandler();

		// if no condition
		if(func_num_args() == 2){

			$query = $handler->query('SELECT '.func_get_arg(1).' FROM '.func_get_arg(0));
			
		}
		//with conditions
		else if (func_num_args() == 4){

			$query = $handler->query('SELECT '.func_get_arg(1).' FROM '.func_get_arg(0).' WHERE '.func_get_arg(2).'="'.func_get_arg(3).'"');
			
		}
		//with more conditions
		else if (func_num_args() == 6){

			$query = $handler->query('SELECT '.func_get_arg(1).' FROM '.func_get_arg(0).' WHERE '.func_get_arg(2).'="'.func_get_arg(3).'" AND '.func_get_arg(4).'="'.func_get_arg(5).'"');
			
		}
		else if (func_num_args() == 8){

			$query = $handler->query('SELECT '.func_get_arg(1).' FROM '.func_get_arg(0).' WHERE '.func_get_arg(2).'="'.func_get_arg(3).'" AND '.func_get_arg(4).'="'.func_get_arg(5).'" AND '.func_get_arg(6).'="'.func_get_arg(7).'"');
			
		}
		else if (func_num_args() == 14){

			$query = $handler->query('SELECT '.func_get_arg(1).' FROM '.func_get_arg(0).' WHERE '.func_get_arg(2).'="'.func_get_arg(3).'" AND '.func_get_arg(4).'="'.func_get_arg(5).'" AND '.func_get_arg(6).'="'.func_get_arg(7).'" AND '.func_get_arg(8).'="'.func_get_arg(9).'" AND '.func_get_arg(10).'="'.func_get_arg(11).'" AND '.func_get_arg(12).'="'.func_get_arg(13).'"');
			
		}
		// Otherwise not enough arguments
		else{

			exit("SELECT NB ARGS ERROR");

		}

		while($r = $query->fetch()){

			$private[$j]=$r[func_get_arg(1)];
			$j++;

		}

		$handler = null;
		return $private;

	}


	/*
	 *
	 *	Insert into database
	 *	arg[0] : table
	 *	arg[1...9] : values
	 *
	 */
	function insertInto(){

		$handler = $this->getHandler();

		// Popular cases : insert into deviceConnected, Device, Application or app_tmp
		// Here is deviceConnected
		if(func_num_args() == 9){
			$sql = "INSERT INTO ".func_get_arg(0)." VALUES ('".func_get_arg(1)."', '".func_get_arg(2)."', '".func_get_arg(3)."', '".func_get_arg(4)."', '".func_get_arg(5)."', '".func_get_arg(6)."', '".func_get_arg(7)."', '".func_get_arg(8)."')";
		}
		else if(func_num_args() == 8){
			$sql = "INSERT INTO ".func_get_arg(0)." VALUES ('".func_get_arg(1)."', '".func_get_arg(2)."', '".func_get_arg(3)."', '".func_get_arg(4)."', '".func_get_arg(5)."', '".func_get_arg(6)."', '".func_get_arg(7)."')";
		}
		else if(func_num_args() == 7){
			$sql = "INSERT INTO ".func_get_arg(0)." VALUES ('".func_get_arg(1)."', '".func_get_arg(2)."', '".func_get_arg(3)."', '".func_get_arg(4)."', '".func_get_arg(5)."', '".func_get_arg(6)."')";
		}
		else if(func_num_args() == 6){
			$sql = "INSERT INTO ".func_get_arg(0)." VALUES ('".func_get_arg(1)."', '".func_get_arg(2)."', '".func_get_arg(3)."', '".func_get_arg(4)."', '".func_get_arg(5)."')";
		}
		// Here is device
		else if(func_num_args() == 5){
			$sql = "INSERT INTO ".func_get_arg(0)." VALUES ('".func_get_arg(1)."', '".func_get_arg(2)."', '".func_get_arg(3)."', '".func_get_arg(4)."')";
		}
		// Here is app_tmp
		else if(func_num_args() == 4){
			$sql = "INSERT INTO ".func_get_arg(0)." VALUES ('".func_get_arg(1)."', '".func_get_arg(2)."', '".func_get_arg(3)."')";
		}
		// Here is Application
		else if(func_num_args() == 3){
			$sql = "INSERT INTO ".func_get_arg(0)." VALUES ('".func_get_arg(1)."', '".func_get_arg(2)."')";
		}
		// Here is Application
		else if(func_num_args() == 2){
			$sql = "INSERT INTO ".func_get_arg(0)." VALUES ('".func_get_arg(1)."')";
		}
		else{
			exit("\nINSERT NB ARGS ERROR,  you have ".func_num_args()." arguments\n");
		}

		$handler->query($sql);

		$handler = null;

	}


	/*
	 *
	 *  Delete from $table database with column = value
	 *
	 */
	function delete($table, $column, $value){

		$handler = $this->getHandler();

		$sql = "DELETE FROM ".$table." WHERE ".$column."=\"".$value."\"";
		$handler->query($sql);

		$handler = null;

	}


	/*
	 *
	 *  Truncate table in Database
	 *
	 */
	function truncate($table){

		$handler = $this->getHandler();

		$sql = "TRUNCATE TABLE ".$table;
		$handler->query($sql);

		$handler = null;

	}


	/*
	 *
	 *  Update values
	 *	arg[0] = table
	 *	arg[1] = column
	 *	arg[2] = value
	 *	arg[3...7] = conditions
 	 *
	 */
	function setValue(){

		$handler = $this->getHandler();

		if(func_num_args() == 9){
			$query = $handler->query("UPDATE ".func_get_arg(0)." SET ".func_get_arg(1)." = '".func_get_arg(2)."' WHERE ".func_get_arg(3)." = '".func_get_arg(4)."' and ".func_get_arg(5)." = '".func_get_arg(6)."' and ".func_get_arg(7)." = '".func_get_arg(8)."'");
		}
		// Here is deviceConnected
		else if(func_num_args() == 7){
			$query = $handler->query("UPDATE ".func_get_arg(0)." SET ".func_get_arg(1)." = '".func_get_arg(2)."' WHERE ".func_get_arg(3)." = '".func_get_arg(4)."' and ".func_get_arg(5)." = '".func_get_arg(6)."'");
		}
		// Here is deviceConnected
		else if(func_num_args() == 5){
			$query = $handler->query("UPDATE ".func_get_arg(0)." SET ".func_get_arg(1)." = '".func_get_arg(2)."' WHERE ".func_get_arg(3)." = '".func_get_arg(4)."'");
		}
		// Here is device
		else if(func_num_args() == 3){
			$query = $handler->query("UPDATE ".func_get_arg(0)." SET ".func_get_arg(1)." = '".func_get_arg(2)."'");
		}
		else{
			exit("UPDATE NB ARGS ERROR");
		}

		$handler = null;

	}


	/*
	 *
	 *  Count nb of lines
	 *
	 */
	function count(){

		$j=0;
		$private = "";
		$handler = $this->getHandler();

		$query = $handler->query('SELECT count('.func_get_arg(1).') FROM '.func_get_arg(0).' WHERE '.func_get_arg(2).' = "'.func_get_arg(3).'"');

		while($r = $query->fetch()){

			$private[$j]=$r["count(*)"];
			$j++;

		}
		
		$handler = null;
		return $private[0];

	}


	/*
	 *
	 *  Get max index from table
	 *
	 */
	function getMaxIndex($table){

		$j=0;
		$private = "";
		$handler = $this->getHandler();

		$query = $handler->query('SELECT MAX(id) FROM '.$table);

		while($r = $query->fetch()){

			$private[$j]=$r["MAX(id)"];
			$j++;

		}

		$handler = null;
		return $private[0];

	}


	/*
	 *
	 *  Execute exact sql request
	 *
	 */
	function sqlRequest($request, $column){

		$j=0;
		$private = "";
		$handler = $this->getHandler();

		$query = $handler->query($request);

		while($r = $query->fetch()){

			$private[$j]=$r[$column];
			$j++;

		}

		$handler = null;
		
		if(count($private) == 1 && $private == ""){

			$private = [];

		}
		return $private;

	}

	 function sqlRequestInsert($request){

		$handler = $this->getHandler();

		$query = $handler->query($request);

		$handler = null;

 	}



}


?>