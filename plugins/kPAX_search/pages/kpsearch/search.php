<?php

$title = elgg_echo('kPAX:games');
elgg_register_title_button();


$objKpax = new kpaxSrv(elgg_get_logged_in_user_entity()->username);

//Get filter and order parameters
$idFilterer = $_SESSION['gameListFilter'];
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


/* Get categories, platforms and skills */
$categories = $objKpax->getCategories("category");
$platforms = $objKpax->getCategories("platform");
$skills = $objKpax->getCategories("skill");

// values for filters: get values from session
isset($_SESSION['select_name']) ? $name = $_SESSION['select_name'] : $name = " ";
isset($_SESSION['select_category']) ? $category = $_SESSION['select_category'] : $category = "0";
isset($_SESSION['select_platform']) ? $platform = $_SESSION['select_platform'] : $platform = "0";
isset($_SESSION['select_skills']) ? $skill = $_SESSION['select_skills'] : $skill = "0";
isset($_SESSION['select_sort']) ? $sort = $_SESSION['select_sort'] : $sort = "1";
isset($_SESSION['select_results']) ? $num_results = $_SESSION['select_results'] : $num_results = "9";
isset($_SESSION['offset']) ? $offset = $_SESSION['offset'] : $offset = 0;

// values for filters: get values from last POST
if (isset($_POST['select_name'])) {
		$name = $_POST['select_name'];
		$offset = 0;
}
if (isset($_POST['select_category'])) {
		$category = $_POST['select_category'];
		$offset = 0;
}
if (isset($_POST['select_platform'])) { 
		$platform = $_POST['select_platform'];
		$offset = 0;
}
if (isset($_POST['select_skills'])) {
		$skill = $_POST['select_skills'];
		$offset = 0;
}
if (isset($_POST['select_sort'])) {
		$sort = $_POST['select_sort'];
		$offset = 0;
}
if (isset($_POST['select_results'])) {
		$num_results = $_POST['select_results'];
		$offset = 0;
}
if (isset($_POST['offset'])) {
		$offset = $_POST['offset'];
}

// values for filters: set SESSION values
$_SESSION['select_name']=$name;
$_SESSION['select_category']=$category;
$_SESSION['select_platform']=$platform;
$_SESSION['select_skills']=$skill;
$_SESSION['select_sort']=$sort;
$_SESSION['select_results']=$num_results;
$_SESSION['offset']=$offset;

error_log ('XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX game_name ' . $game_name );

// get offset (when showing the games)
//The first time the application shows the results, it shows the first result ($offset = 0) and 9 results ($num_results)
$action = $vars['page'][1];

$content .= "<div class='table'>";
	$content .= "<div class='row'>";
		$content .= "<div class='cell' width='15%'>";   
      	// platforms
      	$content.="<h1>".elgg_echo('kpax:game:platform')."</h1>";
			$style_selected="font-size:13pt;padding:2px; border:3px solid green;background-color:#00BCD4";
			if("0" == $platform){
				$style_selected="font-weight: bold font-size:13pt;padding:2px; border:3px solid green;background-color:cyan";
			}
      $content .= "<form method='post' action='". $action ."'>";
			$content .= "<fieldset>";
			$content .= "<input type='hidden' name='select_platform' value='0' />";
			$content .= "<input style='".$style_selected."' type='submit' value='".elgg_echo('kpax:game:allplatforms')."' />"; 
			$content .= "</fieldset>";
			$content .= "</form>";
			foreach($platforms as $plat) {
				$style_selected="font-size:13pt;padding:2px; border:3px solid green;background-color:#00BCD4";
				if($plat->name == $platform) {
 					$style_selected="font-weight: bold font-size:13pt;padding:2px; border:3px solid green;background-color:cyan";
				}
				$content .= "<form method='post' action='". $action ."'>";
				$content .= "<fieldset>";
				$content .= "<input type='hidden' name='select_platform' value='".$plat->name."' />";
				$content .= "<input style='".$style_selected."' class='input-box' type='submit' value='".$plat->name."' />";
				$content .= "</fieldset>";
				$content .= "</form>";
			}
       
			// categories		
			$content.="<h1>".elgg_echo('kpax:game:category')."</h1>";
			$style_selected="font-size:13pt;padding:2px; border:3px solid green;background-color:#00BCD4";
			if("0" == $category){
				$style_selected="font-weight: bold font-size:13pt;padding:2px; border:3px solid green;background-color:cyan";
			}
			$content .= "<form method='post' action='". $action ."'>";
			$content .= "<fieldset>";
			$content .= "<input type='hidden' name='select_category' value='0' />";
			$content .= "<input style='".$style_selected."' type='submit' value='".elgg_echo('kpax:game:allcategories')."' />";
			$content .= "</fieldset>";
			$content .= "</form>";
			foreach($categories as $cat) {
				$style_selected="font-size:13pt;padding:2px; border:3px solid green;background-color:#00BCD4";
				if($cat->name == $category) {
 					$style_selected="font-weight: bold font-size:13pt;padding:2px; border:3px solid green;background-color:cyan";
				}
				$content .= "<form method='post' action='". $action ."'>";
				$content .= "<fieldset>";
				$content .= "<input type='hidden' name='select_category' value='".$cat->name."' />";
				$content .= "<input style='".$style_selected."' type='submit' value='".$cat->name."' />";
				$content .= "</fieldset>";
				$content .= "</form>";
			}
		$content .= "</div>";
		$content .= "<div class='cell'>";

 	/*
				// game name selection
				$content.= "<div style='float: left;'>";							
        		$content.="<label for='name'>".elgg_echo('kPAX:game:nameKKKK')."&nbsp;&nbsp;&nbsp;</label>";
				$content.="</div>";

				$content.= "<div style='float: left;'>";
						$content.= "<form method='post' action='". $action ."'>";
								$content.="<input class='' type='text' size='50' name='select_name' id='name' value='".$name. "' />";
						$content.= "</form>";		
				$content.="</div>";

					// search buton
					$content.="<div style='float: right;'>";
							$content.="<input id='search_button' name='search_button' type='submit' value='".elgg_echo('kpsearch:game:search')."' />";
					$content.="</div>";
		*/
		
					//	$content .= "<br><br>";		
					$content .= "<table width=\"100%\" border=\"1\"><body>";
   				$content .= "<tr>";			
						
						// skills
						$content .= "<td><br>";
                                                $content.=elgg_echo('kPAX:game:skills');
                                                $content .= "</td><td>";
						//$content.="<div style='float: left;'>";
						$content.="<p>";
							//$content.="<label for='skill'>".elgg_echo('kPAX:game:skills').": </label>";
							$content.= "<form method='post' name='form_skills' action='". $action ."'>";
							$content.="<select class='' name='select_skills' id='skill' onchange=document.form_skills.submit(); >";
								//$content.="<option disabled='disabled' selected>".elgg_echo('kPAX:game:skills')."</option>";
								$content .= "<option value='0'>".elgg_echo('kPAX:game:allskills')."</option>";
								foreach($skills as $ski)
								{
									$selected = "";
									if($ski->name == $skill)
										$selected = "selected='selected'";
									$content .= "<option value='".$ski->name."' ".$selected.">".$ski->name."</option>";
								}
							$content.="</select>";
							$content.= "</form>";
						$content.="</p>";
						//$content.="</div>";
						$content .= "</td>";		
						
				/* Order form */
				$selectedordenacio1 = "";
				$selectedordenacio2 = "";
				$selectedordenacio3 = "";
				$selectedordenacio4 = "";
				if("1" == $sort)
					$selectedordenacio1 = "selected='selected'";
				else if("2" == $sort)
					$selectedordenacio2 = "selected='selected'";
				else if("3" == $sort)
					$selectedordenacio3 = "selected='selected'";
				else if("4" == $sort)
					$selectedordenacio4 = "selected='selected'";
				
				$content .= "<td><br>";
                                $content.=elgg_echo('kPAX:game:sort');
                                $content .= "</td><td>";
				//$content.="<div style='float: left;'>";
					$content.="<p>";
						//$content.="<label for='sort'>".elgg_echo('kPAX:game:sort').": </label>";
						$content.= "<form method='post' name='form_sort' action='". $action ."'>";
						$content.="<select class='' name='select_sort' id='sort' onchange=document.form_sort.submit();  >";
							//$content.="<option disabled='disabled' selected>".elgg_echo('kPAX:game:sort')."</option>";
							$content.="<option value='1' ".$selectedordenacio1.">".elgg_echo('kPAX:game:name')."</option>";
							$content.="<option value='2' ".$selectedordenacio2.">".elgg_echo('kPAX:creationdate')."</option>";
							$content.="<option value='3' ".$selectedordenacio3.">".elgg_echo('kPAX:game:players')."</option>";
							$content.="<option value='4' ".$selectedordenacio4.">".elgg_echo('kPAX:gameslikes')."</option>";
						$content.="</select>";
						$content.= "</form>";
					$content.="</p>";
				//$content.="</div>";
				$content .= "</td>";
                                
        $content .= "<td><br>";
        $content.=elgg_echo('kPAX:game:resultsperpage');
        $content .= "</td><td>";
				
				$selectresult1 = "";
				$selectresult2 = "";
				$selectresult3 = "";
				if("9" == $num_results)
					$selectresult1 = "selected='selected'";
				else if("16" == $num_results)
					$selectresult2 = "selected='selected'";
				else if("25" == $num_results)
					$selectresult3 = "selected='selected'";
						// Number of Results per page
						$content .= "<td>";
						//$content.="<div style='float: left;'>";
						$content.="<p>";
							//$content.="<label for='results'>".elgg_echo('kPAX:game:resultsperpage').": </label>";
							$content.= "<form method='post' name='form_results' action='". $action ."'>";
							$content.="<select class='' name='select_results' id='results' onchange=document.form_results.submit();  >";
							//$content.="<option disabled='disabled' selected>".elgg_echo('kPAX:game:resultsperpage')."</option>";
							$content.="<option value='9' ".$selectresult1.">".elgg_echo('&nbsp&nbsp&nbsp9 (3x3)')."</option>";
							$content.="<option value='16' ".$selectresult2.">".elgg_echo('&nbsp&nbsp16 (4x4)')."</option>";
							$content.="<option value='25' ".$selectresult3.">".elgg_echo('&nbsp&nbsp25 (5x5)')."</option>";


							$content.="</select>";
							$content.= "</form>";
						$content.="</p>";
						//$content.="</div>";	
						$content .= "</td>";	

	   $content .= "</tr>";
  	   $content .= "</tbody></table>";		
	$content .= "<br>";			   						
						
		//	$content.="</div>";
		//	$content.="<div class='clearer'></div>";


//Get games list from kpaxSrv
$gameList = $objKpax->getListGamesSearch($_SESSION["campusSession"], $idOrderer, $idFilterer, $fields, $values, $name,
																																 $category, $platform, $skill, $sort);
// Mostrar els jocs
if(isset($gameList) && sizeof($gameList) > 0) { 
   // system_message(elgg_echo('kPAX:list:success
  	$content .=  print_games($gameList);	
} else {
    //register_error(elgg_echo('kpax:list:failed'));
    $content .= '<div><p>' . elgg_echo('kPAX:nogames') . '</p></div>';
}
//Buttons for Pagination
$content .= "<div class='pagination'><p>";
			// Previous Button		
			if ($offset > 0){
				$content .= "<div style='float: left;'>";
				$content .= "<form method='post' action='search'>";
				$content .= "<fieldset>";
				$style="font-size:13pt;padding:2px; border:3px solid green;background-color:#00BCD4";
				$content .= "<input type='hidden' name='offset' value='".($offset-$num_results)."'/>";
        $content .= "<input style='" . $style . "' type='submit' value='".elgg_echo('kpax:game:previous')."' />";
        $content .= "</fieldset>";
				$content .= "</form>";
				$content .= "</div>";
			}
			
			// Next button
			if ( ($offset + $num_results) < sizeof($gameList)){
				$content .= "<div style='float: right;'>";
				$content .= "<form method='post' action='search'>";
				$content .= "<fieldset>";
        $style="font-size:13pt;padding:2px; border:3px solid green;background-color:#00BCD4";
        $content .= "<input type='hidden' name='offset' value='".($offset+$num_results)."'/>";
				$content .= "<input style='" . $style . "' type='submit' value='".elgg_echo('kpax:game:next')."' />";
				$content .= "</fieldset>";
				$content .= "</form>";
				$content .= "</div>";
			}	
		$content .= "</p></div>";
		$content.="</div>"; //contenido
	$content.="</div>"; //listado_juegos
	
	$content .= "</div>";
	$content .= "</div>";
	$content .= "</div>";

$body = elgg_view_layout('one_column', array(
    'filter_context' => 'search',
    'content' => $content,
    'title' => $title
		));
		

$css_url = 'mod/kPAX_search/views/default/css/elements/felix.css';
elgg_register_css('game', $css_url);
elgg_load_css('game');

/**
	
// CSS include gamelist.css
$css_url = 'mod/kPAX_search/views/default/css/elements/gamelist.css';
elgg_register_css('gamelist', $css_url);
elgg_load_css('gamelist');
// CSS include game_small.css 
$css_url = 'mod/kPAX_search/views/default/css/elements/game_small.css';
elgg_register_css('game_small', $css_url);
elgg_load_css('game_small');
// JS include kpax.js
$js_url = 'mod/kPAX_search/js/kpax.js';
elgg_register_js('games_kpax', $js_url);
elgg_load_js('games_kpax');

**/


echo elgg_view_page($title, $body);
?>

