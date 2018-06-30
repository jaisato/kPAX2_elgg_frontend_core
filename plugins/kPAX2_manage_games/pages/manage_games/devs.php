<?php

	// "My games" button
	elgg_register_menu_item('title', array(
			     'name' => 'my_dev_games',
			     'href' => 'manage_games/my_dev_games',
			     'text' => elgg_echo('kPAX:myGames'),
			     'link_class' => 'elgg-button elgg-button-action',
			 ));

	// "Add games" button
	elgg_register_menu_item('title', array(
			     'name' => 'add_games',
			     'href' => 'manage_games/add',
			     'text' => elgg_echo('kPAX:add'),
			     'link_class' => 'elgg-button elgg-button-action',
			 ));

	$body .= elgg_view('manage_games/devs_explanations');

	$params = array(
		'content' => $body,
		'filter' => false,  // All, Mine and Friends tabs are not shown
	);
	
	$body = elgg_view_layout('content', $params);
	echo elgg_view_page($title, $body);

?>