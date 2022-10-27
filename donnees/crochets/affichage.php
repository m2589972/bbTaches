<?php

add_filter("private_title_format", function ($format, $objet) {
	
	if (bbp_get_forum_post_type() === $objet->post_type) {
		$format = "%s";
	}
	
	
	return $format;
	
}, 10, 2);


