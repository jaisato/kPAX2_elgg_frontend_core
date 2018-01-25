<?php


$title = elgg_echo('kPAX:gamesdetall');
elgg_register_title_button();

$guid = get_input('guid');

if ($guid == 0) {
    $ent = new ElggObject;
} else {
    $ent = get_entity($guid);
    if (!$ent) {
  	register_error(elgg_echo('noaccess'));
        $_SESSION['last_forward_from'] = current_page_url();
        forward('');
    }
}
$ent->subtype = "comment";

$objKpax = new kpaxSrv(elgg_get_logged_in_user_entity()->username);

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

//Process to extract id's game
$urlVector = explode("/",$_SERVER['REQUEST_URI']);
$idGame = $urlVector[count($urlVector)-1];

// get the game
$game = $objKpax->getGame($idGame, $_SESSION["campusSession"]);

isset($game->created_at) ? $created_at = $game->created_at : $created = '00/00/00';
$created_label = elgg_echo('kpax:created');

isset($game->price) ? $price = $game->price : $price = '0,00 €';
$play_label = elgg_echo('kpax:game:play');
$buy_label = elgg_echo('kpax:game:buy');
$wishlist_label = elgg_echo('kpax:game:wishlist');

isset($game->description) ? $game_description = $game->description : $game_description = NULL;
$description_label = elgg_echo('kPAX:game:description');


/* Get categories, platforms and skills */
$categories = $objKpax->getCategories("category");
$platforms = $objKpax->getCategories("platform");
$skills = $objKpax->getCategories("skill");
$metadatas = $objKpax->getCategories("metadata");


//Add breadcrumb game
elgg_push_breadcrumb($game->name);

$url = getPageURL();

	
$content .= "<div class='table'>";
	$content .= "<div class='row'>";
		$content .= "<div class='table' width='70%' >";
			$content .= "<div class='row'>";
				$content .= "<div class='table'>";
					// superior panel - game details
					$content .= "<div class='row'>";
						$content .= "<div class='cell cell25'>";
							$content.="<img src='" . $url . '/' . $game->urlImage."' alt='".$game->name."' width='300'  />";
						$content.= "</div>";
						$content .= "<div class='cell'>";		
							$content.= "<h2>".$game->name."</h2><br>";
							$content.= elgg_echo('kPAX:game:description').": ".$game->description . "<br>";
                                                        // Problem with mongo driver
                                                        // $date = new MongoDB\BSON\UTCDateTime($game->created_at);
                                                        $content .= elgg_echo('kPAX:game:created').": " . substr($game->created_at,8,2) . "/" .
                                                                substr($game->created_at,5,2) . "/" . substr($game->created_at,0,4) . "<br>";    
                                                    	$content.= elgg_echo('kPAX:game:category').": ";
							foreach ( $game->category as $cat ) {
								$content.=' ' . $cat;
							}						
							$content.= "<br>" . elgg_echo('kPAX:game:platforms').": ";
							foreach ( $game->platforms as $plat ) {
								$content.=' ' . $plat;
							}	
							$content.=  "<br>" . elgg_echo('kPAX:game:skills').": ";
							foreach ( $game->skills as $skill ) {
								$content.=' ' . $skill;
							}	
							//$content.="<input id='play_button' name='play_button' type='submit' value='". $buy_label.": ".$price ."' />";
							//$content.="<input id='buy_button' name='buy_button' type='submit' value='". $buy_label ."' />";
							//$content.="<input id='wishlist_button' name='wishlist_button' type='submit' value='". $wishlist_label ."' />";		
							//$content.="<input id='play_button' name='play_button' type='submit' value='".elgg_echo('kPAX:play')."' />";
						$content.= "</div>";
					$content .= "</div>"; // row
				$content .= "</div>";
			$content .= "</div>";		
			$content .= "<div class='row'>";
				$content .= "<div class='table'>";
					$content .= "<div class='row'>";
						$content .= "<div class='cell cell50'>";
							// similar games panel
							$content.= "<h3>".elgg_echo('kPAX:similarGames')."</h3>";
							$content .=  print_similar_games($game->SimilarGames, $objKpax);
						$content .= "</div>";		
						$content .= "<div class='cell cell50'>";
								$content.="<h3>".elgg_echo('kPAX:comments')."</h3>";								
								$content .= elgg_view_comments($ent);                                                               
						$content .= "</div>";		
					$content .= "</div>"; // row
				$content .= "</div>"; //table
			$content .= "</div>"; // row
		$content .= "</div>"; //table
                
		$content .= "<div class='cell cell25'>";
                	$content .= "<div class='table'>";
				$content .= "<div class='row'>";
    					$content .= "<div class='cell'>";
						$content.="<h3>".elgg_echo('kPAX:gamestatisticssocialnetworks')."</h3>";
                                                $content.="<h3>Playing</h3>";
                                                $content .=  print_users_like($game->uplay, $objKpax);
                                                $content .="<br>";
                                      $content .= "</div>";
                        	$content .= "</div>";
                                $content .= "<div class='row'>";
                                       $content .= "<div class='cell'>";
                                                $content.="<h3>".elgg_echo('kPAX:gameslikes')."</h3>";
                                                $content .=  print_users_like($game->ulike, $objKpax);
                                                $content .="<br>Like:<br>";       
                                                $content .=  elgg_view('likes/button', ['entity' => $ent]);
                                       $content .= "</div>";
                                $content .= "</div>";
                                $content .= "<div class='row'>";
                                       $content .= "<div class='cell'>";
                                                $content.="<h3>".elgg_echo('kPAX:gameshare')."</h3>";
                                                $content .="<br>";
                                                $content .= print_social_media();
                                                
                                       $content .= "</div>";
                                $content .= "</div>";
                        $content .= "</div>";                
		$content .= "</div>";
               
           
              
	$content .= "</div>"; // row
$content .= "</div>"; // table


$body = elgg_view_layout('one_column', array(
    'content' => $content,
    'title' => ''/*$title*/,
    'filter' => '',
    'header' => '',
        ));

// CSS include

$css_url = 'mod/kPAX_search/views/default/css/elements/felix.css';
elgg_register_css('game', $css_url);
elgg_load_css('game');

/*

$css_url = 'mod/kPAX_core/views/default/css/elements/game.css';
elgg_register_css('game', $css_url);
elgg_load_css('game');

$css_url = 'mod/kPAX_core/views/default/css/elements/game_small.css';
elgg_register_css('game_small', $css_url);
elgg_load_css('game_small');

$css_url = 'mod/kPAX_core/views/default/css/elements/game.css';
elgg_register_css('game', $css_url);
elgg_load_css('game');

*/

echo elgg_view_page($title, $body);

?>
