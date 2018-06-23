<?php
  $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";

	//Check if the file is well uploaded
	if($_FILES['file']['error'] > 0) { echo 'Error during uploading, try again'; }

	//We won't use $_FILES['file']['type'] to check the file extension for security purpose

	//Set up valid image extensions
	$extsAllowed = array( 'jpg', 'jpeg', 'png', 'gif' );

	//Extract extention from uploaded file
		//substr return ".jpg"
		//Strrchr return "jpg"

	$extUpload = strtolower( substr( strrchr($_FILES['file']['name'], '.') ,1) ) ;
	//Check if the uploaded file extension is allowed

	if (in_array($extUpload, $extsAllowed) ) {

	//Upload the file on the server

	$name = "images/{$_FILES['file']['name']}";
	$result = move_uploaded_file($_FILES['file']['tmp_name'], $name);

	//if($result){echo "<img src='$name'/>";}
  if($result){echo $actual_link.'/'.$name;}

	} else { echo 'File is not valid. Please try again'; }

?>
