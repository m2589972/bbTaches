<?php

/*  * /
if (current_user_can("manage_options")) {
	aff($d);
}
/*  */


$utilisateurs = apply_filters("bbTaches/utilisateurs", NULL);


$nom_utilisateur_attribue_precedent = "";


?>

<div class="details_discussion">
	
	<div class="utilisateur_attribue">
		
		<?php if (
			(
					(get_current_user_id() !== $d["utilisateur_attribue"])
				&&	(!current_user_can( 'moderate', $d["id_tache"]))
			)
			||
			(
					(0 !== $d["id_tache"])
				&&	(1 === (int) bbp_get_topic($d["id_tache"])->tache_faite)
			)
		) {?>
			
			Utilisateur attribué&nbsp;:
			
			<?php
				foreach ($utilisateurs as $utilisateur) {
					
					if ($utilisateur->ID === $d["utilisateur_attribue"]) {
						echo htmlspecialchars($utilisateur->display_name);
						break;
					}
					
				}
			?>
			
		<?php } else {?>
			
			<label>
				Utilisateur attribué&nbsp;:
				
				<select name="utilisateur_attribue[<?php echo htmlspecialchars($d["id_tache"]);?>]">
					<?php foreach ($utilisateurs as $utilisateur) {?>
						
						<?php
							if ($utilisateur->ID === $d["utilisateur_attribue_precedent"]) {
								$nom_utilisateur_attribue_precedent = 
									$utilisateur->display_name
								;
							}
						?>
						<option
							value="<?php echo htmlspecialchars($utilisateur->ID);?>"
							<?php echo ($utilisateur->ID !== $d["utilisateur_attribue"])
								? "" : " selected=\"selected\"";?>
						>
							<?php echo htmlspecialchars($utilisateur->display_name);?>
						</option>
					<?php }?>
				</select>
				
			</label>
			
			<?php if (0 !== $d["utilisateur_attribue_precedent"]) {?>
				
				<span
					class="espace_attribue_precedent"
					data-id-utilisateur="<?php echo htmlspecialchars($d["utilisateur_attribue_precedent"]);?>"
					data-nom-utilisateur="<?php echo htmlspecialchars($nom_utilisateur_attribue_precedent);?>"
				>
				</span>
				
			<?php }?>
			
		<?php }?>
		
	</div>
	
	<?php do_action("bbTaches/template", "date_traitement");?>
	
</div>


