<?php

elgg_register_event_handler('init', 'system', 'kpax2_profile_init');

function kpax2_profile_init(){
    elgg_register_page_handler('profile', 'kpax2_profile_page_handler');
}

function kpax2_profile_page_handler(){
    echo elgg_view_resource('profile');
}