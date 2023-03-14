<?php
class Mysql_Driver
{
    /**
     * Connection holds MySQLi resource
     */
    private $connection;

    /**
     * Create new connection to database
     */ 
    public function connect()
    {
          $host = 'localhost';
          $user = 'web_user';
          $password = '@Bc123_dEf!';
          $database = 'ductusCarry'; 
          
          $port = '3306'; 
    
        $this->connection = mysqli_connect($host, $user, $password, $database, $port);
		if (mysqli_connect_errno())
  		{
 		    //echo "Failed to connect to MySQL: " . mysqli_connect_error();
			trigger_error("Failed to connect to MySQL: " . mysqli_connect_error());
  		} 
    }

    public function close()
	{
        mysqli_close($this->connection);     
	}
	/*
    public function query($qry, ...$params)
	{
		$result = "";

		$stmt = mysqli_stmt_init($this->connection);

		if (!mysqli_stmt_prepare($stmt, $qry)) {
			trigger_error("Failed to prepare Stmt Query" . $stmt->error);
			
		} else {
			$stringTypes = "";
			$type = "";

			foreach($params as $param) {
				if (is_string($param)) {
					$type = "s";
				} else if (is_int($param)) {
					$type = "i";
				} else if (is_double($param)) {
					$type = "d";
				}

				$stringTypes .= $type;
			}
			if (sizeof($params)) {
				mysqli_stmt_bind_param($stmt, $stringTypes , ...$params);
			}

			if (!mysqli_stmt_execute($stmt)) {
				trigger_error("Query Failed SQL: $qry - Stmt Error: " . htmlspecialchars($stmt->error));
			}

			if (mysqli_stmt_affected_rows($stmt) > 0) {
				$result = true;
			} else {
				$result = mysqli_stmt_get_result($stmt);
			}
			return $result;
		}
	}
	*/

	public function query($qry)
	{
      	$result = mysqli_query($this->connection,$qry);
		if (!$result) 
			trigger_error("Query Failed! SQL: $qry - Error: " . 
			               mysqli_error($this->connection));
		else
			return $result;
	}
	
	public function num_rows($result)
	{
		return mysqli_num_rows($result);
	}
	
	public function fetch_array($result)
	{
		return mysqli_fetch_array($result);
	}
        
        public function prepare($qry)
        {
            $stmt = $this->connection->prepare($qry);
            if ($this->connection->error_list) {
                print_r($this->connection->error_list);
            }
            return $stmt;
        }
	
        public function fetch_row($result)
        {
            return mysqli_fetch_row($result);
        }
}   
?>
