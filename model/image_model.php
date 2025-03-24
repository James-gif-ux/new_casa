<?php
	//import database connector
	require_once 'server.php';
	
	//-------------------------------//
	//--class for login page active--//
	//-------------------------------//
	class image_model extends Connector{
		function __construct(){
			parent::__construct();
		}
		
		//-------------------------------//
		//--  function starts here      --//
		function getimages(){
            $sql = "SELECT * FROM `image_tb`";
            $query = $this->getConnection()->prepare($sql);
            $current_image = $query->fetchColumn();
            // Check if a new image is uploaded
            if (!empty($_FILES['image_img']['name'])) {
                $image = $_FILES['image_img']['name'];
                $target = "../images/" . basename($image);
                // Delete old image if exists
                if ($current_image && file_exists("../images/" . $current_image)) {
                    unlink("../images/" . $current_image);
                }
                move_uploaded_file($_FILES['image_img']['tmp_name'], $target);
            } else {
                $image = $current_image;
            }
            $query->execute();
            //return
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
        
        function addimages() {
             // Check if required fields are set
             if (!isset($_POST['image_name']) || empty($_POST['image_name'])) {
                // Handle the error - maybe return false or throw an exception
                return false;
            }
           // Check if required fields are set
           if (!isset($_POST['image_img']) || empty($_POST['image_img'])) {
            // Handle the error - maybe return false or throw an exception
            return false;
        }
       
    
            $sql = "INSERT INTO `image_tb` (`image_id`, `image_name`, `image_img`, `image_description`, `image_price`) VALUES (?, ?, ?, ?, ?)";
            $query = $this->getConnection()->prepare($sql);
        
            // Bind the parameters
            $query->bindParam(1, $_POST['image_id']);
            $query->bindParam(2, $_POST['image_name']);
            $query->bindParam(3, $_POST['image_img']);
            $query->bindParam(4, $_POST['image_description']);
            $query->bindParam(5, $_POST['image_price']);
            $current_image = $query->fetchColumn();
            // Check if a new image is uploaded
            if (!empty($_FILES['image_img']['name'])) {
                $images = $_FILES['image_img']['name'];
                $target = "../images/" . basename($images);
                // Delete old image if exists
                if ($current_image && file_exists("../images/" . $current_image)) {
                    unlink("../images/" . $current_image);
                }
                move_uploaded_file($_FILES['image_img']['tmp_name'], $target);
            } else {
                $images = $current_image;
            }
            //execute query
            $query->execute();
            //return
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
	 }
?>