<?php

/**
 * Elgg add like action
 *
 */
$entity_guid = (int) get_input('guid');
$user = elgg_get_logged_in_user_entity();
$guid = $user->guid;
$username = $user->username;
error_log ('XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX annotation add');
//check to see if the user has already liked the item
if (elgg_annotation_exists($entity_guid, 'likes')) {
    system_message(elgg_echo("likes:alreadyliked"));
    forward(REFERER);
    error_log ('XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX already liked');
}
// Let's see if we can get an entity with the specified GUID
$entity = get_entity($entity_guid);
if (!$entity) {
    register_error(elgg_echo("likes:notfound"));
    forward(REFERER);
}

// limit likes through a plugin hook (to prevent liking your own content for example)
if (!$entity->canAnnotate(0, 'likes')) {
    // plugins should register the error message to explain why liking isn't allowed
    forward(REFERER);
    error_log ('XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX CAN ANOTATE');
} else {
     error_log ('XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX CAN NOT ANOTATE no deberia entrar aqui');
}

$user = elgg_get_logged_in_user_entity();
$objKpax = new kpaxSrv($user->username);

$kpaxPost = get_entity($entity->guid);

if($kpaxPost->getSubtype() == "kpax"){
    $objKpax->addLikeGame($_SESSION["campusSession"],$user->guid,$entity->guid);
}
error_log ('XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa');
$annotation = create_annotation($entity->guid, 'likes', "likes", "", $user->guid, $entity->access_id);
error_log ('XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb');

// tell user annotation didn't work if that is the case
if (!$annotation) {
    register_error(elgg_echo("likes:failure"));
    forward(REFERER);
}

// notify if poster wasn't owner
if ($entity->owner_guid != $user->guid) {

    likes_notify_user($entity->getOwnerEntity(), $user, $entity);
}

system_message(elgg_echo("likes:likes"));

// Forward back to the page where the user 'liked' the object
forward(REFERER);
