<?php 

include 'inc/functions.php';

if (isset($_GET["id"])) {
	header('Content-Type: application/json');

	if (isset($_GET["fff"])) {
		echo toFFF($_GET["id"]);
	} else {
		echo toGeoJson($_GET["id"]);
	}

} else {
	echo '400 Bad Request';
}

?>
