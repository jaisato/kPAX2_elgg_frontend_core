<?php

	$objKpax = new kpaxSrv(elgg_get_logged_in_user_entity()->username);
	gatekeeper();

	// get the form input
	$title = get_input('title');
	$description = get_input('description');
	$tags = string_to_tag_array(get_input('tags'));
	$category = get_input('category'); //NOU
	
	//Creation date is calculated by server - $creationDate = get_input('creationDate');

	/* Cal treue el comentari a les següents línies un cop la col·lecio Games estigui modificada
	 *  $license = get_input('license');
	 *  $thumbnail = get_input('thumbnail');
	 */

	$guid = get_input('guid');
	$owner = elgg_get_logged_in_user_guid();
	$container_guid = get_input('container_guid', $owner);

	elgg_make_sticky_form('kpax'); //if saving fails, the input info. in the form is retained


	$kPAXgame = get_entity($guid);
	if (!$kPAXgame->canEdit()) {
		system_message(elgg_echo('kpax:save:failed:entity'));
		forward(REFERRER);
	}


	// fill in the game object with the information from the form
	$kPAXgame->title = $title;
	$kPAXgame->description = $description;
	$kPAXgame->idCategory = $category; 
	$kPAXgame->creationDate =  date("d-m-Y H:i:s"); 

	$kPAXgame->access_id = ACCESS_LOGGED_IN; // by default, the game is public
	$kPAXgame->owner_guid = $owner; // by default, the developer is the logged in user
	$kPAXgame->tags = $tags;        // save tags as metadata
	
	/* Cal treue el comentari a les següents línies un cop la col·lecio Games estigui modificada
	 *  $kPAXgame->license = $license;
	 *  $kPAXgame->thumbnail = $thumbnail;
	 */

	// save the game to the database
	if ($kPAXgame->save()) {
		elgg_clear_sticky_form('kpax');
		system_message(elgg_echo('kpax:save:success'));
	} else {
		register_error(elgg_echo('kpax:save:failed'));
		forward("manage_games");
	}

	// Save the game
	$response = $objKpax->addGame($_SESSION['campusSession'], $kPAXgame);
	
	//NOU
	//DEL @API2 if($objKpax->addGame($_SESSION["campusSession"],$title, $kPAXgame->getGUID(), $category, $creationDate)!="OK"){
	if($response['status'] != 200) {
		register_error(elgg_echo('kpax:save:failed'));
	//  register_error(elgg_echo('kpax:save:failed:service'));

	//DEL
	// }
	// else { //bob
	//   register_error(elgg_echo('kpax:save:failed'));
	//   forward("kpax");
	}

	//DEL we update tags when crete | update
	// else
	// {
	//   if($objKpax->addDelTagsGame($_SESSION['campusSession'], $kPAXgame->getGUID(), get_input('tags')) != 'OK'){
	//    register_error(elgg_echo('kpax:save:failed:service'));
	//   }
	// }

	// forward user to a page that displays the developer's games information
	system_message($kPAXgame->getURL());
	forward('manage_games/devs');
?>
