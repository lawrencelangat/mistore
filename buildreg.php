<?php 
$error = "";
    if(isset($_POST['submitroom']))
    {
        $name = $_POST['name'];
        $plotno = $_POST['plotno'];
        $image = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $location = $_POST['locate'];
		$room = $_POST['room'];
		
		

        move_uploaded_file($image_tmp,"upload_images/$image");

        $con = mysqli_connect("localhost","root","","mistore");

        $query = "insert into building(name,plotno,nofrooms,location,buildingimage) values ('$name','$plotno','$room','$location','$image')";

        $result = mysqli_query($con, $query);

        if($result==1)
        {       

        $error .='<p class="alert alert-success">Building Added succesfully</p>';
        
        }
        else {       

         $error .='<p class="alert alert-danger">Building Addition failed. Try Again</p>';

             }
    }
?>