<?php
/**
 * Game helper functions
 * Functions based on elgg mod blog plugin
 *
 * @package kpax
 */

/**
 * Get page components to view a kpax game.
 *
 * @param int $guid GUID of a game entity or idGame.
 * @return array
 */
function game_get_page_content_read($guid = NULL) {

	$return = array();
	$campusSession = $_SESSION["campusSession"];

	//$game = get_entity($guid); at the moment connect with kpax
	$objKpax = new kpaxSrv(elgg_get_logged_in_user_entity()->username);

	$game = $objKpax->getGame($guid, $campusSession);
	if (!$game) {
		register_error(elgg_echo("kpax:connection:noresponse"));
		forward(REFERER);
	}

	// no header or tabs for viewing an individual game
	$return['filter'] = false;
/*
	if (!elgg_instanceof($game, 'object', 'kpax')) {
		register_error(elgg_echo('noaccess'));
		$_SESSION['last_forward_from'] = current_page_url();
		forward('');
	}
*/
	$return['title'] = $game->name;

	elgg_push_breadcrumb($game->name);

	/* CSS include game*/
	$css_url = 'mod/kpax/views/default/css/elements/game.css';
	elgg_register_css('game', $css_url);
	elgg_load_css('game');
	/* CSS include game small*/
	$css_url = 'mod/kpax/views/default/css/elements/game_small.css';
	elgg_register_css('game_small', $css_url);
	elgg_load_css('game_small');

	$variables['game'] = $game;

	$return['content'] = elgg_view('kpax/game/view', $variables);
	//$return['content'] = elgg_view_entity($game, array('full_view' => true));
	// check to see if we should allow comments
	/*if ($game->comments_on != 'Off' && $game->status == 1) {
		$return['content'] .= elgg_view_comments($game);
	}*/

	return $return;
}

/**
 * Get page components to edit/create a kpax game.
 *
 * @param string  $page     'edit' or 'new'
 * @param int     $guid     GUID of game game or IdGame
 * @param int     $revision Annotation id for revision to edit (optional)
 * @return array
 */
function game_get_page_content_edit($page, $guid = 0, $revision = NULL) {
	//TODO: Don't forget to register js to integrate draft auto_save_revision funtionality
	//elgg_register_js()
	//elgg_load_js('kpax.game');
    
	$return = array(
		'filter' => false,
	);

	$vars = array();
	$vars['id'] = 'kpax-game-edit';
	$vars['class'] = 'elgg-form-alt';

	$sidebar = NULL;
	if ($page == 'edit') {

		$objKpax = new kpaxSrv(elgg_get_logged_in_user_entity()->username);

		$game = $objKpax->getGame($guid, $_SESSION["campusSession"]);

		$title = elgg_echo('edit');
		//TODO Test if user is owner of the game or is admin
		// if (!(elgg_is_admin_logged_in() || (elgg_is_logged_in() && $owner_guid == elgg_get_logged_in_user_guid()))) {
		// TODO: Improve isset by checking a valid object something done in blog plugin elgg_instanceof($blog, 'object', 'blog')
		if (isset($game) && elgg_is_admin_logged_in()) {
			$vars['entity'] = $game;
			//TODO: sanitize_string($game->name());
			$title .= ': '.$game->name;
			//TODO: Create revisions for kpax games
			/*
			if ($revision) {
				$revision = elgg_get_annotation_from_id((int)$revision);
				$vars['revision'] = $revision;
				$title .= ' ' . elgg_echo('blog:edit_revision_notice');

				if (!$revision || !($revision->entity_guid == $guid)) {
					$content = elgg_echo('blog:error:revision_not_found');
					$return['content'] = $content;
					$return['title'] = $title;
					return $return;
				}
			}
			*/

			$body_vars = game_prepare_form_vars($game, $revision);

			//elgg_push_breadcrumb($game->title, $game->getURL());
			elgg_push_breadcrumb(elgg_echo('edit'));
			
			//TODO: integrate draft auto_save_revision
			//elgg_load_js('kpax.game');
			$form_vars = array('enctype' => 'multipart/form-data');

			$content = elgg_view_form('kpax/save', $form_vars, $body_vars);
			//$sidebar = elgg_view('blog/sidebar/revisions', $vars);
		} else {
			$content = elgg_echo('kPAX:error:cannot_edit_game');
		}
	} else {
		elgg_push_breadcrumb(elgg_echo('kPAX:add'));
		$body_vars = game_prepare_form_vars(null);

		$title = elgg_echo('kPAX:add');
		$form_vars = array('enctype' => 'multipart/form-data');
		$content = elgg_view_form('kpax/save', $form_vars, $body_vars);
	}
	// "Help" button deleted. TODO: Open Help in a overlay see JB_Overlay
/*	elgg_register_menu_item('title', array(
	                 'name' => 'Need Help',
	                 'href' => 'kpax/devs',
	                 'text' => elgg_echo('kpax:help:developers'),
	                 'link_class' => 'elgg-button elgg-button-action',
	             ));
*/
	$return['title'] = $title;
	$return['content'] = $content;
	if(isset($sidebar)){
		$return['sidebar'] = $sidebar;
	}
	return $return;	
}
/**
 * Pull together game variables for the save form
 *
 * @param ElggGame       $game
 * @param ElggAnnotation $revision
 * @return array
 */
function game_prepare_form_vars($game = NULL, $revision = NULL) {

	// input names => defaults
	$values = array();
	$objectvars = array_keys(get_object_vars($game));
	foreach ($objectvars as $key => $value) {
		$values[$value] = NULL;
	}
	//TODO: Other fields inherited from blog to review their usability. elgg_entity?
	$values['excerpt'] = NULL;
	$values['access_id'] = ACCESS_DEFAULT;
	//$values['comments_on'] = 'On';
	$values['draft_warning'] = '';
	$values['container_guid'] = NULL;
	$values['guid'] = NULL;

	if ($game) {
		foreach (array_keys($values) as $field) {
			if (isset($game->$field)) {
				$values[$field] = $game->$field;
			}
		}

		if ($game->status == 'draft') {
			$values['access_id'] = $game->future_access;
		}
	}

	if (elgg_is_sticky_form('game')) {
		$sticky_values = elgg_get_sticky_values('game');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}
	
	elgg_clear_sticky_form('game');

	if (!$game) {
		return $values;
	}
	//TODO: Integrate revisions & auto_draft
	// load the revision annotation if requested
	/*
	if ($revision instanceof ElggAnnotation && $revision->entity_guid == $game->getGUID()) {
		$values['revision'] = $revision;
		$values['description'] = $revision->value;
	}

	// display a notice if there's an autosaved annotation
	// and we're not editing it.
	if ($auto_save_annotations = $game->getAnnotations('blog_auto_save', 1)) {
		$auto_save = $auto_save_annotations[0];
	} else {
		$auto_save = false;
	}

	if ($auto_save && $auto_save->id != $revision->id) {
		$values['draft_warning'] = elgg_echo('blog:messages:warning:draft');
	}
	*/
	return $values;
}
function get_own_games_read($page, $username = 'all'){

	$username == 'all' ? $title = elgg_echo('kpax:all'): $title = elgg_echo('kPAX:myowngames');

	$data['username'] = $username;
	$data['page'] = $page;
	$content = elgg_view('kpax/game/search', $data);
    $params = array(
    	'content' => $content,
    	'title' => $title,
    	'filter' => false,  // All, Mine and Friends tabs are not shown
    	);
   return $params;
}
/**
 * Build a game help page
 *
 * @return array $params parameters to build a default page
 */
function kpax_help_page(){
    $title = elgg_echo('kpax:devs:explanations:title');

    // "My games" button
    elgg_register_menu_item('title', array(
                     'name' => 'all_games',
                     'href' => 'kpax/game/all',
                     'text' => elgg_echo('kpax:all'),
                     'link_class' => 'elgg-button elgg-button-action',
                 ));
    // "My games" button
    elgg_register_menu_item('title', array(
                     'name' => 'my_games',
                     'href' => 'kpax/game/own',
                     'text' => elgg_echo('kPAX:myGames'),
                     'link_class' => 'elgg-button elgg-button-action',
                 ));

    // "Add games" button
    elgg_register_menu_item('title', array(
                     'name' => 'add',
                     'href' => 'kpax/game/add',
                     'text' => elgg_echo('kPAX:add'),
                     'link_class' => 'elgg-button elgg-button-action',
                 ));

    $content = elgg_view('kpax/devs/explanations');
    $params = array(
    'content' => $content,
    'title' => $title,
    'filter' => false,  // All, Mine and Friends tabs are not shown
    );

    return $params;
}

/**
 * Build default game image
 *
 * @param array $class 
 *
 * @return array $params parameters to build a default page
 */
function kpax_default_game_image($vars){
	$file_path = elgg_get_site_url() .'mod/kpax/graphics/default_game_image.png';
	//$file_path = elgg_get_data_path() . $vars['file_path'];
	$contents = file_get_contents($file_path);
	$base64 = base64_encode($contents);
	$vars['src'] = 'data:image/png;base64,' . $base64;
	$attributes = elgg_format_attributes($vars);
	return "<img $attributes/>";
}

/**
 * Temporal upload function
 * Based file plugin
 * 
 * TODO: Delete this function and replace with an apropiate plugin with drag&drop utility
 *
 * @param array $file_name
 *
 * @return array $image_path Path where image resides.
 */
function kpax_upload_file_valid($file_name) {

// check if upload failed
if (!empty($_FILES[$file_name]['name']) && $_FILES[$file_name]['error'] != 0) {
	register_error(elgg_echo('file:cannotload'));
	forward(REFERER);
}

// check whether this is a new file or an edit
$new_file = true;
/*if ($guid > 0) {
	$new_file = false;
}
*/
if ($new_file) {
	// must have a file if a new file upload
	if (empty($_FILES[$file_name]['name'])) {
		$error = elgg_echo('file:nofile');
		register_error($error);
		forward(REFERER);
	}

	$file = new FilePluginFile();
	$file->subtype = "file";

} else {
	// load original file object
	$file = new FilePluginFile($guid);
	if (!$file) {
		register_error(elgg_echo('file:cannotload'));
		forward(REFERER);
	}

	// user must be able to edit file
	if (!$file->canEdit()) {
		register_error(elgg_echo('file:noaccess'));
		forward(REFERER);
	}

	if (!$title) {
		// user blanked title, but we need one
		$title = $file->title;
	}
}

// we have a file upload, so process it
if (isset($_FILES[$file_name]['name']) && !empty($_FILES[$file_name]['name'])) {

	$prefix = "file/";

	// if previous file, delete it
	if ($new_file == false) {
		$filename = $file->getFilenameOnFilestore();
		if (file_exists($filename)) {
			unlink($filename);
		}

		// use same filename on the disk - ensures thumbnails are overwritten
		$filestorename = $file->getFilename();
		$filestorename = elgg_substr($filestorename, elgg_strlen($prefix));
	} else {
		$filestorename = elgg_strtolower(time().$_FILES[$file_name]['name']);
	}

	$file->setFilename($prefix . $filestorename);
	$mime_type = ElggFile::detectMimeType($_FILES[$file_name]['tmp_name'], $_FILES[$file_name]['type']);

	$file->setMimeType($mime_type);
	$file->originalfilename = $_FILES[$file_name]['name'];
	$file->simpletype = file_get_simple_type($mime_type);

	// Open the file to guarantee the directory exists
	$file->open("write");
	$file->close();

	move_uploaded_file($_FILES[$file_name]['tmp_name'], $file->getFilenameOnFilestore());
	$file_path = explode(elgg_get_data_path(), $file->getFilenameOnFilestore());
	return $file_path[1];
}
/*	$guid = $file->save();

	// if image, we need to create thumbnails (this should be moved into a function)
	if ($guid && $file->simpletype == "image") {
		$file->icontime = time();
		
		$thumbnail = get_resized_image_from_existing_file($file->getFilenameOnFilestore(), 60, 60, true);
		if ($thumbnail) {
			$thumb = new ElggFile();
			$thumb->setMimeType($_FILES[$file_name]['type']);

			$thumb->setFilename($prefix."thumb".$filestorename);
			$thumb->open("write");
			$thumb->write($thumbnail);
			$thumb->close();

			$file->thumbnail = $prefix."thumb".$filestorename;
			unset($thumbnail);
		}

		$thumbsmall = get_resized_image_from_existing_file($file->getFilenameOnFilestore(), 153, 153, true);
		if ($thumbsmall) {
			$thumb->setFilename($prefix."smallthumb".$filestorename);
			$thumb->open("write");
			$thumb->write($thumbsmall);
			$thumb->close();
			$file->smallthumb = $prefix."smallthumb".$filestorename;
			unset($thumbsmall);
		}

		$thumblarge = get_resized_image_from_existing_file($file->getFilenameOnFilestore(), 600, 600, false);
		if ($thumblarge) {
			$thumb->setFilename($prefix."largethumb".$filestorename);
			$thumb->open("write");
			$thumb->write($thumblarge);
			$thumb->close();
			$file->largethumb = $prefix."largethumb".$filestorename;
			unset($thumblarge);
		}
	}
} else {
	// not saving a file but still need to save the entity to push attributes to database
	$file->save();
}

// file saved so clear sticky form
elgg_clear_sticky_form('file');


// handle results differently for new files and file updates
if ($new_file) {
	if ($guid) {
		$message = elgg_echo("file:saved");
		system_message($message);
		add_to_river('river/object/file/create', 'create', elgg_get_logged_in_user_guid(), $file->guid);
	} else {
		// failed to save file object - nothing we can do about this
		$error = elgg_echo("file:uploadfailed");
		register_error($error);
	}

	$container = get_entity($container_guid);
	if (elgg_instanceof($container, 'group')) {
		forward("file/group/$container->guid/all");
	} else {
		forward("file/owner/$container->username");
	}

} else {
	if ($guid) {
		system_message(elgg_echo("file:saved"));
	} else {
		register_error(elgg_echo("file:uploadfailed"));
	}

	forward($file->getURL());
}
*/
}