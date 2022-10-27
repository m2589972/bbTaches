<?php

add_action("template_redirect", function () {
	
	$url_extension = apply_filters("bbTaches/url_extension", NULL);
	$version_extension = apply_filters("bbTaches/version_extension", NULL);
	
	
	wp_enqueue_style(
		  "bbTaches__forums"
		, "$url_extension/liens/css/forums.css"
		, ["bbp-default"]
		, $version_extension
	);
	
	wp_enqueue_style(
		  "bbTaches__css_personnalise"
		, admin_url("admin-post.php?action=bbTaches__css_personnalise")
	);
	
	
	wp_enqueue_script(
		  "bbTaches__forums"
		, "$url_extension/liens/js/forums.js"
		, []
		, $version_extension
	);
	
	wp_localize_script(
		  "bbTaches__forums"
		, "donnees"
		,
		[
			"url_base" => home_url(""),
			"url_extension" => $url_extension,
			"nonce_rest" => wp_create_nonce("wp_rest"),
		]
	);
	
	
});


add_action("wp_loaded", function () {
	
	$GLOBALS["bbTaches"]["donnees_template"] = [];
	
});


add_action("bbTaches/donnees_template", function ($donnees_template) {
	
	foreach ($donnees_template as $cle => $valeur) {
		$GLOBALS["bbTaches"]["donnees_template"][$cle] = $valeur;
	}
	
});


add_action("bbTaches/template", function ($code_template) {
	
	// recherche dans les templates du thème enfant ou du thème parent
	$template = locate_template("bbTaches/templates/$code_template.php");
	
	// s'il n'existe pas dans le thème
	if ("" === $template) {
		
		$base_extension = apply_filters("bbTaches/base_extension", NULL);
		
		// recherche dans les templates de l'extension
		$template = "$base_extension/donnees/templates/$code_template.php";
		
	}
	
	// données du template
	$d = $GLOBALS["bbTaches"]["donnees_template"];
	
	
	require $template;
	
}, 10, 2);


add_filter("bbTaches/mktime", function ($_, $jour, $h, $m, $s) {
	
	$tab = explode("-", $jour);
	
	$ancienFuseau = date_default_timezone_get();
	date_default_timezone_set(get_option("timezone_string"));
	$timestamp = mktime((int) $h, (int) $m, (int) $s, (int) $tab[1], (int) $tab[2], (int) $tab[0]);
	date_default_timezone_set($ancienFuseau);
	
	
	return $timestamp;
	
}, 10, 5);


add_filter("bbTaches/strtotime", function ($_, $format, $time) {
	
	$ancienFuseau = date_default_timezone_get();
	date_default_timezone_set(get_option("timezone_string"));
	$strtotime = strtotime($format, $time);
	date_default_timezone_set($ancienFuseau);
	
	
	return $strtotime;
	
}, 10, 3);



