<?php

elgg_register_event_handler('init', 'system', 'kpax_init');

function kpax_init() {

    $root = dirname(__FILE__);

    if (!update_subtype('object', 'kpax', 'ElggKpax')) {
        add_subtype('object', 'kpax', 'ElggKpax');
    }

    elgg_register_page_handler('kpax', 'kpax_page_handler');

    elgg_register_entity_url_handler('object', 'kpax', 'kpax_url');

    elgg_register_entity_type('object', 'kpax');

    // libraries
    elgg_register_library('elgg:kpax', "$root/lib/kpax.php");
    elgg_register_library('elgg:kpaxSrv', "$root/lib/kpaxSrv.php");
    elgg_register_library('elgg:kpaxOauth', "$root/lib/kpaxOauth.php");
    elgg_register_library('elgg:kpaxOauthLib', "$root/lib/Oauth.php");

    elgg_load_library('elgg:kpax');
    elgg_load_library('elgg:kpaxSrv');
    elgg_load_library('elgg:kpaxOauth');
    elgg_load_library('elgg:kpaxOauthLib');

    // actions
    elgg_register_action('kpax/save', "$root/actions/kpax/save.php");
    elgg_register_action('kpax/delete', "$root/actions/kpax/delete.php");

    // menus
    elgg_register_menu_item('site', array(
        'name' => 'play',
        'text' => elgg_echo('kPAX:play'),
        'href' => 'kpax/play'
    ));

    elgg_register_menu_item('site', array(
        'name' => 'developers',
        'text' => elgg_echo('kPAX:devs'),
        'href' => 'kpax/devs'
    ));

    // WS AUTH USER
    // TODO
    function auth_user($username="", $password = "") {

        $credentials = array('username' => $username, 'password' => $password);

        $user = get_user_by_username($credentials['username']);
        if (!empty($user)) {
            if ($user->password !== generate_user_password($user, $credentials['password'])) {
                log_login_failure($user->guid);
                throw new LoginException(elgg_echo('LoginException:PasswordFailure'));
                return "ERROR";
            }
            return "OK";
        } else {
            return "ERROR";
        }
    }

    elgg_ws_expose_function("user.auth", "auth_user", array("username" => array('type' => 'String', 'required' => true), "password" => array('type' => 'String', 'required' => true)), 'Auth user elgg', 'GET', true, false);

    function auth_sign($username="") {

        $credentials = array('username' => $username);

        $user = get_user_by_username($credentials['username']);
        if (!empty($user)) {
            return "OK";
        } else {
            $user = get_user_by_username("uoc.edu_".$credentials['username']);
            if(!empty ($user))return "OK";
            return "ERROR";
        }
    }

    elgg_ws_expose_function("auth.sign", "auth_sign", array("username" => array('type' => 'String', 'required' => true)), 'Auth sign ellg', 'GET', true, false);
}


function kpax_page_handler($page) {

    elgg_push_breadcrumb(elgg_echo('kPAX:play'), 'kpax/play');
    elgg_push_breadcrumb(elgg_echo('kPAX:devs'), 'kpax/devs');
    elgg_push_breadcrumb(elgg_echo('Games'), 'kpax/all');   //NOU  ???

    // old group usernames
    if (substr_count($page[0], 'group:')) {
        preg_match('/group\:([0-9]+)/i', $page[0], $matches);
        $guid = $matches[1];
        if ($entity = get_entity($guid)) {
            kpax_url_forwarder($page);
        }
    }

    // user usernames
    $user = get_user_by_username($page[0]);
    if ($user) {
        kpax_url_forwarder($page);
    }

    $pages = dirname(__FILE__) . '/pages/kpax';

    switch ($page[0]) {
        case "all":
            include "$pages/all.php";
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

        case "play":
            include "$pages/play.php";
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
function kpax_url_forwarder($page) {
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
function kpax_url($entity) {
    global $CONFIG;

    $title = $entity->title;
    $title = elgg_get_friendly_title($title);
    return $CONFIG->url . "kpax/view/" . $entity->getGUID() . "/" . $title;
}

?> 
