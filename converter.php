<?php

// Add our lists.
$lists = array(
	// Spam404
	'Name' => 'URL',

);

foreach ( $lists as $name => $list ) {
	echo "Converting {$name}...\n";

	// Fetch filter list and explode into an array.
	$lines = file_get_contents( $list );
	$lines = explode( "\n", $lines );

	// HOSTS header.
	$hosts  = "# {$name}\n";
	$hosts .= "#\n";
	$hosts .= "# Converted from - {$list}\n";
	$hosts .= "# Last converted - " . date( 'r' ) . "\n";
	$hosts .= "#\n\n";

	// Loop through each ad filter.
	foreach ( $lines as $filter ) {
		// Skip filter if matches the following:
		if ( false === strpos( $filter, '.' ) ) {
			continue;
		}
		if ( false !== strpos( $filter, '*' ) ) {
			continue;
		}
		if ( false !== strpos( $filter, '/' ) ) {
			continue;
		}
		if ( false !== strpos( $filter, '=' ) ) {
			continue;
		}
		if ( false !== strpos( $filter, '#' ) ) {
			continue;
		}
		if ( false !== strpos( $filter, ' ' ) ) {
			continue;
		}
		// Skip exception rules.
		if ( false !== strpos( $filter, '@@' ) ) {
			continue;
		}
		// Replace filter syntax with HOSTS syntax.
		// @todo Perhaps skip $third-party, $image and $popup?
		$filter = str_replace( array( '||', '^', '$third-party', ',third-party', '$image', ',image', '$important', ',important', '$script', ',script', '$object-subrequest', '$object',',object', '$popup', ',popup','$empty', '$subdocument', ',subdocument', '-subrequest', '$websocket', '$media', ',stylesheet', '|' ), '', $filter );

		// Skip rules matching 'xmlhttprequest' for now.
		if ( false !== strpos( $filter, 'xmlhttprequest' ) ) {
			continue;
		}

		// Skip exclusion rules.
		if ( false !== strpos( $filter, '~' ) ) {
			continue;
		}

		// If starting or ending with '.', skip.
		if ( '.' === substr( $filter, 0, 1 ) || '.' === substr( $filter, -1 ) ) {
			continue;
		}

		// Skip commented rules
		if ( false !== strpos( $filter, '!' ) ) {
			continue;
		}
		
		// If starting or ending with ; Custom array
		if ( '_' === substr( $filter, 0, 1 ) || '.' === substr( $filter, -1 ) ) {
			continue;
		}
		if ( ';' === substr( $filter, 0, 1 ) || '.' === substr( $filter, -1 ) ) {
			continue;
		}
		if ( '-' === substr( $filter, 0, 1 ) || '.' === substr( $filter, -1 ) ) {
			continue;
		}
		if ( '' === substr( $filter, 0, 1 ) || '-' === substr( $filter, -1 ) ) {
			continue;
		}

		// Skip weird leftover filtering rules in adguardRU filter
		if ( false !== strpos( $filter, '120x600.' ) ) {
			continue;
		}
		if ( false !== strpos( $filter, '160x600.' ) ) {
			continue;
		}
		if ( false !== strpos( $filter, '468x15.h' ) ) {
			continue;
		}
		if ( false !== strpos( $filter, '468x60a.' ) ) {
			continue;
		}
		if ( false !== strpos( $filter, '468x60.g' ) ) {
			continue;
		}
		if ( false !== strpos( $filter, '468x60.h' ) ) {
			continue;
		}
		if ( false !== strpos( $filter, '468x60.j' ) ) {
			continue;
		}
		if ( false !== strpos( $filter, '468x60.s' ) ) {
			continue;
		}
		if ( false !== strpos( $filter, '728x90.g' ) ) {
			continue;
		}
		if ( false !== strpos( $filter, '728x90.h' ) ) {
			continue;
		}
		if ( false !== strpos( $filter, '728x90.j' ) ) {
			continue;
		}
		if ( false !== strpos( $filter, '728x90.p' ) ) {
			continue;
		}
		if ( false !== strpos( $filter, '728x90.s' ) ) {
			continue;
		}
		if ( false !== strpos( $filter, '768x60.gif' ) ) {
			continue;
		}
		if ( false !== strpos( $filter, 'ban100x100.swf' ) ) {
			continue;
		}

		$hosts .= "0.0.0.0 {$filter}\n";
	}
	// Set output directory 
	$dir = './hosts-list/';
	
	// Create folder 'hosts-list'
    if(!is_dir($dir)){
        $dir_p = explode('/',$dir);
        for($a = 1 ; $a <= count($dir_p) ; $a++){
            @mkdir(implode('/',array_slice($dir_p,0,$a)));  
        }
    }
	
	// Put generated content in 'hosts-list' folder
	file_put_contents( $dir . "{$name}.txt", $hosts );

	echo "{$name} converted to HOSTS file - see {$name}.txt\n";
}
