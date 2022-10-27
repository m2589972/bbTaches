<?php

add_action("user_register", function ($user_id, $userdata) {
	
	$css  = "";
	$css .= "  .bbp-replies .user-id-$user_id\n";
	$css .= ", .bbp-replies .user-id-$user_id a\n";
	$css .= "{\n";
	$css .= "\tcolor : #090;\n";
	$css .= "}\n";
	
	
	update_user_meta(
		  $user_id
		, "css_personnalise"
		, $css
	);
	
}, 10, 2);


add_action("personal_options", function ($user) {
	
	?>
		<tr>
			<th>
				Code CSS personnalisé
			</th>
			<td>
				<textarea
					id="css_personnalise"
					name="css_personnalise"
					style="height : 10em;"
				><?php echo $user->css_personnalise;?></textarea>
			</td>
		</tr>
	<?php
	
}, 500);


add_action("profile_update", function ($user_id, $old_user_data) {
	
	update_user_meta(
		  $user_id
		, "css_personnalise"
		, $_POST["css_personnalise"]
	);
	
}, 10, 2);


add_action("admin_post_bbTaches__css_personnalise", function () {
	
	// appel GET
	
	$current_user = wp_get_current_user();
	
	
	header("Content-type: text/css");
	
	echo $current_user->css_personnalise;
	
	exit();
	
});


