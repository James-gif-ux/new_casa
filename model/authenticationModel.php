<?php
	//import database connector
	require_once 'server.php';
	
	//-------------------------------//
	//--class for login page active--//
	//-------------------------------//
	class authenticationModel extends Connector{
		function __construct(){
			parent::__construct();
		}
		
		//-------------------------------//
		//--  function starts here      --//
		function loggedin(){
            $sql = "SELECT * FROM `admin_tb` WHERE admin_username = ? and admin_password = ?";
            $query = $this->getConnection()->prepare($sql);
            
            // Bind the parameters
            $query->bindParam(1, $_POST['username']);
            $query->bindParam(2, $_POST['password']);
        
            //execute query
            $query->execute();
            //return
            return $query->fetch(PDO::FETCH_ASSOC);
        }

		
        
	 }
?>