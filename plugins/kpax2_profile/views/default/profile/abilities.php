<div id="abilities" style="clear: both">
    <table width="100%">
        <caption>Abilities</caption>
        <thead>
            <tr>
                <td>Name</td>
                <td>Level</td>
                <td>Time in level</td>
                <td>Max level</td>
                <td>Time in max level</td>
                <td>Experience</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($vars['abilities'] as $ability) { ?>
            <tr>
                <td><?= $ability->name; ?></td>
                <td><?= $ability->level; ?></td>
                <td><?= $ability->time_level; ?></td>
                <td><?= $ability->max_level; ?></td>
                <td><?= $ability->time_max_level; ?></td>
                <td><?= $ability->experience; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>