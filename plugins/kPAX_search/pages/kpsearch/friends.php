<?php
/**
 * Elgg kpax plugin friends page
 *
 * @package Elggkpax
 */

$owner = elgg_get_page_owner_entity();

elgg_push_breadcrumb($owner->name, "kpax/owner/$owner->username");
elgg_push_breadcrumb(elgg_echo('friends'));

elgg_register_title_button();

$title = elgg_echo('kpax:friends');

//NOU - El·liminat
// $content = list_user_friends_objects($owner->guid, 'kpax', 10, false);

//NOU

//Getting the friends ids to filter the resulting list.
$friends = get_user_friends_of($owner->guid, ELGG_ENTITIES_ANY_VALUE, 999999, false);
$friendsIds = array();
for($i = 0, $size = sizeof($friends); $i < $size; ++$i) {
	$friendsIds[] = $friends[$i] -> getGUID();
}

//DEFAULT OPTIONS FOR ELGG LISTING. All games.
$options = array(
  'types' => 'object',
  'subtypes' => 'kpax',
  'limit' => 10,
  'full_view' => false,
);

//GETTING THE GAME LIST FROM SRVKPAX
$objKpax = new kpaxSrv(elgg_get_logged_in_user_entity()->username);
//Parameters
$idFilterer = $_SESSION['gameListFilter'];
$idOrderer = $_SESSION['gameListOrder'];
$fields = $_SESSION['gameListFields'];
$values = $_SESSION['gameListValues'];

if(!isset($idFilterer))
{
	$idFilterer = 0; //Default filterer: do not filter.
	$_SESSION['gameListFilter'] = $idFilterer;
}
if(!isset($idOrderer))
{
	$idOrderer = 0; //Default orderer: do not order.
	$_SESSION['gameListOrder'] = $idOrderer;
}
if(!isset($fields))
{
	$fields = array(); //Default fields array: no fields.
	$values = array(); //Default fields array: no fields.

	$_SESSION['gameListFields'] = $fields;
	$_SESSION['gameListValues'] = $values;
}

$response = $objKpax->getListGames($_SESSION["campusSession"], $idOrderer, $idFilterer, $fields, $values);

if($response['status'] == 200) {
	system_message(elgg_echo('kpax:list:success'));

	/*
	 * Adding the gameIds to the elgg list.
	 * 
	 * Forcing elgg to list the games in the same
	 * order as gotten from srvKpax. Not by default
	 * elgg order (time_created desc).
	 */
	$gameList =  $response['body'];
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
	$options = array_merge($options, array('owner_guids' => $friendsIds));
}
else {
    register_error(elgg_echo('kpax:list:failed'));
}

//LISTING THE GAMES. All games by default when srvKpax fails.
$content = elgg_list_entities($options);
//FI NOU

if (!$content) {
	$content = elgg_echo('kpax:none');
}

$params = array(
	'filter_context' => 'friends',
	'content' => $content,
	'title' => $title,
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
