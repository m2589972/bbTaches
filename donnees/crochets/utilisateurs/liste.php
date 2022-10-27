<?php

add_filter("bbTaches/utilisateurs", function ($utilisateurs_taches) {
	
	
	if (!isset($GLOBALS["bbTaches"]["utilisateurs_taches"])) {
		
		$roles_taches = [];
		$autorisation = "read_private_forums";
		
		foreach (bbpress()->roles as $code_role => $role) {
			
			if (	isset($role->capabilities[$autorisation])
				&&	$role->capabilities[$autorisation]
			) {
				$roles_taches[] = $code_role;
			}
			
		}
		
		
		$GLOBALS["bbTaches"]["utilisateurs_taches"] = get_users([
			"role__in" => $roles_taches,
			"orderby" => "display_name",
		]);
		
	}
	
	
	return $GLOBALS["bbTaches"]["utilisateurs_taches"];
	
});


