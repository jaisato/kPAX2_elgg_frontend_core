<?php

	elgg_register_event_handler('init', 'system', 'kPAX2_manage_games_init');

	function kPAX2_manage_games_init(){
		$root = dirname(__FILE__);
		
		$item = new ElggMenuItem('kPAX2_manage_games', elgg_echo('kPAX:my_dev_games'), 'manage_games/devs');
		elgg_register_menu_item('site', $item);
		elgg_register_page_handler('manage_games', 'kPAX2_manage_games_page_handler');
		
		// accions
		elgg_register_action('manage_games/newGame',  "$root/actions/manage_games/newGame.php" );
	}

	function kPAX2_manage_games_page_handler($segments){
		switch($segments[0]) {
			case "devs":
					include elgg_get_plugins_path() . 'kPAX2_manage_games/pages/manage_games/devs.php';
					break;
			case "my_dev_games":
					include elgg_get_plugins_path() . 'kPAX2_manage_games/pages/manage_games/my_dev_games.php';
					break;
			case "add":
					include elgg_get_plugins_path() . 'kPAX2_manage_games/pages/manage_games/add.php';
					break;
			case "all":
					include elgg_get_plugins_path() . 'kPAX2_manage_games/pages/manage_games/all.php';
					break;
			default:
					return false;
		}
		return true;
	}
	
?>