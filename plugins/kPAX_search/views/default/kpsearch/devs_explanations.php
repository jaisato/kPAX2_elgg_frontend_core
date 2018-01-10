<?php
/**
 * kPAX developers main page
 *
 */
?>

<div>
<h2>Instructions for developers</h2>
<br>
<p>kPAX philosophy is based in free software and open source. All the code is available under the 
<A HREF="http://opensource.org/licenses/gpl-2.0.php">GNU General Public License, version 2 (GPL-2.0)</A>. For those developers
interested in participating in the kPAX project, there are two ways to do it: On the one hand, it is possible to <strong>add functions/improvements to the platform</strong>. On the other hand, 
	new external <strong>games, apps, etc.</strong> to offer to users (players) are welcome. Let us see how to do it.
</p>
<br>
<h3>Platform construction</h3>

<p>In case you want to participate in the kPAX platform construction, the code of the stable version is public in github. There, you can find 
	both:
	<ul>
		<li> 1.- <strong>elgg plugins</strong> -  <A HREF=https://github.com/jsanchezramos/mods-kpax>mods-kpax repository</A>
		<li> 2.- <strong>kPAX services</strong> - <A HREF=https://github.com/jsanchezramos/k-pax>k-pax repository</A>
	</ul>
<br>
Both, the information on how to install a local version of kPAX and a few ideas on desired improvements can be found 
in the <A HREF=https://github.com/jsanchezramos/k-pax/wiki>k-pax repository wiki</A>. Feel free to complement/fix/etc. it.
</p>
<br>
<h3>Games development</h3>

<p> If you want to develop a game (do not forget kPAX is only for educative ones) you only need to insert in your code
the necessary calls to kPAX engine which validate users, the game itself, and send/receive information to/from the database.
The most simple relationship between a game and kPAX might imply user and game validation, and at the end, send the score to be incorporated to the
user's game information to update the database.
</p>

<p> To do this, some security has to be added to communication between games and the platform. Security issues in kPAX are dealed with
	by means of: the validation of games using a public/private key pair, and users autorisation, using <A HREF=http://oauth.net/>OAuth</A>.
	This means the developer has to create the game's public/private key pair and include the public one in the game's form. The private is
	used to sign every message sent to kPAX, which can certify it corresponds to the corresponding game.
</p>
<br>
<h3>Apps development</h3>
<p>Bla bla bla (pensar si les aplicacions tambe han de estar donades d'alta a la plataforma o no)
</p>
</div>