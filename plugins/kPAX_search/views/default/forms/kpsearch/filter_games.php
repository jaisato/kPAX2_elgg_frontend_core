<?php
$title = elgg_extract('title', $vars, '');
$category = elgg_extract('category', $vars, ''); 
//TODO: Falten les plataformes
$skills = elgg_extract('skills', $vars, '');
$tags = elgg_extract('tags', $vars, '');
?>

<div>
    <label><?php echo elgg_echo('kPAX:game:name'); ?></label><br />
    <?php echo elgg_view('input/text', array('name' => 'title', 'value' => $title)); ?>
</div>

<div>
    <label><?php echo elgg_echo('kPAX:game:category'); ?></label><br />
    <?php echo elgg_view('input/text', array('name' => 'category', 'value' => $category)); ?>
</div>

<div> 
    <label><?php echo elgg_echo('kPAX:game:platforms'); ?></label><br />
    <?php echo elgg_view('input/checkboxes', array('name' => "platforms", //TODO: It'd be great to include the logos
                                                   'options' => array('web' => '1', 
                                                                      'android' => '2',
                                                                      'iOS' => '3',
                                                                      'Nintendo DS' => '4',
                                                                      'PSP' => '5',
                                                                      'Nintendo Wii' => '6',
                                                                      'XBox' => '7'))); ?>
</div>

<div>
    <label><?php echo elgg_echo('kPAX:game:skills'); ?></label><br />
    <?php echo elgg_view('input/tags', array('name' => 'skills', 'value' => $skills)); ?>
</div>

<div>
    <label><?php echo elgg_echo('kPAX:game:tags'); ?></label><br />
    <?php echo elgg_view('input/tags', array('name' => 'tags', 'value' => $tags)); ?>
</div>

<div>
    <?php echo elgg_view('input/submit', array('value' => elgg_echo('kPAX:gamesearch:send'))); ?>
</div>