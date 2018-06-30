<?php
	/**
	 * kPAX2 Games Developers main page
	 *
	 */
?>

<div>
	<h2>Instructions for Games Developers</h2>
		<br>
		<p>kPAX philosophy is based in free software and open source. All the code is available under the 
		<A HREF="http://opensource.org/licenses/gpl-2.0.php">GNU General Public License, version 2 (GPL-2.0)</A>. 
		For those developers interested in participating in the kPAX project, there are two ways to do it: 
		On the one hand, it is possible to 
		<strong>add functions/improvements to the platform</strong>. On the other hand, new external 
		<strong>games, apps, etc.</strong> to offer to users (players) are welcome. Let us see how to do it.
		</p>
		<br>
	<h2>Games development</h2>
		<p> If you want to develop a game (do not forget kPAX is only for educative ones) you only need to 
		insert in your code the necessary calls to kPAX engine which validate users, the game itself, 
		and send/receive information to/from the database.
		</p>
		<p>
		The most simple relationship between a game and kPAX might imply user and game validation, 
		and at the end, send the score to be incorporated to the user's game information to update the database.
		</p>
		<p> To do this, some security has to be added to communication between games and the platform. 
		Security issues in kPAX are dealed with	by means of: the validation of games using a public/private key pair, 
		and users autorisation, using <A HREF=http://oauth.net/>OAuth</A>. This means the developer has to create 
		the game's public/private key pair and include the public one in the game's form. The private is
		used to sign every message sent to kPAX, which can certify it corresponds to the corresponding game.
		</p>
	</div>