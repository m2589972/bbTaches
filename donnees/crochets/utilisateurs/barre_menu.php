<?php

add_action("admin_bar_menu", function ($wp_admin_bar) {
	
	if (!current_user_can("read_private_forums")) {
		// l'utilisateur courant n'a pas accès au forum
		return;
	}
	
	
	$current_user_id = get_current_user_id();
	
	
	// lien vers le profil du forum

	$wp_admin_bar->add_node([
		"id" => "bbTaches__mon_profil_forum",
		"parent" => "top-secondary",
		"title" => "Mon profil forum",
		"href" => bbp_get_user_profile_url($current_user_id),
	]);
	
	
	// liste des tâches des utilisateurs
	
	$url_forums = get_post_type_archive_link(bbp_get_forum_post_type());
	
	$wp_admin_bar->add_node([
		"id" => "bbTaches__liste_taches",
		"parent" => "top-secondary",
		"title" => "Mes tâches",
		"href" => "$url_forums?liste_taches=$current_user_id",
	]);
	
	
	$utilisateurs = apply_filters("bbTaches/utilisateurs", NULL);
	
	foreach ($utilisateurs as $utilisateur) {
		
		if ($utilisateur->ID === $current_user_id) {
			continue;
		}
		
		$wp_admin_bar->add_node([
			"id" => "bbTaches__taches_$utilisateur->ID",
			"parent" => "bbTaches__liste_taches",
			"title" => "Tâches de $utilisateur->display_name",
			"href" => "$url_forums?liste_taches=$utilisateur->ID",
		]);
		
	}
	
	
}); // FIN add_action("admin_bar_menu", function ($wp_admin_bar) {


