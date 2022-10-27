<?php

add_action("bbp_theme_after_topic_title", function () {
	
	if (	(1 === (int) bbp_get_forum(0)->type_taches)
		&&	(1 === (int) bbp_get_topic(0)->tache_faite)
	) {
		echo "<span class=\"tache_faite\">[tâche faite]</span>";
	}
	
	
}); // FIN add_action("bbp_theme_after_topic_title", function () {


add_filter("bbp_get_single_topic_description", function ($retstr, $r, $args) {
	
	if (	(0 !== bbp_get_forum_id(0))
		&&  (1 === (int) bbp_get_forum(0)->type_taches)
	) {
		
		$id_tache = bbp_get_topic_id($r["topic_id"]);
		
		if (get_current_user_id() === (int) bbp_get_topic($id_tache)->utilisateur_attribue) {
			
			do_action("bbTaches/donnees_template", [
				"id_tache" => $id_tache,
			]);
			
			ob_start();
			do_action("bbTaches/template", "entete_discussion");
			$retstr .= ob_get_clean();
			
		}
		
	}
	
	
	return $retstr;
	
}, 10, 3);


add_action("save_post_" . bbp_get_topic_post_type(), function ($id_discussion, $discussion, $update) {
	
	do_action("bbTaches/maj_discussion", $id_discussion, 0);
	
}, 10, 3);

add_action("save_post_" . bbp_get_reply_post_type(), function ($id_reponse, $reponse, $update) {
	
	$id_tache = $reponse->post_parent;
	
	do_action("bbTaches/maj_discussion", $id_tache, $id_tache);
	
}, 10, 3);


// formulaire de création d'une discussion
add_action("bbp_theme_before_topic_form_title", function () {
	
	if (	(0 === bbp_get_forum_id(0))
		||	(1 !== (int) bbp_get_forum(0)->type_taches)
	) {
		return;
	}
	
	$date_traitement = apply_filters("bbTaches/strtotime", NULL, "+1 day 12:00", time());
	
	do_action("bbTaches/donnees_template", [
		"id_tache" => 0,
		"utilisateur_attribue" => get_current_user_id(),
		"date_traitement" => $date_traitement,
		"utilisateur_attribue_precedent" => 0,
	]);
	
	do_action("bbTaches/template", "details_discussion");
	
}); // FIN add_action("bbp_theme_before_topic_form_title", function () {


// formulaire de réponse dans discussion
add_action("bbp_theme_before_reply_form_content", function () {
	
	if (	(0 === bbp_get_forum_id(0))
		||	(1 !== (int) bbp_get_forum(0)->type_taches)
	) {
		return;
	}
	
	do_action("bbTaches/donnees_template", [
		"id_tache" => (int) $GLOBALS["post"]->ID,
		"utilisateur_attribue" => (int) $GLOBALS["post"]->utilisateur_attribue,
		"date_traitement" => (int) $GLOBALS["post"]->date_traitement,
		"utilisateur_attribue_precedent" => 
			(int) $GLOBALS["post"]->utilisateur_attribue_precedent,
	]);
	
	do_action("bbTaches/template", "details_discussion");
	
}); // FIN add_action("bbp_theme_before_reply_form_content", function () {


// afficher le role seulement aux modérateurs
add_filter("bbp_after_get_reply_author_link_parse_args", function ($r, $args, $defaults) {
	
	if (!current_user_can( 'moderate', bbp_get_reply_id() )) {
		$r["show_role"] = FALSE;
	}
	
	
	return $r;
	
}, 10, 3);


