<?php

function remove_array_item($array, $item ) {
	$index = array_search($item, $array);
	if ( $index !== false ) {
		unset( $array[$index] );
	}

	return $array;
}

function array_count_occurrences($array, $item) {
	$count = 0;
	foreach($array as $x) {
		if ($x == $item) { $count++; }
	}
	return $count;
}
