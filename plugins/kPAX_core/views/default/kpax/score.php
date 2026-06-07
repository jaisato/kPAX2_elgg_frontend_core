<?php
if ($vars['objScore']) {
    ?>
    <p>&nbsp;</p>
    <h4><?= elgg_echo('kpax:game:score'); ?></h4>
    <div class='score' style='border:1px solid #cccccc; padding:5px; padding-left: 12px; text-align:left;width: 220px;'>
        <?php
        foreach ($vars['objScore'] as $value) {
            // SECURITY FIX: Escape output to prevent XSS
            echo "<p>" . htmlspecialchars($value->nameUser, ENT_QUOTES, 'UTF-8') . " - " . htmlspecialchars($value->points, ENT_QUOTES, 'UTF-8') . "</p>";
        }
        ?>
    </div>
    <?php
}

?>
