<?php
	//import model

	$page_info['page'] = 'admin'; //for page that needs to be called
	$page_info['sub_page'] = isset($_GET['sub_page'])? $_GET['sub_page'] : 'admin'; //for function to be loaded
		
	
	try {//used try to catch unfortunate errors
		//check for active function
		
		//no active function, use the default page to view
		new admin($page_info);
		
	}catch (Throwable $e){ //get the encountered error
		echo '<h1>ERROR 404</h1>';
		echo $e->getMessage();
	}//end of validation
	
	
	//-----------------------//
	//--  Class Navigation --//
	//-----------------------//
	class admin{
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
		function admin(){
			include '../views/admin.php';
		}
		function admin_booking(){
			include '../views/admin_booking.php';
		}
		function reservedBooking(){
			include '../views/reservedBooking.php';
		}
        function admin_rooms(){
			include '../views/admin_rooms.php';
		}
		function admin_food(){
			include '../views/admin_food.php';
		}
		function admin_inquires(){
			include '../views/admin_inquires.php';
		}
		function admin_payments(){
			include '../views/admin_payments.php';
		}
		function admin_reports(){
			include '../views/admin_reports.php';
		}
		function process(){
			include '../views/process.php';
		}
	}
?>