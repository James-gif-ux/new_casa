<?php
	//import model
	include_once '../model/Booking_Model.php';

	$page_info['page'] = 'admin_rooms'; //for page that needs to be called
	$page_info['sub_page'] = isset($_GET['sub_page'])? $_GET['sub_page'] : 'admin_rooms'; //for function to be loaded
		
	
	try {//used try to catch unfortunate errors
		//check for active function
		
		//no active function, use the default page to view
		new admin_rooms($page_info);
		
	}catch (Throwable $e){ //get the encountered error
		echo '<h1>ERROR 404</h1>';
		echo $e->getMessage();
	}//end of validation
	
	
	//-----------------------//
	//--  Class Navigation --//
	//-----------------------//
	class admin_rooms{
		//set default page info
		private $page = '';
		private $sub_page = '';
		
		//run function construct
		function __construct($page_info){
			//assign page info
			$this->page = $page_info['page'];
			$this->sub_page = $page_info['sub_page'];
			
			//run the function
			$this->{$page_info['sub_page']}();
		}
		
		//-----------------------------//
		//--   function start here   --//
		function admin_rooms(){
			$rooms= new Booking_Model();
			$services = $rooms->add_services();
			include '../views/admin_rooms.php';
		}
        
        // Add the missing method
        function add_services(){
            $rooms = new Booking_Model();
            $services = $rooms->add_services();
            return $services;
        }
	}
	//-----------------------//
	//-- Active Class      --//
	//-----------------------//
	class admin_roomsActive{
		//set default page info
		private $page = '';
		private $sub_page = '';
		//run function construct
		function __construct($page_info){
			//assign page info
			$this->page = $page_info['page'];
			$this->sub_page = $page_info['sub_page'];
			//run the function
			$this->{$page_info['sub_page']}();
			}
			//-----------------------------//
			//-- function start here--//
			function add_services(){
				$rooms= new Booking_Model();
				$services = $rooms->add_services();

			}
		}
?>