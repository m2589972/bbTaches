<?php

add_action("bbp_get_single_topic_description", function () {
	
	$forum = bbp_get_forum(0);
	
	if (	(1 === (int) $forum->type_taches)
		&&	("" !== $forum->couleur_principale)
	) {
		
		do_action("bbTaches/donnees_template", [
			"forum" => $forum,
		]);
		
		do_action("bbTaches/template", "couleurs/voir_discussion");
		
	}
	
});


add_action("bbp_template_before_single_forum", function () {
	
	$forum = bbp_get_forum(0);
	
	if (	(1 === (int) $forum->type_taches)
		&&	("" !== $forum->couleur_principale)
	) {
		
		do_action("bbTaches/donnees_template", [
			"forum" => $forum,
		]);
		
		do_action("bbTaches/template", "couleurs/liste_discussions");
		
	}
	
});


add_action("bbp_theme_before_forum_title", function () {
	
	$forum = bbp_get_forum(0);
	
	if (	(1 === (int) $forum->type_taches)
		&&	("" !== $forum->couleur_principale)
	) {
		
		do_action("bbTaches/donnees_template", [
			"forum" => $forum,
		]);
		
		do_action("bbTaches/template", "couleurs/accueil_forums");
		
	}
	
});


add_action("bbp_forum_metabox", function ($forum) {
	
	wp_enqueue_script("wp-color-picker");
	
	
	?>
		<hr/>
		
		<div>
			<label>
				<input
					type="checkbox"
					name="type_taches"
					<?php echo (!$forum->type_taches ? "" : " checked=\"checked\"");?>
				/>
				
				Type tâches
			</label>
		</div>
		
		<?php if ($forum->type_taches) {?>
			
			<div class="champ_couleur">
				<input
					type="text"
					name="couleur_principale"
					value="<?php echo htmlspecialchars($forum->couleur_principale);?>"
				/>
			</div>
			
			<script>
				"use strict";
				
				jQuery($ => {
					
					$(".champ_couleur input").wpColorPicker({});
					
					document
						.querySelector(".champ_couleur .wp-color-result-text")
						.textContent
							 = "Couleur principale"
					;
					
				});
			</script>
			
		<?php }?>
		
	<?php
	
	
}, 10, 1); // FIN add_action("bbp_forum_metabox", function ($forum) {


add_action("bbp_forum_attributes_metabox_save", function ($id_forum) {
	
	
	update_post_meta(
		  $id_forum
		, "type_taches"
		, isset($_POST["type_taches"]) ? 1 : 0
	);
	
	
	if (isset($_POST["couleur_principale"])) {
		
		update_post_meta(
			  $id_forum
			, "couleur_principale"
			, $_POST["couleur_principale"]
		);
		
	}
	
	
});


