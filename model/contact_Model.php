<?php
	//import database connector
	require_once 'server.php';
	
	class ContactModel extends Connector{
		private $connection;

		function __construct(){
			parent::__construct();
			$this->connection = $this->getConnection();
		}
		
		function contact(){
			//create a query
			$sql = "SELECT * FROM contact_tb";
			
			//prepare query
			$query = $this->connection->prepare($sql);
			//execute query
			$query->execute();
			//return
			return $query->fetch(PDO::FETCH_ASSOC);
		}
	}
?>