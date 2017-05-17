<?php
// make sure only logged in users can see this page
gatekeeper();

// set the title
$title = elgg_view_title("My profile");

$content = elgg_view('profile/personaldata');
$content .= '<div><p>Detailssssss</p></div>';

$body = elgg_view_layout('content', array(
    'content' => $content
));

echo elgg_view_page($title, $body);
echo "Content: " .$content;