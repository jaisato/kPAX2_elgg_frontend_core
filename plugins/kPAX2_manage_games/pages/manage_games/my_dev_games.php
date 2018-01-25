<?php

//---------------
//AUTHOR: drierat
//---------------
	// Assegurar que sols entran usuaris identificats
	gatekeeper();
	
	// Títol de la pàgina
	$title = elgg_echo('kPAX:my_dev_games');
	
	//elgg_register_title_button();  És un botó superior dret.

	//DEFAULT OPTIONS FOR ELGG LISTING. All games.
	$options = array(
		'types' => 'object',
		'subtypes' => 'kpax',
		'limit' => 10,
		'full_view' => false,
	);

	//GETTING THE GAME LIST FROM SRVKPAX
	$objKpax = new kpaxSrv(elgg_get_logged_in_user_guid()->username);
	
	// Es demanen els jocs propis de l'usuari identificat
	$page_owner = elgg_get_logged_in_user_guid();
	$response = $objKpax->getUserListGames($page_owner);

	if($response['status'] == 200) {
		system_message(elgg_echo('kpax:list:success'));
		$gameList = $response['body'];
		/*
		 * Adding the gameIds to the elgg list.
		 *
		 * Forcing elgg to list the games in the same order as gotten from srvKpax. 
		 * Not by default elgg order (time_created desc). */
		$where = array();
		$orderBy = ' CASE ';
		for($i = 0, $size = sizeof($gameList); $i < $size; ++$i)
		{
			$idGame = $gameList[$i]->idGame;

			$where[] = $idGame;
			$orderBy = $orderBy . " WHEN e.guid = " . $idGame . " THEN " . ($i + 1);
		}
		$options = array_merge($options, array('guids' => $where));
		$orderBy = $orderBy . " END ";
		$options = array_merge($options, array('order_by' => $orderBy));

	}else {
			register_error(elgg_echo('kpax:list:failed'));
	}
	$content = elgg_list_entities($options);

	//Llistat exclusius dels jocs propis. Si el servidor falla es mostra cap joc cap.
	if ($size==0) {
		$content = '<p>' . elgg_echo('kPAX:noGames') . '</p>';
	}	else {
		$content .= elgg_view('manage_games/games_list', array('objGameList' => $gameList));
	}

	$body = elgg_view_layout('content', array(
		'filter_context' => 'mine',   //Sols volem jocs propis
		'content' => $content,
		'title' => $title,
		'filter' => false, // All, Mine and Friends tabs are not shown
		'sidebar' => false // Per mirar d'aprofitar l'espai lateral
	)); 
	
	echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container_guid));
	echo elgg_view_page($title, $body);

?>