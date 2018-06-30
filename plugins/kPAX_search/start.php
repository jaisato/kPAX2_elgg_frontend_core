<?php

/**
 * Search Plugin
 *
 */

elgg_register_event_handler('init', 'system', 'kpsearch_init');

function kpsearch_init() {

    $root = dirname(__FILE__);

    if (!update_subtype('object', 'kpsearch', 'ElggKpax')) {
        add_subtype('object', 'kpsearch', 'ElggKpax');
    }

    elgg_register_page_handler('kpsearch', 'kpsearch_page_handler');

    elgg_register_plugin_hook_handler('game:view', 'object', 'kpsearch_url');

    elgg_register_entity_type('object', 'kpsearch');

/*  already registered at kPax_core
    // libraries
    elgg_register_library('elgg:kpax', "$root/lib/kpax.php");
    elgg_register_library('elgg:kpaxSrv', "$root/lib/kpaxSrv.php");
    elgg_register_library('elgg:kpaxOauth', "$root/lib/kpaxOauth.php");
    elgg_register_library('elgg:kpaxOauthLib', "$root/lib/Oauth.php");
*/
    elgg_register_library('elgg:kpax:game', "$root/lib/game.php");
    elgg_register_library('elgg:kpax:game_list', "$root/lib/game_list.php");

    elgg_load_library('elgg:kpax');
    elgg_load_library('elgg:kpaxSrv');
    elgg_load_library('elgg:kpaxOauth');
    elgg_load_library('elgg:kpaxOauthLib');
    elgg_load_library('elgg:kpax:game');
    elgg_load_library('elgg:kpax:game_list');


    // menus
    elgg_register_menu_item('site', array(
        'name' => 'games',
        'text' => elgg_echo('kPAX:games'),
        'href' => 'kpsearch/search'
    ));

}

function kpsearch_page_handler($page) {

    elgg_push_breadcrumb(elgg_echo('kPAX:games'), 'kpsearch/games');
    elgg_push_breadcrumb(elgg_echo('kPAX:games:search'), 'kpsearch/gamessearch');

    // old group usernames
    if (substr_count($page[0], 'group:')) {
        preg_match('/group\:([0-9]+)/i', $page[0], $matches);
        $guid = $matches[1];
        if ($entity = get_entity($guid)) {
            kpsearch_url_forwarder($page);
        }
    }

    // user usernames
    $user = get_user_by_username($page[0]);
    if ($user) {
        kpsearch_url_forwarder($page);
    }

    $pages = dirname(__FILE__) . '/pages/kpsearch';

    switch ($page[0]) {
        case "search":
            include "$pages/search.php";
            break;

        case "owner":
            include "$pages/owner.php";
            break;

        case "friends":
            include "$pages/friends.php";
            break;

        case "read":
        case "view":
            set_input('guid', $page[1]);
            include "$pages/view.php";
            break;

        case "view2":
            set_input('guid', $page[1]);
            include "$pages/view2.php";
            break;


        case "add":
            gatekeeper();
            include "$pages/add.php";
            break;

        case "edit":
            gatekeeper();
            set_input('guid', $page[1]);
            include "$pages/save.php";
            break;

        case 'group':
            group_gatekeeper();
            include "$pages/owner.php";
            break;

        case "bookmarklet":
            set_input('container_guid', $page[1]);
            include "$pages/bookmarklet.php";
            break;

        case "games":
            include "$pages/games.php";
            break;

        case "devs":
            include "$pages/devs.php";
            break;

        case "my_dev_games":
            //gatekeeper();
            include "$pages/my_dev_games.php";
            break;

        default:
            return false;
    }

    elgg_pop_context();

    return true;
}

/**
 * Forward to the new style of URLs
 *
 * @param string $page
 */
//function kpax_url_forwarder($page) {
function kpsearch_url_forwarder($hook, $type, $url, $params) {
    $page = $params['entity'];

    global $CONFIG;

    if (!isset($page[1])) {
        $page[1] = 'items';
    }

    switch ($page[1]) {
        case "read":
            $url = "{$CONFIG->wwwroot}kpax/view/{$page[2]}/{$page[3]}";
            break;
        case "inbox":
            $url = "{$CONFIG->wwwroot}kpax/inbox/{$page[0]}";
            break;
        case "friends":
            $url = "{$CONFIG->wwwroot}kpax/friends/{$page[0]}";
            break;
        case "add":
            $url = "{$CONFIG->wwwroot}kpax/add/{$page[0]}";
            break;
        case "items":
            $url = "{$CONFIG->wwwroot}kpax/owner/{$page[0]}";
            break;
        case "bookmarklet":
            $url = "{$CONFIG->wwwroot}kpax/bookmarklet/{$page[0]}";
            break;
    }

    register_error(elgg_echo("changebookmark"));
    forward($url);
}

/**
 * Populates the ->getUrl() method for bookmarked objects
 *
 * @param ElggEntity $entity The bookmarked object
 * @return string bookmarked item URL
 */
function kpsearch_url($entity) {
    global $CONFIG;

    $title = $entity->title;
    $title = elgg_get_friendly_title($title);
    return $CONFIG->url . "kpsearch/view/" . $entity->getGUID() . "/" . $title;
}

?> 


