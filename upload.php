<?php
	$engine = "a.out";
	$IP = "143.89.152.171";
	
	$data = substr($_POST['data'], strpos($_POST['data'], ",") + 1);
	$decodedData = base64_decode($data);
	$filename = $_POST['fname'];

	$fp = fopen($filename, 'wb');
	fwrite($fp, $decodedData);
	fclose($fp);

	echo translateFile($filename, $engine, $IP);

	function translateFile($_filename, $_engine, $_IP) {
		$cmd = "./" . $_engine . " " . $_IP . " " . $_filename;

		exec($cmd, $output, $result);

		return $result;
	};
?>




