<?php
	$rfid_uid=$_POST["rfid_uid"];
	$Write="<?php $" . "rfid_uid='" . $rfid_uid . "'; " . "echo $" . "rfid_uid;" . " ?>";
	file_put_contents('rfidContainer.php',$Write);
?>
