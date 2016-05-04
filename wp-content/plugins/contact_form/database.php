<?php

Class DB {

	private $dbhost;
	private $dbuser;
	private $dbpass;
	private $dbname;
	private $conn;
	const SELECT = 'SELECT';
	const INSERT = 'INSERT';
	const DELETE = 'DELETE';
	const UPDATE = 'UPDATE';

	public function __construct($dbhost , $dbuser, $dbpass, $dbname, $connect = true){
		$this->dbhost = $dbhost;
		$this->dbuser = $dbuser;
		$this->dbpass = $dbpass;
		$this->dbname = $dbname;
		if($connect){
			$this->connect();
		}
	}
	
	public function connect(){
		// Create connection
		$this->conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
		
		// Check connection
		if ($this->conn->connect_error) {
		   	$errorMessage = "Connection failed: ".$this->conn->connect_error;
		   	//writing error message into log file
		    $this->error_log_file($errorMessage);
		    die("Connection failed:");
		}
		else{
			$this->conn->set_charset('utf8');
		}

		return $this->conn;
	}

	public function disconnect(){
	    if($this->conn){
	    	$this->conn->close();
	    }
	}
	public function custom_sql_select($sql){
		$data_result = array();
		$result = $this->conn->query($sql);
	    if ($result->num_rows > 0) {
		    // output data of each row
		    while($row = $result->fetch_assoc()) {
		        array_push($data_result, $row);
		    }
		    return $data_result;
		} 
		else {
		    echo "0 results";
		    return false;
		}
	}

	/*	
		SELECT Record from table 
		@params (string, array, array, array)
		return array or false
	*/
	public function select($table_name, $rows = array('*'), $where = null, $order = null ){
				
		$data = $this->testData($rows);
		
		if($data){
			$escaped_values = $this->arrayToString($data, false);  
			
	        $select_data  = implode(", ", $escaped_values);
	       	$prepareData = '';   
	        $sql = 'SELECT '.$select_data.' FROM '.$table_name;
		    if($where != null){
		    	$param_type = $this->getParsingDataType($where);
		    	$prepareData = $this->getPrepareData($param_type, $where);
		        $sql .= ' WHERE '.$this->formatWhereSQL($where);
		    }
		    if($order != null){
		        $sql .= ' ORDER BY '.$this->formatOrderSQL($order);
		    }
		    if($where != null){
		   		return $this->makePrepareOperations($prepareData, $sql, self::SELECT);
			}
			else{
				return $this->custom_sql_select($sql);
			}
		}
		else{
			echo 'invalid parsing select data must be array';
			return false;
		}
	}

	/*
		INSERT INTO DB
		@params(string, object/array);
		return last id of recort or false
	*/
	public function insert($table_name, $object){
		$data = $this->testData($object);
		if($data){
			$param_type = $this->getParsingDataType($data);
			if($param_type){
				//true parammeter is for enabled adding dittos
				$escaped_values = $this->arrayToString($data, false);
				
		        $columns_data = implode(", ", array_keys($escaped_values));
		        //$values_data  = implode(", ", $escaped_values);
		        // number os prepare variables as ?,?,? ... 3 variables 
			   	$unknownData = $this->getUnknowValues($escaped_values);  
			   	$prepareData = $this->getPrepareData($param_type, $escaped_values);

			   	$sql = "INSERT INTO $table_name ($columns_data) VALUES ($unknownData)";
				//now we need to add references for using call user func array
				return $this->makePrepareOperations($prepareData, $sql, self::INSERT);	    
			}
			else{
				echo 'bad data type';
				return false;
			}
		}
		else{
			echo 'invalid parsing insert data must be object or array';
			return false;
		}
	}

	/*
		DELETE FROM DB
		@params(string,array)
		return bool
	*/
	public function delete($table_name, $where){

		$param_type = $this->getParsingDataType($where);
		$prepareData = $this->getPrepareData($param_type, $where);

		// sql to delete a record
		$sql = "DELETE FROM $table_name WHERE ".$this->formatWhereSQL($where);
		
		return $this->makePrepareOperations($prepareData, $sql, self::DELETE);
	}

	/*
		UPDATE Record in DB
		@params(string, array, array)
		return bool
	*/
	public function update($table_name, $set ,$where){
		
		$param_type = $this->getParsingDataType($set);
		$param_type .= $this->getParsingDataType($where);

		$prepareData = $this->getPrepareData($param_type, $set);
		foreach ($where as $value) {
			array_push($prepareData, $value);
		}
		
		// sql update to a record
		$sql = "UPDATE $table_name SET ".$this->formatSetSQL($set)."  WHERE ".$this->formatWhereSQL($where);

		return $this->makePrepareOperations($prepareData, $sql, self::UPDATE);
	}

	private function error_log_file($message){
		$logMessage = date('c').' '.$message. "\n";
		$logFile = fopen("error_log.txt", "a") or die("Unable to open file!");
		fwrite($logFile, $logMessage);
		fclose($logFile);
	}

	public function objToArray ($object) {
	    $clone = (array) $object;
	    $array_object = array();
	    while ( list ($key, $value) = each ($clone) ) {
	        $aux = explode ("\0", $key);
	        $newkey = $aux[count($aux)-1];
	        $array_object[$newkey] = $value;
	    }
    	return $array_object;
	}

	public function arrayToString($data, $dittos){
		$escaped_values = array();
		foreach ($data as $idx=>$value) {
			if($dittos){
				$escaped_values[$idx] = "'".$value."'";
        	}
        	else{
        		$escaped_values[$idx] = $value;
        	}
        }
        return $escaped_values;
	}

	//testing data if are object, array or something else
	public function testData($object){
		if(is_object($object)){
			return $this->objToArray($object);
		}
		if(is_array($object)){
			return $object;
		}
		return false;
	}

	//it shoud be adding also  'b' type for byte type or long data type
	public function getDataType($var){
        if (is_float($var)) {return "d";}	//double
        if (is_int($var)) {return "i";}		//integer	
        if (is_string($var)) {return "s";}	//string
        return false;
	}

	//parsing strings data types as parammeter for bind_param() function
	public function getParsingDataType($data){
		$dataTypes = array();
	    foreach ($data as $value) {
	      	$dataType = $this->getDataType($value);
	       	if($dataType){
	       		array_push($dataTypes, $dataType);
	       	}
	       	else{
	       		return false;
	      	}
	    }
	    return implode("", $dataTypes);
	}

	// parsing string for unkonw value as ?
	public function getUnknowValues($array){
		$value_unknow = array();
		foreach ($array as $key) {
		   	array_push($value_unknow, '?');
		}
		return implode(", ", $value_unknow);
	}	

	//prepare data for bind_params function
	public function getPrepareData($parammeters, $dataArray){
		$prepareArray = array();
	  	array_push($prepareArray, $parammeters);
	   	foreach ($dataArray as $key => $value) {
	   		array_push($prepareArray, $value);
	   	}
	   	return $prepareArray;
	}

	/*
		function for dynamic using bind_param function
		It should be protect before SQL inject hacking
	*/
	public function makePrepareOperations($prepareData, $sql, $type){
		
		$tmp = array();
			foreach($prepareData as $key => $value){
			$tmp[$key] = &$prepareData[$key];
		}
		$stmt = $this->conn->prepare($sql);
		
		if($stmt) {
	    	call_user_func_array(array($stmt,'bind_param'),$tmp);
	    	/* Execute statement */
			$stmt->execute();
			switch ($type) {
				case self::SELECT:{
					$result = $stmt->get_result();
					if($result->num_rows > 0){
						$resultData = array();
						while($row = $result->fetch_array(MYSQLI_ASSOC)){
							array_push($resultData, $row);
						}
						$stmt->close();
						//return selected data array 
						return $resultData;
					}
					else{
						$errorMessage = 'Select failed: ' . $sql;
						$this->error_log_file($errorMessage);
						$stmt->close();
						return false;
					}
					break;
				}
				case self::INSERT:{
					if($this->conn->insert_id > 0){
						$stmt->close();
						//return last id inserted record
						return $this->conn->insert_id;
					}
					else{
						$errorMessage = 'Insert failed: ' . $sql;
						$this->error_log_file($errorMessage);
						$stmt->close();
						return false;
					}
					break;
				}
				case self::DELETE:{
					if($this->conn->affected_rows > 0){
						$stmt->close();
						return true;
					}
					else{
						$errorMessage = 'Delete failed: ' . $sql;
						$this->error_log_file($errorMessage);
						$stmt->close();
						return false;
					}
					break;
				}
				case self::UPDATE:{
					if($this->conn->affected_rows > 0){
						$stmt->close();
						return true;
					}
					else{
						$errorMessage = 'Update failed: ' . $sql;
						$this->error_log_file($errorMessage);
						$stmt->close();
						return false;
					}
					break;
				}
				default:
					$stmt->close();
					return false;
					break;
			}
		}
		else{
			$errorMessage = 'Wrong SQL: ' . $sql;
			$this->error_log_file($errorMessage);
			echo('Wrong SQL: ');
			return false;
		}
	}

	//string formating for SET parameter in SQL command
	public function formatSetSQL($set){
		
		$setKeys = array_keys($set);
		$unknownDataSet = array();
		for($i = 0; $i < sizeof($setKeys); $i++){
			if($i<(sizeof($setKeys)-1)){
				array_push($unknownDataSet, $setKeys[$i].' = ?, ');
			}
			else{
				array_push($unknownDataSet, $setKeys[$i].' = ?');
			}
		}
		return implode(' ', $unknownDataSet);
	}	

	//string formating for WHERE parameter in SQL command
	public function formatWhereSQL($where){
		
		$whereKeys = array_keys($where);
		$unknownDataWhere = array();
		for($i = 0; $i < sizeof($whereKeys); $i++){
			if($i<(sizeof($whereKeys)-1)){
				array_push($unknownDataWhere, $whereKeys[$i].' = ?');
				array_push($unknownDataWhere, ' AND ');
			}
			else{
				array_push($unknownDataWhere, $whereKeys[$i].' = ?');
			}
		}
		return implode(' ', $unknownDataWhere);
	}

	//string formating for ORDER parameter in SQL command
	public function formatOrderSQL($order){
		
		$orderData = array();
		$i = 0;
		foreach ($order as $key => $value) {
			if($i<(sizeof($order)-1)){
				array_push($orderData, $key.' '.$value.', ');
			}
			else{
				array_push($orderData, $key.' '.$value);
			}
			$i++;
		}
		return implode(' ', $orderData);
	}
}

?>