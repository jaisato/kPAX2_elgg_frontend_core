<?php

//---------------------------------
//AUTHOR: rviguera, agiroo, drierat
//---------------------------------

$title = elgg_echo('kpax:games');

$objKpax = new kpaxSrv(elgg_get_logged_in_user_entity()->username); //Get games list from kpaxSrv


//Get filter and order parameters
$idFilterer = $_SESSION['gameListFilter']; //Millor fer-ho com a post
$idOrderer = $_SESSION['gameListOrder'];
$fields = $_SESSION['gameListFields'];
$values = $_SESSION['gameListValues'];

if(!isset($idFilterer))
{
    $idFilterer = 0; //Default filterer: do not filter
    $_SESSION['gameListFilter'] = $idFilterer;
}
if(!isset($idOrderer))
{
    $idOrderer = 0; //Default orderer: do not order
    $_SESSION['gameListOrder'] = $idOrderer;
}
if(!isset($fields))
{
    $fields = array(); //Default fields array: no fields
    $values = array(); //Default fields array: no fields

    $_SESSION['gameListFields'] = $fields;
    $_SESSION['gameListValues'] = $values;
}

/*

CODI A AFEGIR DE L'ALBERT ORIOL

$cadena = "";
if(isset($_POST['text'])) $cadena = $_POST['text'];

// Games search form
$content .= "<br/><br/><form method=\"post\" action=\"all\">";
$content .= "Search games<hr/>";
$content .= "<select name=\"seleccio\" size=1>
                    <option value=\"1\">" . elgg_echo('kPAX:game:name') . "</option>
                    <option value=\"2\">" . elgg_echo('kPAX:game:category') . "</option>
                    <option value=\"3\">" . elgg_echo('kPAX:game:skills') . "</option>
            </select><br/><br/>";
if(strlen($cadena) == 0)            
    $content .= " <input type=\"text\" name=\"text\" size =\"100\" value=\"Text...\"/><br/><br/>";
else
    $content .= " <input type=\"text\" name=\"text\" size =\"100\" value=\"".$cadena."\"/><br/><br/>";
$content .= " <input type=\"submit\" value=\"Cerca\" />";
$content .= "<hr/>";
$content .= "</form><br/><br/>";

system_message(elgg_echo($content));
*/

// Carreguem el FORM de cerca de jocs <- Això ha d'anar a una altra pàgina via botó o al sidebar
$vars = kpax_prepare_form_vars();
$content = elgg_view_form('kpax/filter_games', array(), $vars);


//Print games list on screen
$response = $objKpax->getListGames($_SESSION["campusSession"], $idOrderer, $idFilterer, $fields, $values);

if($response['status'] == 200) {
    system_message(elgg_echo('kpax:list:success'));
    $content .= elgg_view('kpax/games_list', array('objGameList' => $response['body']));
}
else {
    register_error(elgg_echo('kpax:list:failed'));
    $content .= '<p>' . elgg_echo('kpax:none') . '</p>';
}

$body = elgg_view_layout('content', array(
    'filter' => false,
    'content' => $content,
    'sidebar' => elgg_view('kpax/sidebar'),
));
 
echo elgg_view_page($title, $body);

?>
