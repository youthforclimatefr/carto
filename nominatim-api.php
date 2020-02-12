<?php

if (isset($_POST['adress'])) {
	$address = rawurlencode( $_POST['adress'] );
	$url  = 'http://nominatim.openstreetmap.org/?format=json&addressdetails=1&q=' . $address . '&format=json&limit=1';
	$opts = array(
		'http'=>array(
			'method'=>"GET",
			'header'=>"User-Agent: YFC-ReseauLycee\r\n" .
				"Referer: youthforclimate.fr\r\n"
		)
	);
	$context = stream_context_create($opts);
	$req = file_get_contents( $url, false, $context);
	$json = json_decode( $req, true );

	$coord['lat']  = $json[0]['lat'];
	$coord['long'] = $json[0]['lon'];

	echo json_encode($coord);

?>

<?php } else { ?>

400 Bad request

<?php } ?>
