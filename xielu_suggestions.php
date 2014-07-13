<?php

require_once('./workflows.php');

function fetch_suggestions_for_query( $query ) {
    $encoded_query = urlencode( $query );
    $json_string = file_get_contents("https://xie.lu/complete/search?client=hp&hl=en&gs_rn=48&gs_ri=hp&cp=4&gs_id=m&q={$encoded_query}&xhr=t");
    if( !$json_string ){
        return null;
    }
    $result = json_decode($json_string, true);
    $suggestions = $result[1];
    return $suggestions;
}

function wrap_suggestions_to_xml( $suggestions, $query ){
	
	$workflows = new Workflows();

	$workflows->result('-1_'.time(), $query, $query, "去 xie.lu 搜索 $query", "./icon.png");

	foreach( $suggestions as $key => $suggestion ){
		$suggestion_word = 
	html_entity_decode( strip_tags( $suggestion[0] ) );
		if( $suggestion_word == $query ){
			continue;
		}
	    $workflows->result($key . "_" . time(), $suggestion_word, $suggestion_word, "去 xie.lu 搜索 $suggestion_word", "./icon.png");
	}

	return $workflows->toxml();
}