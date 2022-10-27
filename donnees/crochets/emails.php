<?php

add_action("bbTaches/envoi_email", function ($arguments) {
	
	
	// données
	$GLOBALS["bbTaches"]["donnees_template"]["arguments_email"] = $arguments;
	
	
	// messages
	
	ob_start();
	do_action("bbTaches/template", "emails/{$arguments["template"]}.html");
	$message_html = ob_get_clean();
	
	ob_start();
	do_action("bbTaches/template", "emails/{$arguments["template"]}.texte");
	$message_texte = ob_get_clean();
	
	
	// envoi
	
	$nom_site = $GLOBALS["bbTaches"]["donnees_template"]["nom_site"];
	
	$GLOBALS["bbTaches"]["texte_email_html"] = $message_texte;
	
	
	wp_mail(
		  $arguments["destinataire"]
		, "[$nom_site] {$arguments["titre"]}"
		, $message_html
		, "From: {$arguments["envoyeur"]} <{$arguments["envoyeur"]}>"
	);
	
	
	unset($GLOBALS["bbTaches"]["texte_email_html"]);
	
	
}, 10, 1);


add_action("phpmailer_init", function ($phpmailer) {
	
	if (!isset($GLOBALS["bbTaches"]["texte_email_html"])) {
		return;
	}
	
	
	// importation en pièce-jointes des images du messages
	
	$message = $phpmailer->Body;
	
	preg_match_all("/(src|background)=[\"'](.*)[\"']/Ui", $message, $images);
	
	if (isset($images[2])) {
		
		foreach ($images[2] as $i => $url) {
			
			$cid = md5($url) . "@phpmailer.0"; //RFC2392 S 2
			
			$filename = basename($url);
			
			if ($phpmailer->addStringEmbeddedImage(
					  file_get_contents($url)
					, $cid
					, $filename
					, "base64"
					, \PHPMailer::_mime_types(
						\PHPMailer::mb_pathinfo($filename, PATHINFO_EXTENSION)
					)
				)
			) {
				$message = preg_replace(
					  "/" . $images[1][$i] . "=[\"']" . preg_quote($url, "/") . "[\"']/Ui"
					, $images[1][$i] . "=\"cid:$cid\""
					, $message
				);
			}
			
		}
		
	}
	
	$phpmailer->isHTML(TRUE);
	$phpmailer->Body = $phpmailer->normalizeBreaks($message);
	
	$phpmailer->AltBody = $phpmailer->normalizeBreaks($GLOBALS["bbTaches"]["texte_email_html"]);
	
	
}, 10, 1); // FIN add_action("phpmailer_init", function ($phpmailer) {


