<?php

//---------------
//AUTHOR: drierat
//---------------

$title = elgg_echo('kpax:all');

elgg_register_title_button();

//DEFAULT OPTIONS FOR ELGG LISTING. All games.
$options = array(
  'types' => 'object',
  'subtypes' => 'kpax',
  'limit' => 10,
  'full_view' => false,
);

//GETTING THE GAME LIST FROM SRVKPAX
//$objKpax = new kpaxSrv(elgg_get_logged_in_user_entity()->username);
//Prova per determinar si l'objecte depen del paràmetre username
$objKpax = new kpaxSrv(elgg_get_logged_in_user_guid()->username);

/*
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
*/

//$page_owner = elgg_get_page_owner_guid();
//$page_owner = "admin";
//returnem el guid de l'usuari logged
$page_owner = elgg_get_logged_in_user_guid();

$response = $objKpax->getUserListGames($page_owner,$_SESSION["campusSession"]);

if($response['status'] == 200) {
	system_message(elgg_echo('kpax:list:success'));

  $gameList = $response['body'];
	/*
	 * Adding the gameIds to the elgg list.
	 *
	 * Forcing elgg to list the games in the same
	 * order as gotten from srvKpax. Not by default
	 * elgg order (time_created desc).
	 */
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

// Deshabilitant aquesta línia carrega correctament. És la que llença la consulta contra ElggDB
//$content = elgg_list_entities($options);


if (!$content) {
    $content = '<p>' . elgg_echo('kpax:none') . '</p>';
}

$body = elgg_view_layout('content', array(
    'filter_context' => 'all',
    'content' => $content,
    'title' => $title,
    'sidebar' => elgg_view('kpax/sidebar'),
        ));
echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container_guid));

echo elgg_view_page($title, $body);

?>
