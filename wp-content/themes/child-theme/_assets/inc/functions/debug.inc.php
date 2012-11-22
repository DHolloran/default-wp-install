<?php
// == Pretty Print Debug ==============================================
function pp( $value ) {
	echo "<pre><code>";
	if ( $value ) {
		print_r( $value );
	} else {
		var_dump( $value );
	} // if/else()
	echo "</code></pre>";
} // pp()
