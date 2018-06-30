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

	function kPAX2_manage_games_page_handler(array $segments){
	    // old group usernames
       /* if (substr_count($segments[0], 'group:')) {
            preg_match('/group\:([0-9]+)/i', $segments[0], $matches);
            $guid = $matches[1];
            if ($entity = get_entity($guid)) {
                kpax_url_forwarder($segments);
            }
        }*/

        // user usernames
        //$user = get_user_by_username($segments[0]);
        //if ($user) {
         //   kpax_url_forwarder($segments);
        //}

        $pages = dirname(__FILE__) . '/pages/manage_games';

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

            case "read":
            case "view":
                set_input('guid', $segments[1]);
                include "$pages/view.php";
                break;

            case "edit":
                gatekeeper();
                set_input('guid', $segments[1]);
                include "$pages/save.php";
                break;

            case "all":
                include elgg_get_plugins_path() . 'kPAX2_manage_games/pages/manage_games/all.php';
                break;

            case "play":
                include "$pages/play.php";
                break;

            default:
					return false;
		}

		return true;
	}

    /**
     * Forward to the new style of URLs
     *
     * @param array $page
     */
    /*function kpax_url_forwarder(array $page) {
        global $CONFIG;

        $pages = dirname(__FILE__) . '/pages/manage_games';

        if (!isset($page[1])) {
            $page[1] = 'items';
        }

        switch ($page[1]) {
            case "read":
                $url = "{$CONFIG->wwwroot}manage_games/view/{$page[2]}/{$page[3]}";
                break;
            case "inbox":
                $url = "{$CONFIG->wwwroot}manage_games/inbox/{$page[0]}";
                break;
            case "friends":
                $url = "{$CONFIG->wwwroot}manage_games/friends/{$page[0]}";
                break;
            case "add":
                $url = "{$CONFIG->wwwroot}manage_games/add/{$page[0]}";
                break;
            case "items":
                $url = "{$CONFIG->wwwroot}manage_games/owner/{$page[0]}";
                break;
            case "bookmarklet":
                $url = "{$CONFIG->wwwroot}manage_games/bookmarklet/{$page[0]}";
                break;
        }

        register_error(elgg_echo("changebookmark"));
        forward($url);
    }*/

    /**
     * Populates the ->getUrl() method for bookmarked objects
     *
     * @param ElggEntity $entity The bookmarked object
     * @return string bookmarked item URL
     */
    /*function kpax_url($entity) {
        global $CONFIG;

        $title = $entity->title;
        $title = elgg_get_friendly_title($title);
        return $CONFIG->url . "kpax/view/" . $entity->getGUID() . "/" . $title;
    }*/
?>