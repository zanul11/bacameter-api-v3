<?php
	$nama = $_FILES["foto"]["name"];
	$temp = $_FILES["foto"]["tmp_name"];
	if (move_uploaded_file($temp, "images/".$nama)) {
		echo "SUKSES";
	} else {
		echo "GAGAL ".$temp.' images/'.$nama;
	}

	phpinfo();
?>
