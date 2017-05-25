<?php
// make sure only logged in users can see this page
gatekeeper();

// set the title
$title = elgg_view_title("My profile");

$content = elgg_view('profile/personaldata', ['name' => 'Roger Martínez', 'nick' => 'RuNcHiTo']);
//$content .= '<div><p>Detailssssss</p></div>';

$content .= elgg_view('profile/attributes',
    [
        'strength' => '80',
        'agility' => '78',
        'intelligence' => '56',
        'charisma' => '34',
        'will' => '20'
        ]);

$content .= elgg_view('profile/abilities');

$body = elgg_view_layout('content', array(
    'content' => $content
));

echo elgg_view_page($title, $body);
