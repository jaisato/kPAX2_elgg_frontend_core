<?php
/**
 * Elgg kpax plugin everyone page
 *
 * @package kpax
 */

$page_owner = elgg_get_page_owner_entity();

elgg_push_breadcrumb($page_owner->name);

elgg_register_title_button();

$offset = (int)get_input('offset', 0);


/*
$content .= elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'kpax',
	'container_guid' => $page_owner->guid,
	'limit' => 10,
	'offset' => $offset,
	'full_view' => false,
	'view_toggle_type' => false
));
*/

//NOU
//DEFAULT OPTIONS FOR ELGG LISTING. All games.
$options = array(
	'type' => 'object',
	'subtype' => 'kpax',
	'container_guid' => $page_owner->guid,
	'limit' => 10,
	'offset' => $offset,
	'full_view' => false,
	'view_toggle_type' => false
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
	$idFilterer = 0; //Default filterer: fo not filter.
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
	$gameList = $response['body'];
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
}
else {
    register_error(elgg_echo('kpax:list:failed'));
}

//LISTING THE GAMES. All games by default when srvKpax fails.
$content = elgg_list_entities($options);
//FINOU

if (!$content) {
	$content = elgg_echo('kpax:none');
}

$title = elgg_echo('kpax:owner', array($page_owner->name));

$filter_context = '';
if ($page_owner->getGUID() == elgg_get_logged_in_user_guid()) {
	$filter_context = 'mine';
}

$vars = array(
	'filter_context' => $filter_context,
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('kpax/sidebar'),
);

// don't show filter if out of filter context
if ($page_owner instanceof ElggGroup) {
	$vars['filter'] = false;
}

$body = elgg_view_layout('content', $vars);

echo elgg_view_page($title, $body);
