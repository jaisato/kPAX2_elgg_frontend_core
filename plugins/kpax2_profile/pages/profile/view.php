<?php
// make sure only logged in users can see this page
gatekeeper();

// set the title
$title = elgg_view_title("My profile");

$user = elgg_get_logged_in_user_entity();
$kpax = new kpaxSrv($user->username);
$arrUserprofile = $kpax->getUserProfile($user->username);
$userprofile = $arrUserprofile['body'][0];

$content .= elgg_view('profile/personaldata',
    ['name' => $userprofile->name
        , 'nick' => $userprofile->nickname,
        'created_at' => $userprofile->created_at,
        'updated_at' => $userprofile->updated_at]);

$content .= elgg_view('profile/attributes', ['attributes' => $userprofile->attributes]);

$content .= elgg_view('profile/abilities', ['abilities' => $userprofile->abilities]);

$body = elgg_view_layout('content', array(
    'content' => $content
));

echo elgg_view_page($title, $body);
