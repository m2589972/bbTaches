<?php

/*  * /
if (current_user_can("manage_options")) {
	aff($d);
}
/*  */


$trouve = FALSE;

$valeur = $d["menu_deroulant_nombres"]["valeur"];

?>

<span class="menu_deroulant_nombres">
	<select
		name="<?php echo htmlspecialchars($d["menu_deroulant_nombres"]["nom"]);?>"
	>
		<?php foreach ($d["menu_deroulant_nombres"]["choix"] as $choix) {?>
			
			<?php
				$choix = substr("0$choix", -2);
			?>
			
			<option
				value="<?php echo htmlspecialchars($choix);?>"
				<?php
					if ($choix === $valeur) {
						$trouve = TRUE;
						echo " selected=\"selected\"";
					}
				?>
			>
				<?php echo htmlspecialchars($choix);?>
			</option>
		<?php }?>
		
		<?php if (!$trouve) {?>
		
			<option
				value="<?php echo htmlspecialchars($valeur);?>"
				selected="selected"
			>
				<?php echo htmlspecialchars($valeur);?>
			</option>
			
		<?php }?>
		
	</select>
</span>

