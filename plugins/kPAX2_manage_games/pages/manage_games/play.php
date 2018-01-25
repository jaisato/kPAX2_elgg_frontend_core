<?php

//---------------------------------
//AUTHOR: rviguera, agiroo, drierat
//---------------------------------

// Aquest fitxer no implementa encara res de kPAX2_manage_games
// Però considero que serà útil pel desenvolupament 

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
