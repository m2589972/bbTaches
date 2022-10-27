<?php
/*
Plugin Name: bbTaches
Version: 1
*/

if (!function_exists("add_action")) {
	echo "extension";
	exit();
}


add_action("bbp_after_setup_actions", function ($instance_bbpress) {
	
	require "donnees/crochets/configuration.php";
	
	require "donnees/crochets/type_forum.php";
	require "donnees/crochets/affichage.php";
	
	require "donnees/crochets/discussions.php";
	require "donnees/crochets/utilisateurs.php";
	require "donnees/crochets/taches.php";
	require "donnees/crochets/pieces_jointes.php";
	
	require "donnees/crochets/liste_taches.php";
	
	
	
	
	//require "donnees/crochets/emails.php";
	
	
}, 2);


add_action("init", function () {
	
	if ("fr_FR" !== $GLOBALS["locale"]) {
		return;
	}
	
	
	$GLOBALS["wp_locale"]->month["08"] = "aout";
	$GLOBALS["wp_locale"]->month_genitive["08"] = "Aout";
	
	$GLOBALS["wp_locale"]->month_abbrev["août"] = "Aout";
	$GLOBALS["wp_locale"]->month_abbrev["aout"] = "Aout";
	
	
}, 0);


add_filter("bbTaches/base_extension", function ($_) {
	return __DIR__;
});

add_filter("bbTaches/url_extension", function ($_) {
	return plugins_url("", __FILE__);
});


add_filter("bbTaches/version_extension", function ($_) {
	
	if (!isset($GLOBALS["bbTaches"]["version_extension"])) {
		
		$data = get_file_data(__FILE__, ["version" => "Version"]);
		$GLOBALS["bbTaches"]["version_extension"] = $data["version"];
		
	}
	
	
	return $GLOBALS["bbTaches"]["version_extension"];
	
});


