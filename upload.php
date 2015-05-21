<?php
	
	// pull the raw binary data from the POST array
	$data = substr($_POST['data'], strpos($_POST['data'], ",") + 1);
	// decode it
	$decodedData = base64_decode($data);
	// print out the raw data,
	$filename = $_POST['fname'];
	echo $filename;
	// write the data out to the file
	$fp = fopen($filename, 'wb');
	fwrite($fp, $decodedData);
	fclose($fp);



	//if($_FILES['blob']['error'] > 0) die('Error ' . $_FILES['file']['error']);
	/*$target_dir = "/Applications/XAMPP/htdocs/davos/uploads/";
	$target_file = $target_dir . basename($_FILES["blob"]["name"]);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

	$engine = "engine.out";
	$IP = "143.89.152.171";

	// Check if image file is a actual image or fake image
	// if(isset($_POST["submit"])) {
	//     $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
	//     if($check !== false) {
	//         echo "File is an image - " . $check["mime"] . ".";
	//         $uploadOk = 1;
	//     } else {
	//         echo "File is not an image.";
	//         $uploadOk = 0;
	//     }
	// }
	// Check if file already exists
	if (file_exists($target_file)) {
	    echo "Sorry, file already exists.";
	    $uploadOk = 0;
	}
	// Check file size
	// if ($_FILES["fileToUpload"]["size"] > 500000) {
	//     echo "Sorry, your file is too large.";
	//     $uploadOk = 0;
	// }
	
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
	    echo "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} else {
	    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
	        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.<br>";

	        echo "target file: " . $target_file . "<br>";

	        $translation = translateFile($target_file, $engine, $IP);
	    } else {
	        echo "Sorry, there was an error uploading your file.";
	    }
	}

	function translateFile($_filename, $_engine, $_IP) {
		echo "translating stuff<br>";
		$cmd = "./" . $_engine . " " . $_IP . " " . $_filename;

		echo $cmd . "<br>";
		//./engine.out 143.89.152.171 ../uploads/tommaso.wav
		exec($cmd, $output, $result);

		echo "result: " . $result . "<br>"; 

		return null;
	};*/
?>




