<?php
	//import model
    require_once '../model/Booking_Model.php';
	$page_info['page'] = 'admin_bookings'; //for page that needs to be called
	$page_info['sub_page'] = isset($_GET['sub_page'])? $_GET['sub_page'] : 'admin_bookings'; //for function to be loaded
		
	
	try {//used try to catch unfortunate errors
		//check for active function
		
		//no active function, use the default page to view
		new admin_bookings($page_info);
		
	}catch (Throwable $e){ //get the encountered error
		echo '<h1>ERROR 404</h1>';
		echo $e->getMessage();
	}//end of validation
	
	
	//-----------------------//
	//--  Class Navigation --//
	//-----------------------//
	class admin_bookings{
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
		function admin_bookings(){
            $booking = new Booking_Model();
            $bookings = $booking->get_bookings();
			include '../views/admin_bookings.php';
		}
        
	}

    class adminActive{
        private $page = '';
        private $sub_page = '';

        //run function construct
        function __construct($page_info){

            //assign page info
            $this->page = $page_info['page'];
            $this->sub_page = $page_info['sub_page'];
            //run the function
            $this->{$page_info['sub_page']}();

            //-----------------------------//
            //--   function start here   --//
            function bookings(){
               $booking = new Booking_Model();
                $bookings = $booking->get_bookings();
                include '../views/admin_bookings.php';
            }
        }
    }
?>