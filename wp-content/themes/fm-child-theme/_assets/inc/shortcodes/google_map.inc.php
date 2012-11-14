<?php
// :TODO Make into a Google Maps Wordpress plugin.
function google_map_init( $gmaps_sensor = 'false', $height = '350px' , $width = '350px', $map_id = "fm_map_canvas", $options = array(), $marker_options = array() )
{
	// Google maps API
	// :TODO look into having user submit their own
	define('API_KEY', 'AIzaSyBmQFEYLR43gBrgXJMtDnwgrwZZKbUFI-c');

	// Make sure the user has at the very least supplied the center: LatLng values.
	if ($options['center'] == 'new google.maps.LatLng()') {
		// If the user has not then notify them.
		// Adjust width values to account for padding: 10px and setup error styles.
		$width = ((int)$width - 20) . "px";
		$height = ((int)$height - 20) . "px";
		$style_values = "width:$width;
										height:$height;
										margin:0;
										padding:0;
										background-color:#fafafa;
										padding: 10px;";
		echo "<h2 style=\"$style_values\">Please enter a valid center latitude and longitude location.<br><span style=\"font-size: 16px;\">Example: <br>[fm_google_map center=\"-34.397, 150.644\"]</span></h2>";
	} else {
		// Load Google Maps API
		// $gmaps_sensor indicates whether this application uses a sensor (such as a GPS locator) to determine the user's location.
		echo '<script type="text/javascript"src="http://maps.googleapis.com/maps/api/js?key='.API_KEY.'&sensor='.$gmaps_sensor.'"></script>';

		// Echo out the google map container with selected size settings.
		// :TODO add more options aka add class or classes
		echo '<div id="fm_map_canvas" class="fm_map" style="width: '.$width.'; height: '.$height.'"></div>';

		// Once map is created set the map options
		google_map_set_opts( $options, $map_id, $marker_options );
	} // if/else($options['center']=='new google.maps.LatLng()')
} //google_map_init($gmaps_sensor = 'false')

// :TODO Find a better way to handle the options setting???
function google_map_set_opts( $opts = array(), $map_id, $marker_options )
{
	// :TODO Add more options to short code, it works but could be better.
	/* Available Options and an example
	*
	* Center LatLng	The initial Map center. Required.
	* :TODO Look into setting by address
	* Center Example: center: new google.maps.LatLng(-34.397, 150.644)
	* =======================================================================
	* Zoom number	The initial Map zoom level. Required.
	* Zoom Example: zoom: 8
	* =======================================================================
	* Map Type 	MapTypeId	The initial Map mapTypeId. Required.
	* Map Type Example: mapTypeId: google.maps.MapTypeId.ROADMAP
	* Map Type Options:
	* ROADMAP displays the normal, default 2D tiles of Google Maps.
	*	SATELLITE displays photographic tiles.
	*	HYBRID displays a mix of photographic tiles and a tile layer for prominent features (roads, city names).
	*	TERRAIN displays physical relief tiles for displaying elevation and water features (mountains, rivers, etc.).
	* =======================================================================
	*
	*/

	$map_options = '';
	// Check if we have an options to work with...
	if ( count($opts) ) {
		// :TODO make a more intelligent way to handle missing options especially center, maybe even throw an error or maybe just handle it in the Wordpress back end.

		// ...if so then loop through and turn options into a string separated by , ...
		foreach ($opts as $opt_key => $opt_value) {
			$map_options .= trim($opt_key).': '.trim($opt_value).',';
		} // foreach()

		// Check if required Google Maps parameters have been given if not set some defaults
		if ( stripos( $map_options, 'zoom:') === false ) {
			// Set sensible default value for zoom.
			$map_options .= 'zoom: 8,';
		} // if(!stripos($map_options,'zoom:'))

		if ( stripos($map_options, 'mapTypeId:') === false ) {
			// Set sensible default value for mapTypeId.
			$map_options .= 'mapTypeId: google.maps.MapTypeId.ROADMAP,';
		} // if(!stripos($map_options,'mapTypeId:'))

		// Remove the last comma from map_options.
		$map_options = substr_replace($map_options, '', strrpos($map_options, ','));
	} else {
		// ...if we have no options to work with then set sensible defaults.
		$map_options .= 'center: new google.maps.LatLng(-34.397, 150.644), zoom: 8, mapTypeId: google.maps.MapTypeId.ROADMAP';
	}// if/else (count($opts))
	echo '<script>
		var mapOptions = { '.$map_options.' }, map = new google.maps.Map(document.getElementById("'.$map_id.'"), mapOptions);';
	google_map_set_marker( $marker_options );
	echo '</script>';
} // google_map_set_opts()

function google_map_set_marker( $marker_opts = array() )
{
	// :TODO Create marker icon choices feature for Google Map.
	// :TODO Setup pop for marker.
	/*
	* animation animation DROP/BOUNCE
	* position LatLng *required
	* title string Rollover text
	*/
	extract( $marker_opts );
	if ( $marker != '' ) {
		echo "var marker = new google.maps.Marker({
				    position: $marker,
				    map: map,
				    title:'$marker_title'
				  });";
	} // if($marker!='')
} // google_map_set_marker()
