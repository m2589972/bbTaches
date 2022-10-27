<?php

/*  * /
if (current_user_can("manage_options")) {
	aff($d);
}
/*  */


?>
<style>
	#bbpress-forums #bbp-forum-<?php echo htmlspecialchars($d["forum"]->ID);?>
	{
		background-color : <?php echo htmlspecialchars($d["forum"]->couleur_principale);?>;
	}
</style>


