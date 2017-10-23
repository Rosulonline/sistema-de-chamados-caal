<?php
	if(isset($_GET['ip'])){
		$ip = $_GET['ip'];
	}else{
		$ip = 'localhost';
	}
	$ping = `ping $ip`;
	echo nl2br($ping);
?>