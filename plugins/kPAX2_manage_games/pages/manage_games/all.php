<?php

// Aquest fitxer no implementa encara res de kPAX2_manage_games
// Però considero que serà útil pel desenvolupament 

	$title = elgg_echo('kpax:all');

	$content = elgg_list_entities(array(
		    'types' => 'object',
		    'subtypes' => 'kpax',
		    'limit' => 10,
		    'full_view' => false,
	));

	if (!$content) {
		$content = '<p>' . elgg_echo('kpax:none') . '</p>';
	}

	$body = elgg_view_layout('content', array(
			'filter_context' => 'play',
			'content' => $content,
			'title' => $title,
			'sidebar' => elgg_view('manage_games/sidebar'),
	));

	echo elgg_view_page($title, $body);

?>
