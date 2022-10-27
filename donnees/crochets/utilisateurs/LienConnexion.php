<?php

add_action("personal_options", function ($user) {
	
	$cle = $user->lien_connexion__cle;
	
	
	?>
		<tr>
			<th>
			</th>
			<td>
				
				<?php
				
				if ("" !== $cle) {
					
					$lien_connexion = admin_url(
						"admin-post.php?liste_taches&action=" 
							. \LienConnexion\LIEN_CONNEXION . "&cle=$cle"
					);
					
					?>
						
						<div>
							<strong>Lien de connexion qui affiche les tâches&nbsp;: </strong>
						</div>
						
						<div>
							<a href="<?php echo htmlspecialchars($lien_connexion);?>">
								<?php echo htmlspecialchars($lien_connexion);?></a>
						</div>
						
					<?php
					
				}
				
				?>
			</td>
		</tr>
	<?php
	
}, 50); // FIN add_action("personal_options", function ($user) {


add_filter("LienConnexion/url_apres_connexion", function ($url, $utilisateur) {
	
	if (isset($_GET["liste_taches"])) {
		
		$url_forums = get_post_type_archive_link(bbp_get_forum_post_type());
		
		$url = "$url_forums?liste_taches=$utilisateur->ID";
		
	}
	
	
	return $url;
	
}, 10, 2);


