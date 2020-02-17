<?php 

include 'inc/functions.php';

if (isset($_GET["id"])) {
	header('Content-Type: application/javascript');
	echo("var map" . $_GET["id"] . " = " . toGeoJson($_GET["id"]) . ";");

} else {
	echo '400 Bad Request';
}
?>
