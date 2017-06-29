<?php

elgg_register_event_handler('init', 'system', 'kpax2_profile_init');

function kpax2_profile_init(){
    elgg_register_page_handler('profile', 'kpax2_profile_page_handler');
}

function kpax2_profile_page_handler($segments){
    switch($segments[0]) {
        case 'view':
        default:
            include elgg_get_plugins_path() . 'kpax2_profile/pages/profile/view.php';
            break;
    }
    return true;
}