<?php

/*  * /
if (current_user_can("manage_options")) {
	aff($d);
}
/*  */


$affichage_court = isset($d["affichage_court"]) && $d["affichage_court"];


$heures = range(7, 22);
$minutes = range(0, 55, 5);


$jour_traitement = "";
$jour_semaine_affichage = "";
$heures_traitement = "12";
$minutes_traitement = "00";

if (0 !== $d["date_traitement"]) {
	
	$jour_traitement = wp_date("Y-m-d", $d["date_traitement"]);
	$jour_traitement_affichage = wp_date("d/m/Y", $d["date_traitement"]);
	
	if ($affichage_court) {
		$jour_semaine_affichage = wp_date("D", $d["date_traitement"]);
	} else {
		$jour_semaine_affichage = wp_date("l", $d["date_traitement"]);
	}
	
	$heures_traitement = wp_date("H", $d["date_traitement"]);
	$heures_traitement = substr("0$heures_traitement", -2);
	
	$minutes_traitement = wp_date("i", $d["date_traitement"]);
	$minutes_traitement = substr("0$minutes_traitement", -2);
	
}


?>
<div class="date_traitement">
	
	<?php if (
		(
				(get_current_user_id() !== $d["utilisateur_attribue"])
			&&	(!current_user_can( 'moderate', $d["id_tache"]))
		)
		||
		(
				(0 !== $d["id_tache"])
			&&	(1 === (int) bbp_get_topic($d["id_tache"])->tache_faite)
		)
	) {?>
		
		<?php if (0 === $d["date_traitement"]) {?>
			
			<?php if ($affichage_court) {?>
				date non définie
			<?php } else {?>
				Date de traitement non définie
			<?php }?>
			
			
		<?php } else {?>
			
			<?php if (!$affichage_court) {?>
				Date de traitement&nbsp;:
			<?php }?>
			
			<?php echo htmlspecialchars($jour_semaine_affichage);?>
			<?php echo htmlspecialchars($jour_traitement_affichage);?>
			à
			<?php
				echo htmlspecialchars($heures_traitement);
			?>&nbsp;h&nbsp;<?php
				echo htmlspecialchars($minutes_traitement);
			?>
			
		<?php }?>
		
	<?php } else {?>
		<label>
			<?php if (!$affichage_court) {?>
				Date de traitement&nbsp;:
			<?php }?>
			
			<?php echo htmlspecialchars($jour_semaine_affichage);?>
			
			<input
				type="date"
				name="jour_traitement[<?php echo htmlspecialchars($d["id_tache"]);?>]"
				value="<?php echo htmlspecialchars($jour_traitement);?>"
				class="jour_traitement"
			>
			à
			<?php
				
				do_action("bbTaches/donnees_template", [
					"menu_deroulant_nombres" => [
						"nom" => "heures_traitement[$d[id_tache]]",
						"choix" => $heures,
						"valeur" => $heures_traitement,
					],
				]);
				
				do_action("bbTaches/template", "menu_deroulant_nombres");
				
			?>
			&nbsp;h&nbsp;
			<?php
				
				do_action("bbTaches/donnees_template", [
					"menu_deroulant_nombres" => [
						"nom" => "minutes_traitement[$d[id_tache]]",
						"choix" => $minutes,
						"valeur" => $minutes_traitement,
					],
				]);
				
				do_action("bbTaches/template", "menu_deroulant_nombres");
				
			?>
			
		</label>
		
	<?php }?>
	
</div>


