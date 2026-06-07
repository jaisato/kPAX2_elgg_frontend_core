<?php
if ($vars['objGameList']) {
    ?>
    <p>&nbsp;</p>
    <h4><?= elgg_echo('kPAX:play'); ?></h4>
    <div class='score' style='border:1px solid #cccccc; padding:5px; padding-left: 12px; text-align:left;width: 220px;'>
        <p><b>Id - Game - Category</b></p>
        <hr>
        <?php
        foreach ($vars['objGameList'] as $game) {
            // SECURITY FIX: Escape output to prevent XSS
            echo "<p>" . htmlspecialchars($game->guid, ENT_QUOTES, 'UTF-8') . " - " . "<a href=\"view/" . htmlspecialchars($game->guid, ENT_QUOTES, 'UTF-8') . "\"> " . htmlspecialchars($game->name, ENT_QUOTES, 'UTF-8') . " </a>" . htmlspecialchars($game->category, ENT_QUOTES, 'UTF-8') . "</p>";
        }
        ?>
    </div>
    <?php
}
else { 
	elgg_echo('kPAX:noGames');
}
?> 