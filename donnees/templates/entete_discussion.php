<?php

/*  * /
if (current_user_can("manage_options")) {
	aff($d);
}
/*  */


$tache_faite = (int) get_post_meta($d["id_tache"], "tache_faite", TRUE);

$nonce = "modification_tache_$d[id_tache]";

?>
<div class="entete_discussion">
	
	<form
		action="<?php echo htmlspecialchars(admin_url("admin-post.php?action=bbTaches__modification_tache"));?>"
		method="POST"
	>
		<input
			type="hidden"
			name="id_tache"
			value="<?php echo htmlspecialchars($d["id_tache"]);?>"
		/>
		
		<?php wp_nonce_field($nonce, $nonce);?>
		
		<?php if (1 === $tache_faite) {?>
			
			Tâche faite
			
			<input
				type="submit"
				name="tache_en_cours"
				value="Remettre la tâche en cours"
			/>
			
		<?php } else {?>
			
			Tâche en cours
			
			<input
				type="submit"
				name="tache_faite"
				value="Marquer la tâche comme faite"
			/>
			
		<?php }?>
		
	</form>
	
</div>


