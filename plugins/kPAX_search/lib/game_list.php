<?php
/**
 * Game helper functions
 * Functions based on elgg mod blog plugin
 *
 * @package kpax
 */

function getPageURL() {
 $pageURL = 'http';
 if ( isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] ) == "on" ) {
    $pageURL .= "s";
 } 
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
//  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
 } else {
 	//$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
  $pageURL .= $_SERVER["SERVER_NAME"];
 }
 return $pageURL;
}

function print_cell ($game) {
	
	$url = getPageURL();
		
	$attributes="cellpadding=\"100\" cellspacing=\"100\" class=\"tr-caption-container\" style=\"margin-left: auto; margin-right: auto; text-align: center;";
	
	$ct = "<table align=\"center\" " . $attributes . "><tbody>";
	$ct .= "<tr>";
	$ct .= "<td style=\"text-align: center;\">";
//	$ct .= "<div class='juego_foto_wrapper'>";
	$ct .= "<a class= 'juego_link' href='view/" . $game->guid ."' style=\"margin-left: auto; margin-right: auto;\" target=\"_blank\">";
	$ct .= "<img border=\"0\" src='" . $url . '/' . $game->urlImage."' alt='".$game->name."' align=\"center\" width=\"90%\" />";
	$ct .= "<span class='transicion' /></a>";
//	$ct .= "</div>";
	$ct .= "</td>";
	$ct .= "</tr>";
	$ct .= "<tr>";
	$ct .= "<td class=\"tr-caption\" style=\"text-align: center;\">";
	$ct .= "<p><a href=view/" . $game->guid . "> " . $game->name . "</a><br>";
        // Problem with mongo driver
        // $date = new MongoDB\BSON\UTCDateTime($game->created_at);
        $ct .= "(" . substr($game->created_at,8,2) . "/" . substr($game->created_at,5,2) . "/" . substr($game->created_at,0,4) . ")<br>";
        //. $game->description . "<br>
        $likes=sizeof($game->ulike);
        $plays=sizeof($game->uplay);
        $ct .= "Players: " . $plays . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Likes: " . $likes . "</p>";
	$ct .= "</td></tr></tbody></table>";

	return $ct;

}

function print_games($games){
	
if (isset($games)) {
	$num_results=$_SESSION['select_results'];	
	$offset=$_SESSION['offset'];	
	$num_games=	sizeof($games);
	
	$ng=sqrt($num_results);
	
	$num_cell=1;
	$content .= "<body><table width=\"100%\" height=\"10%\" border=\"203\" cellpadding=\"10\" cellspacing=\"10\">"; 
  
  
  $max=$offset+$num_results-1;
  if ( ($max+1) > $num_games) {
  		$max=$num_games-1;	
  }
 	
	// foreach ($games as $game) {
  $arrayGames = array_values($games);
  for ($i=$offset;$i<=$max;$i++) { 	
	  		$game = $arrayGames[$i]; 	
  		  if (($num_cell % $ng) ==1) {
				$content .= "<tr>";
  	 	  }
			$content .= "<td width=\"20%\">";
			$content .= print_cell($game);			
			$content .= "</td>";
		  if (( ($num_cell % $ng) ==0) || (($max+1) == $num_cell)) {	// Canvi de fila o ultim element
					$content .= "</tr>";
  		}
			$num_cell++;
	}  
	$content .= "</table></body>";	
	return $content;
} else {
        elgg_echo('kPAX:noGames');
}

}


function print_similar_games($similar_games, $objKpax){
	
if (isset($similar_games)) {
	$num_results=$_SESSION['select_results'];	
	$num_games=sizeof($similar_games);

	//$ng=sqrt($num_results);
	$ng=3;
	
	$num_cell=1;
	$content .= "<body><table width=\"100%\" height=\"10%\" border=\"203\" cellpadding=\"10\" cellspacing=\"10\">"; 
   foreach ($similar_games as $gamesimilar) {   	
  		  if (($num_cell % $ng) ==1) {
				$content .= "<tr>";
  	 	  }
			$content .= "<td width=\"20%\">";
				$temp = strval ($gamesimilar);
								$similarGame  = $objKpax->getGame($gamesimilar, $_SESSION["campusSession"]);
								$content.= "<div class='cuadro_juego'>";
									$content.= "<a class= 'juego_link' href='".$similarGame->guid."'></a>";
									$content.= "<div class='juego_foto'>";
										$content.= "<a class= 'juego_link' href='".$similarGame->guid."'>";
												$content.="<img src='" . $url . '/' .  $similarGame->urlImage."' alt='".$similarGame->name."' width='100' />";
											$content.= "<span class='transicion' />";	
										$content.= "</a>";
									$content.= "</div>";
									$content.= "<div class='juego_texto'>";
										$content.= "<a class= 'juego_link' href='".$similarGame->guid."'></a>";
										$content.= "<a href='".$similarGame->guid."'>".$similarGame->name."<span class='final_parrafo final_parrafo_titulo'></span></a>";
										$content.= "<div class='texto_descripcion'>";
											$content.= "<span class='final_parrafo'></span>";
											$content.= "<a class= 'juego_link' href='".$similarGame->guid."'></a>";
										$content.= "</div>";
									$content.= "</div>";	
								$content.= "</div>";   				
			$content .= "</td>";
		
		   if (( ($num_cell % $ng) ==0) || ($num_games == $num_cell)) {	// Canvi de fila o ultim element
				$content .= "</tr>";
  		   }
			$num_cell++;
	}  

	$content .= "</table></body>";	
	return $content;
	
} else {
        elgg_echo('kPAX:noGames');
}

}
                                     
function print_users_like($ulike, $objKpax){
	
if (isset($ulike)) {
	$num_ulikes=	sizeof($ulike);
	$ng=3;
	$num_cell=1;
	$content .= "<body><table width=\"100%\" height=\"10%\" border=\"203\" cellpadding=\"10\" cellspacing=\"10\">"; 
   foreach ($ulike as $ul) {   	
  		  if (($num_cell % $ng) ==1) {
				$content .= "<tr>";
  	 	  }
			$content .= "<td width=\"20%\">";
								$luser  = $objKpax->getUser($ul->uid, $_SESSION["campusSession"]);
								$content.= "<div class='cuadro_juego'>";
									$content.= "<div class='juego_foto'>";
                                                                                $user = get_entity($ul->uid);
                                                                                $icon=$user->getIconURL('large');                                                                     
                                                                                $content.="<img src='" .  $icon . "' alt='".$luser->name."' width='80' />";         
									$content.= "</div>";
									$content.= "<div class='juego_texto'>";
										$content.= "$luser->username";
										$content.= "</div>";
									$content.= "</div>";	
								$content.= "</div>";   				
			$content .= "</td>";
		
		   if (( ($num_cell % $ng) ==0) || ($num_ulikes == $num_cell)) {	// Canvi de fila o ultim element
				$content .= "</tr>";
  		   }
			$num_cell++;
	}  

	$content .= "</table></body>";	
	return $content;
	
} else {
        elgg_echo('kPAX:noGames');
}

}

function print_social_media(){
    
$url = getPageURL();
$content .= "<body><table width=\"100%\" height=\"10%\" border=\"203\" cellpadding=\"10\" cellspacing=\"10\">"; 
    $content .= "<tr>";
        $content .= "<td width=\"20%\">";     
            $content .= "<a class= 'juego_link' href='https://www.facebook.com/' style=\"margin-left: auto; margin-right: auto;\" target=\"_blank\">";
	    $content.="<img src='" . $url . "/social/facebook.jpg" . "' alt='facebook.jpg' width='35' />";
            $content .= "<span class='transicion' /></a>";
        $content .= "</td>";
        $content .= "<td width=\"20%\">";    
            $content .= "<a class= 'juego_link' href=' https://twitter.com' style=\"margin-left: auto; margin-right: auto;\" target=\"_blank\">";    
            $content.="<img src='" . $url . "/social/twitter.jpg" . "' alt='twitter.jpg' width='35' />";
            $content .= "<span class='transicion' /></a>";
        $content .= "</td>";
        $content .= "<td width=\"20%\">";   
            $content .= "<a class= 'juego_link' href='https://plus.google.com/' style=\"margin-left: auto; margin-right: auto;\" target=\"_blank\">";
            $content.="<img src='" . $url . "/social/google.jpg" . "' alt='google.jpg' width='35'>";
            $content .= "<span class='transicion' /></a>";
        $content .= "</td>";
        $content .= "<td width=\"20%\">";   
            $content .= "<a class= 'juego_link' href='https://www.youtube.com/' style=\"margin-left: auto; margin-right: auto;\" target=\"_blank\">";
            $content.="<img src='" . $url . "/social/youtube.jpg" . "' alt='youtube.jpg' width='35' />";
            $content .= "<span class='transicion' /></a>";
        $content .= "</td>";        
        $content .= "<td width=\"20%\">";  
            $content .= "<a class= 'juego_link' href='https://www.skype.com/' style=\"margin-left: auto; margin-right: auto;\" target=\"_blank\">";
            $content.="<img src='" . $url . "/social/skype.png" . "' alt='skype.png' width='35' />";
            $content .= "<span class='transicion' /></a>";
        $content .= "</td>";
    $content .= "</tr>";  		  
$content .= "</table></body>";	
   
return $content;

}