<?php

add_action("admin_post_bbTaches__modification_tache", function () {
	
	// appel POST
	
	
	if (	!current_user_can("read_private_forums")
		||	!isset($_POST["id_tache"])
	) {
		return;
	}
	
	$id_tache = $_POST["id_tache"];
	
	
	$utilisateur_attribue = (int) get_post_meta($id_tache, "utilisateur_attribue", TRUE);
	
	if (get_current_user_id() !== $utilisateur_attribue) {
		return;
	}
	
	
	$nonce = "modification_tache_$id_tache";
	check_admin_referer($nonce, $nonce);
	
	
	if (isset($_POST["tache_faite"])) {
		update_post_meta($id_tache, "tache_faite", 1);
	}
	
	if (isset($_POST["tache_en_cours"])) {
		update_post_meta($id_tache, "tache_faite", 0);
		update_post_meta($id_tache, "date_traitement", "");
	}
	
	
	// redirection
	$url_tache = get_permalink($id_tache);
	wp_redirect($url_tache);
	exit();
	
});


add_action("bbTaches/maj_discussion", function ($id_tache, $id_tache_post) {
	
	
	if (	isset($_POST["jour_traitement"][$id_tache_post])
		&&	isset($_POST["heures_traitement"][$id_tache_post])
		&&	isset($_POST["minutes_traitement"][$id_tache_post])
	) {
		
		if ("" === $_POST["jour_traitement"][$id_tache_post]) {
			$date_traitement = "";
		} else {
			
			$date_traitement = apply_filters(
				  "bbTaches/mktime"
				, NULL
				, $_POST["jour_traitement"][$id_tache_post]
				, $_POST["heures_traitement"][$id_tache_post]
				, $_POST["minutes_traitement"][$id_tache_post]
				, "00"
			);
			
		}
		
		update_post_meta($id_tache, "date_traitement", $date_traitement);
		
	}
	
	
	if (isset($_POST["utilisateur_attribue"][$id_tache_post])) {
		
		$utilisateur_attribue_precedent = (int) get_post_meta($id_tache, "utilisateur_attribue", TRUE);
		
		$utilisateur_attribue = (int) $_POST["utilisateur_attribue"][$id_tache_post];
		update_post_meta($id_tache, "utilisateur_attribue", $utilisateur_attribue);
		
		if (get_current_user_id() !== $utilisateur_attribue) {
			update_post_meta($id_tache, "date_traitement", "");
		}
		
		if ($utilisateur_attribue !== $utilisateur_attribue_precedent) {
			update_post_meta(
				  $id_tache
				, "utilisateur_attribue_precedent"
				, $utilisateur_attribue_precedent
			);
		}
		
	}
	
}, 10, 2);


add_filter("bbTaches/taches_utilisateur" , function ($_, $id_utilisateur) {
	
	$taches = get_posts([
		"post_type" => bbp_get_topic_post_type(),
		"meta_query" => [
			[
				"key" => "utilisateur_attribue",
				"value" => $id_utilisateur,
			],
			[
				"relation" => "OR",
				[
					"key" => "tache_faite",
					"value" => 0,
				],
				[
					"key" => "tache_faite",
					"compare" => "NOT EXISTS",
				],
			],
		],
		"meta_key" => "date_traitement",
		"orderby" => "meta_value",
		"order" => "ASC",
	]);
	
	
	return $taches;
	
}, 10, 2); // add_filter("bbTaches/taches_utilisateur" , function ($_, $id_utilisateur) {


