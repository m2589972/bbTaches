<?php

/*  * /
if (current_user_can("manage_options")) {
	aff($d);
}
/*  */

$date_proche = time() + 72 * HOUR_IN_SECONDS;
$date_tres_proche = time() + 24 * HOUR_IN_SECONDS;

$message = $_GET["message"] ?? NULL;


$nonce = "liste_taches_$d[id_utilisateur]";


// gestion du déplacement des tâches à la souris

wp_enqueue_script("jquery-ui-sortable");

$url_extension = apply_filters("bbTaches/url_extension", NULL);
$version_extension = apply_filters("bbTaches/version_extension", NULL);

wp_enqueue_script(
	  "bbTaches__liste_taches"
	, "$url_extension/liens/js/liste_taches.js"
	, ["jquery-ui-sortable"]
	, $version_extension
);


?>

<?php
	do_action("bbTaches/template", "couleurs/liste_taches");
?>

<div class="conteneur_liste_taches">
	
	<?php if (
			isset($message)
		&&	("enregistre" === $message)
	) {?>
		<div class="message succes"><span>
			Les modifications ont bien été enregistrées.
		</span></div>
	<?php }?>
	
	<h2>
		<?php echo htmlspecialchars($d["titre"]);?>
	</h2>
	
	<form
		action="<?php echo htmlspecialchars(admin_url("admin-post.php?action=bbTaches__liste_taches"));?>"
		method="POST"
	>
		
		<?php if (0 === count($d["taches"])) {?>
			
			<div>
				Aucune tâche
			</div>
			
		<?php } else {?>
			
			<?php if (get_current_user_id() === $d["id_utilisateur"]) {?>
				
				<?php wp_nonce_field($nonce, $nonce);?>
				
				<input
					type="submit"
					class="enregistre_dates"
					name="enregistre_dates"
					value="Enregistrer les dates"
				/>
				
			<?php }?>
			
			<div class="liste_taches">
				
				<?php foreach ($d["taches"] as $tache) {?>
					
					<?php
						$forum = $d["forums"][bbp_get_topic_forum_id($tache->ID)];
						
						$classes = "tache";
						
						if ($tache->date_traitement < $date_proche) {
							$classes .= " proche";
						}
						
						if ($tache->date_traitement < $date_tres_proche) {
							$classes .= " tres_proche";
						}
						
					?>
					
					<div class="<?php echo htmlspecialchars($classes);?>">
						
						<span class="titre">
							<a href="<?php echo htmlspecialchars(get_permalink($tache->ID));?>">
								<?php echo htmlspecialchars($tache->post_title);?></a>
						</span>
						
						<span class="forum forum_<?php echo htmlspecialchars($forum->ID);?>">
							<?php echo htmlspecialchars($forum->post_title);?>
						</span>
						
						<?php
							
							do_action("bbTaches/donnees_template", [
								"id_tache" => $tache->ID,
								"utilisateur_attribue" => $d["id_utilisateur"],
								"date_traitement" => (int) $tache->date_traitement,
								"affichage_court" => TRUE,
							]);
							
							do_action("bbTaches/template", "date_traitement");
							
						?>
						
					</div>
					
				<?php }?>
				
			</div>
			
			<?php if (get_current_user_id() === $d["id_utilisateur"]) {?>
				
				<input
					type="submit"
					class="enregistre_dates"
					name="enregistre_dates"
					value="Enregistrer les dates"
				/>
				
			<?php }?>
			
		<?php }?>
		
	</form>
	
</div>


