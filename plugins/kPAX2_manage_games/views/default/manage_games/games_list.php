<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="../font-awesome-4.7.0/css/font-awesome.min.css">
		<style>
			th,td#td1 {background-color:#E5E5E5; color:#000000; font-family:"Sans-serif", Arial, Verdana; font-size:12px;font-weight: bold;}
			td {background-color:#FFFFFF; color:#000000; font-family:"Sans-serif", Arial, Verdana; font-size:12px;}
			th, td {padding: 10px;}
		</style>
	</head>
	
	<body>

		<?php
			if ($vars['objGameList']) {
		?>

		<table id="t1" style="width:100%">
				<tr>
					<th width=2%>ID</th>
					<th width=18%>Name</th>
					<th width=40%>Description</th>
					<th width=5%>Cat</th>
					<th width=14%>Platform</th>
					<td id="td1" colspan=3 width=21%>Actions</th>
				</tr>

				<?php
					foreach ($vars['objGameList'] as $game) {
				?>
				<tr>
					<td width=2%><?=$game->guid;?></td>
					<td width=18%><?=$game->name;?></td>
					<td width=40%><?=$game->description;?></td>
					<td width=5%><?=$game->category;?></td>
					<td width=14%><?=$game->created_at;?></td> 
					<td><a href=<?php echo elgg_view_icon('eye fa-lg');?></a>View</td>
					<td><a href=<?php echo elgg_view_icon('pencil-square-o fa-lg');?></a>Edit</td>
					<td><a href=<?php echo elgg_view_icon('trash fa-lg');?></a>Delete</td>
				</tr>
				<?php
					}
				?>
		</table>
		<?php
		} else { 
			elgg_echo('kPAX:noGames');
		}
	?>
	</body>
</html>