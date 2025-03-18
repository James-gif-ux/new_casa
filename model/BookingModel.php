<?php
require_once 'server.php';


    class BookingModel extends Connector{
        function __construct(){
            parent::__construct();
        }

        function add_Service($service, $file){
            	//image location
			$target_dir = "../images/";
			$target_file = $target_dir . basename($file["image"]["name"]);
			$filename = basename($file["image"]["name"]);
			
			$imageFileType = ['jpg','jpeg','png','svg','gif','webp'];
			
			//check te file if it is an image
			$extension = pathinfo($file["image"]["name"], PATHINFO_EXTENSION);
			
			//check if the image is on the list of images formats
			if (in_array($extension,$imageFileType)){
				
				//upload the correct image
				if (move_uploaded_file($file["image"]["tmp_name"], $target_file)) {
					//pre sql statement
                    $sql = "INSERT INTO `services_tb`(`services_name`,`services_image`,`services_description`,`services_price`) VALUES (?, ?, ?, ?)";

					$stmt = $this->getConnection()->prepare($sql);
        
                    $stmt->bindParam(1, $service['name']);
                    $stmt->bindParam(2,$filename);
                    $stmt->bindParam(3, $service['desc']);
                    $stmt->bindParam(4, $service['prices']);
        
                    $stmt->execute();
					
				}
				else{
					?>
						<script>alert('There was an error on file upload!');</script>
						<script>window.history.back(-1);</script>
					<?php
				}
			}else{
				?>
					<script>alert('Invalid File Format.\r\nFormat: jpg,jpeg,png,svg,gif extention:'<?= $extension ?>);</script>
					<script>window.history.back(-1);</script>
				<?php
			}
			
       
        }
		function service_delete($service_delete){
			$sql = "DELETE FROM `services_tb` WHERE `services_id` = ?";
			$stmt = $this->getConnection()->prepare($sql);
			$stmt->bindParam(1, $service_delete['services_id']);
			$stmt->execute();
			$stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		

        function get_service(){
            $sql = "SELECT * FROM `services_tb` ";
			//prepare query
			$stmt = $this->getConnection()->prepare($sql);
			
			//execute query
			$stmt->execute();
			//return
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

		function get_images(){
            $sql = "SELECT * FROM `image _tb` ";
			//prepare query
			$stmt = $this->getConnection()->prepare($sql);
			
			//execute query
			$stmt->execute();
			//return
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

		function service_update(){
			try{
				$query = "UPDATE `services_tb` SET `services_name` = '{$_POST['services_name_input']}', `services_description` = '{$_POST['services_description_input']}' WHERE `services_id` = '{$_POST['services_id']}'";
	
				$stmt = $this->getConnection()->prepare($query);
	
				$stmt->execute();
	
				return true;
				} catch(Throwable $e){
					$e->getMessage();
					return false;
				}

		}
	}
    


?>