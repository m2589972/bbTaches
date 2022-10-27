 <?php

// les pièces-jointes fonctionnenent avec toutes les discussions
// même si le forum n'est pas de type tâches

add_filter("bbTaches/pieces_jointes/repertoire", function ($_) {
	
	$base_extension = apply_filters("bbTaches/base_extension", NULL);
	
	return "$base_extension/../../bbTaches/pieces_jointes";
	
});


add_filter("bbTaches/pieces_jointes/fichier", function ($_, $nom_fichier, $repertoire_discussion) {
	
	$fichier_trouve = FALSE;
	
	
	$d = dir($repertoire_discussion);
	
	while (FALSE !== ($e = $d->read())
		&&	!$fichier_trouve
	) {
		if (	is_file("$repertoire_discussion/$e")
			&&	($e === $nom_fichier)
		) {
			$fichier_trouve = TRUE;
			break;
		}
	}
	
	$d->close();
	
	
	if (!$fichier_trouve) {
		// fichier non existant ou non autorisé
		exit();
	}
	
	
	return "$repertoire_discussion/$nom_fichier";
	
}, 10, 3);


add_action("rest_api_init", function () {
	
	register_rest_route("bbTaches", "pieces_jointes",
		[
			"methods" => "POST",
			"callback" => function (\WP_REST_Request $request) {
				
				// sécurité
				
				if (	!isset($_POST["id_discussion"])
					||	!isset($_POST["nonce_pieces_jointes"])
				) {
					return;
				}
				
				$id_discussion = $_POST["id_discussion"];
				$verif = "pieces_jointes_$id_discussion";
				
				if (	(wp_create_nonce($verif) !== $_POST["nonce_pieces_jointes"])
					||	!current_user_can("read_private_forums")
				) {
					return;
				}
				
				
				// traitements
				
				$repertoire = apply_filters("bbTaches/pieces_jointes/repertoire", NULL);
				$repertoire_discussion = "$repertoire/$id_discussion";
				
				
				if (isset($_POST["ajout"])) {
					
					if (!is_dir($repertoire_discussion)) {
						
						// création des répertoires
						mkdir($repertoire_discussion, 0777, TRUE);
						
						// protection contre l'accès par Apache
						file_put_contents("$repertoire/.htaccess", "Deny from All", LOCK_EX);
						
					}
					
					
					// enregistrement des fichiers
					
					foreach ($_FILES as $tab_fichier) {
						
						// refuser les fichiers qui commencent par un point
						if (0 === strpos($tab_fichier["name"], ".")) {
							continue;
						}
						
						move_uploaded_file(
							  $tab_fichier["tmp_name"]
							, "$repertoire_discussion/{$tab_fichier["name"]}"
						);
					}
					
				} // FIN if (isset($_POST["ajout"])) {
				
				if (isset($_POST["suppression"])) {
					
					$nom_fichier = wp_unslash($_POST["suppression"]);
					$fichier = apply_filters(
						  "bbTaches/pieces_jointes/fichier"
						, NULL, $nom_fichier, $repertoire_discussion
					);
					
					// suppression
					unlink($fichier);
					
				}
				
				
				// réponse : fichiers actuels
				
				$fichiers = [];
	
				if (is_dir($repertoire_discussion)) {
					
					$d = dir($repertoire_discussion);
					
					while (FALSE !== ($e = $d->read())) {
						if (is_file("$repertoire_discussion/$e")) {
							$fichiers[] = $e;
						}
					}
					
					$d->close();
					
				}
				
				
				return rest_ensure_response($fichiers);
				
			},
		]
	); // FIN register_rest_route("bbTaches", "pieces_jointes",
	
}); // add_action("rest_api_init", function () {


add_action("admin_post_bbTaches__pieces_jointes__telecharger", function () {
	
	// appel GET
	
	
	if (	!isset($_GET["id_discussion"])
		||	!isset($_GET["fichier"])
	) {
		return;
	}
	
	
	$id_discussion = $_GET["id_discussion"];
	
	
	// tester autorisation d'accès
	
	if (!current_user_can("read_private_forums")) {
		exit();
	}
	
	
	// informations du fichier
	
	$repertoire = apply_filters("bbTaches/pieces_jointes/repertoire", NULL);
	$repertoire_discussion = "$repertoire/$id_discussion";
	
	$nom_fichier = wp_unslash($_GET["fichier"]);
	$fichier = apply_filters(
		  "bbTaches/pieces_jointes/fichier"
		, NULL, $nom_fichier, $repertoire_discussion
	);
	
	
	// téléchargement
	
	$mime = mime_content_type($fichier);
	
	$contenu_fichier = file_get_contents($fichier);
	$taille_fichier = strlen($contenu_fichier);
	
	$nom_fichier_entete = str_replace('"', '\\"', $nom_fichier);
	
	
	header("Content-Type: $mime");
	header("Content-Length: $taille_fichier");
	header("Content-Disposition: attachment; filename=\"$nom_fichier_entete\"");
	
	echo $contenu_fichier;
	
	exit();
	
});


add_action("bbp_theme_before_reply_form", function () {
	
	$id_discussion = bbp_get_topic_id(0);
	$verif = "pieces_jointes_$id_discussion";
	
	$url_telecharger = admin_url(
		  "admin-post.php"
		. "?action=bbTaches__pieces_jointes__telecharger"
		. "&id_discussion=$id_discussion"
	);
	
	wp_localize_script(
		  "bbTaches__forums"
		, "pieces_jointes"
		,
		[
			"id_discussion" => $id_discussion,
			"url_telecharger" => $url_telecharger,
			"nonce" => wp_create_nonce($verif),
		]
	);
	
	
	?>
		<div class="conteneur_pieces_jointes">
			souci JavaScript
		</div>
	<?php
	
});


