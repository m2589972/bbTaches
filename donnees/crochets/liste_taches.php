<?php

add_action("admin_post_bbTaches__liste_taches", function () {
	
	// appel POST
	
	
	if (	!current_user_can("read_private_forums")
		||	!isset($_POST["enregistre_dates"])
		||	!isset($_POST["jour_traitement"])
	) {
		return;
	}
	
	
	$id_utilisateur = get_current_user_id();
	
	
	$nonce = "liste_taches_$id_utilisateur";
	check_admin_referer($nonce, $nonce);
	
	
	foreach (array_keys($_POST["jour_traitement"]) as $id_tache) {
		
		$utilisateur_attribue = (int) get_post_meta($id_tache, "utilisateur_attribue", TRUE);
		
		if ($id_utilisateur !== $utilisateur_attribue) {
			return;
		}
		
		
		do_action("bbTaches/maj_discussion", $id_tache, $id_tache);
		
	}
	
	
	// redirection
	
	$url_forums = get_post_type_archive_link(bbp_get_forum_post_type());
	$url_taches = "$url_forums?liste_taches=$id_utilisateur&message=enregistre";
	
	wp_redirect($url_taches);
	exit();
	
});


add_filter("document_title_parts", function ($tab_titre) {
	
	if (isset($GLOBALS["bbTaches"]["titre_taches"])) {
		$tab_titre["title"] = $GLOBALS["bbTaches"]["titre_taches"];
	}
	
	
	return $tab_titre;
	
});


add_action("bbp_locate_template", function ($located, $template_name, $template_names, $template_locations, $load, $require_once) {
	
	if (	current_user_can("read_private_forums")
		&&	(FALSE === $located)
		&&	("bbTaches__liste_taches" === $template_name)
		&&	isset($_GET["liste_taches"])
		&&	user_can($_GET["liste_taches"], "read_private_forums")
	) {
		
		$id_utilisateur = (int) $_GET["liste_taches"];
		$utilisateur = get_userdata($id_utilisateur);
		
		$taches = apply_filters("bbTaches/taches_utilisateur", NULL, $id_utilisateur);
		
		
		$liste_forums = [];
		$nombres_taches_sans_date = 0;
		
		foreach ($taches as $tache) {
			
			// préparation des forums
			$liste_forums[] = bbp_get_topic_forum_id($tache->ID);
			
			// recherche des tâches sans date
			if ("" === $tache->date_traitement) {
				$nombres_taches_sans_date++;
			}
			
		}
		
		
		// informations des forums
		
		$liste_forums = array_unique($liste_forums);
		
		$liste_forums = get_posts([
			"post_type" => bbp_get_forum_post_type(),
			"post__in" => $liste_forums,
		]);
		
		$forums = [];
		
		foreach ($liste_forums as $forum) {
			$forums[$forum->ID] = $forum;
		}
		
		
		// préparation du titre
		
		$titre = "";
		
		if (get_current_user_id() === $id_utilisateur) {
			$titre = "Mes tâches";
		} else {
			$titre = "Tâches de $utilisateur->display_name";
		}
		
		if ($nombres_taches_sans_date > 0) {
			$titre .= " ($nombres_taches_sans_date)";
		}
		
		$GLOBALS["bbTaches"]["titre_taches"] = $titre;
		
		
		// affichage
		
		do_action("bbTaches/donnees_template", [
			"taches" => $taches,
			"forums" => $forums,
			"titre" => $titre,
			"id_utilisateur" => $id_utilisateur,
		]);
		
		do_action("bbTaches/template", "liste_taches");
		
	} // FIN préparation liste des tâches
	
}, 10, 6); // FIN add_action("bbp_locate_template", function ($located, $template_name, $template_names, $template_locations, $load, $require_once) {


add_filter("bbp_get_template_part", function ($templates, $slug, $name) {
	
	if (	("content" === $slug)
		&&	("archive-forum" === $name)
		&&	isset($_GET["liste_taches"])
	) {
		$templates = "bbTaches__liste_taches";
	}
	
	
	return $templates;
	
}, 10, 3); // FIN add_filter("bbp_get_template_part", function ($templates, $slug, $name) {


