<?php

/**
 * Delete a kpax
 *
 * @package Bookmarks
 */
$guid = get_input('guid');
$kpax = get_entity($guid);
$objKpax = new kpaxSrv(elgg_get_logged_in_user_entity()->username);

if (elgg_instanceof($kpax, 'object', 'kpax') && $kpax->canEdit()) {
    $container = $kpax->getContainerEntity();
    if ($kpax->delete()) {

        $response = $objKpax->delGame($_SESSION["campusSession"], $guid);

        if($response['status'] == 200) {
            system_message(elgg_echo("kpax:delete:success"));
        } else {
            register_error(elgg_echo('kpax:delete:failed:service'));
        }

        if (elgg_instanceof($container, 'group')) {
            forward("kpax/group/$container->guid/all");
        } else {
            forward("kpax/owner/$container->username");
        }
    }
}

register_error(elgg_echo("kpax:delete:failed"));
forward(REFERER);
