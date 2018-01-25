<?php
/**
 * Bookmarks Catalan language file
 */

$catalan = array(

/**
 * Sessions
 */

	'login' => "Valida",
	'loginok' => "Has completat la teva validaci&oacute;",
	'loginerror' => "No hem pogut validar-te. Prova de nou",
	'login:empty' => "Requereix nom d'usuari/correu electr&ograve;nic i paraula de pas",
	'login:baduser' => "No es pot carregar el teu compte d'usuari",
	'auth:nopams' => "Error intern. No hi ha m&egrave;tode de validaci&oacute; instal·lat",

	'logout' => "Surt",
	'logoutok' => "Has sortit de kPAX",
	'logouterror' => "No s'ha pogut sortir. Si us plau, prova de nou",

	'loggedinrequired' => "Has d'estar validat per veure aquesta p&agrave;gina",
	'adminrequired' => "Has de ser administrador per veure aquesta p&agrave;gina",
	'membershiprequired' => "Has de ser un membre d'aquest grup per veure aquesta p&agrave;gina",

	/**
	 * Menu items and titles
	 */
	'bookmarks' => "Bookmarks",
	'bookmarks:add' => "Add bookmark",
	'bookmarks:edit' => "Edit bookmark",
	'bookmarks:owner' => "%s's bookmarks",
	'bookmarks:friends' => "Friends' bookmarks",
	'bookmarks:everyone' => "All site bookmarks",
	'bookmarks:this' => "Bookmark this page",
	'bookmarks:this:group' => "Bookmark in %s",
	'bookmarks:bookmarklet' => "Get bookmarklet",
	'bookmarks:bookmarklet:group' => "Get group bookmarklet",
	'bookmarks:inbox' => "Bookmarks inbox",
	'bookmarks:morebookmarks' => "More bookmarks",
	'bookmarks:more' => "More",
	'bookmarks:with' => "Share with",
	'bookmarks:new' => "A new bookmark",
	'bookmarks:via' => "via bookmarks",
	'bookmarks:address' => "Address of the resource to bookmark",
	'bookmarks:none' => 'No bookmarks',

	'bookmarks:delete:confirm' => "Are you sure you want to delete this resource?",

	'bookmarks:numbertodisplay' => 'Number of bookmarks to display',

	'bookmarks:shared' => "Bookmarked",
	'bookmarks:visit' => "Visit resource",
	'bookmarks:recent' => "Recent bookmarks",

	'river:create:object:bookmarks' => '%s bookmarked %s',
	'river:comment:object:bookmarks' => '%s commented on a bookmark %s',
	'bookmarks:river:annotate' => 'a comment on this bookmark',
	'bookmarks:river:item' => 'an item',

	'item:object:bookmarks' => 'Bookmarks',

	'bookmarks:group' => 'Group bookmarks',
	'bookmarks:enablebookmarks' => 'Enable group bookmarks',
	'bookmarks:nogroup' => 'This group does not have any bookmarks yet',
	'bookmarks:more' => 'More bookmarks',

	'bookmarks:no_title' => 'No title',

	/**
	 * Widget and bookmarklet
	 */
	'bookmarks:widget:description' => "Display your latest bookmarks",

	'bookmarks:bookmarklet:description' =>
			"The bookmarks bookmarklet allows you to share any resource you find on the web with your friends, or just bookmark it for yourself. To use it, simply drag the following button to your browser's links bar:",

	'bookmarks:bookmarklet:descriptionie' =>
			"If you are using Internet Explorer, you will need to right click on the bookmarklet icon, select 'add to favorites', and then the Links bar.",

	'bookmarks:bookmarklet:description:conclusion' =>
			"You can then save any page you visit by clicking it at any time.",

	/**
	 * Status messages
	 */

	'bookmarks:save:success' => "Your item was successfully bookmarked.",
	'bookmarks:delete:success' => "Your bookmarked item was successfully deleted.",

	/**
	 * Error messages
	 */

	'bookmarks:save:failed' => "Your bookmark could not be saved. Make sure you've entered a title and address and then try again.",
	'bookmarks:delete:failed' => "Your bookmark could not be deleted. Please try again.",

    'kPAX:play' => "Jocs",
    'kPAX:devs' => "Desenvolupa",
    'kPAX:add' => "Afegeix joc",
    'kPAX:game:name' => "Nom del joc (*)",
    'kPAX:game:description' => "Descripci&oacute;",
    'kPAX:game:skills' => "Compet&egrave;ncies (*)",
    'kPAX:game:category' => "Categoria/es del joc",
    'kPAX:game:platforms' => "Plataformes disponibles",
    'kPAX:game:creationDate' => "Data de creaci&oacute;",
    'kPAX:game:tags' => "Etiquetes (tags) associades",
    'kPAX:game:csr_file' => "Fitxer de petici&oacute; de certificat [.csr] (*)",
    'kPAX:game:send' => "Envia el meu joc!",
    'kPAX:myGames' => "Els meus jocs",
    'kPAX:noGames' => "Encara no tens jocs a kPAX",
    'kPAX:my_dev_games' => "Els meus jocs (desenvolupats)",
    'kPAX:manage_games'=> "Gestionar el meus jocs",
    'kpax:tagline' => 'Juguem seriosament!',
	'kpax:dragdrop' => 'També pots arrossegar una imatge desde l\'escriptori'
);

add_translation('ca', $catalan);

?>