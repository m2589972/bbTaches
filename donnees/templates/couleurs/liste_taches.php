<?php

/*  * /
if (current_user_can("manage_options")) {
	aff($d);
}
/*  */


?>

<style>

<?php foreach ($d["forums"] as $forum) {?>
	
	.liste_taches .forum_<?php echo htmlspecialchars($forum->ID);?>
	{
		color : #000;
		background-color : <?php echo htmlspecialchars($forum->couleur_principale);?>;
	}
	
<?php }?>

</style>


