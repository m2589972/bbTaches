"use strict";

document.addEventListener("DOMContentLoaded", e => {
	
	// pièces-jointes
	
	/* * /
	console.clear();
	/* */
	
	[...document.getElementsByClassName("conteneur_pieces_jointes")].forEach(conteneur => {
		
		conteneur["innerHTML"] = 
			"<span class=\"message_en_cours\">" +
				"chargement en cours… <span class=\"en_cours\"></span>" +
			"</span>"
		;
		
		serveur_pieces_jointes(conteneur, {});
		
	}); // FIN [...document.getElementsByClassName("conteneur_pieces_jointes")]
		//		.forEach(conteneur => {
	
	
	// bouton "utilisateur attribué précédent"
	
	[...document.getElementsByClassName("espace_attribue_precedent")].forEach(e => {
		
		const nom_attribue_precedent = e["dataset"]["nomUtilisateur"];
		
		const menu_deroulant = e["parentElement"]
			.querySelector(":scope select")
		;
		
		
		const bouton = document.createElement("button");
		bouton["textContent"] = "Utilisateur attribué précédent : " + nom_attribue_precedent;
		
		
		bouton.addEventListener("click", e => {
			
			e.preventDefault();
			
			menu_deroulant["value"] = e["target"]["parentElement"]["dataset"]["idUtilisateur"];
			
			e["target"].remove();
			
		});
		
		
		e.appendChild(bouton);
		
	}); // FIN [...document.getElementsByClassName("espace_attribue_precedent")].forEach(e => {
	
	
}); // FIN document.addEventListener("DOMContentLoaded", e => {


function serveur_pieces_jointes(conteneur, args)
{
	// requete
	
	var form_data = new FormData();
	
	form_data.append("id_discussion", pieces_jointes["id_discussion"]);
	form_data.append("nonce_pieces_jointes", pieces_jointes["nonce"]);
	
	
	if ("undefined" !== typeof args["ajout"]) {
		
		[...args["ajout"]].forEach((file, index) => {
			form_data.append("f_" + index, file);
		});
		
		form_data.append("ajout", "");
		
	} else if ("undefined" !== typeof args["suppression"]) {
		
		form_data.append("suppression", args["suppression"]);
		
	}
	
	
	const url = donnees["url_base"] + "/wp-json/bbTaches/pieces_jointes";
	
	fetch(url, {
		"method" : "POST",
		"headers" : new Headers({
			"X-WP-Nonce" : donnees["nonce_rest"],
		}),
		"body" : form_data,
	})
		.then(reponse => {
			return reponse.json();
		})
		.then(fichiers => {
			
			// affichage
			
			const champ_ajout_fichier = document.createElement("input");
			champ_ajout_fichier.setAttribute("type", "file");
			champ_ajout_fichier.setAttribute("multiple", "multiple");
			
			champ_ajout_fichier.addEventListener("change", e => {
				
				const message = document.createElement("span");
				message["innerHTML"] = "ajout en cours… <span class=\"en_cours\"></span>";
				e["currentTarget"]["parentNode"].appendChild(message);
				
				serveur_pieces_jointes(conteneur, {"ajout" : e["currentTarget"]["files"]});
				
			});
			
			
			const label = document.createElement("label")
			label.appendChild(document.createTextNode("Ajouter une pièce-jointe : "));
			label.appendChild(champ_ajout_fichier);
			
			
			conteneur["innerHTML"] = "";
			
			if (0 === fichiers["length"]) {
				
				conteneur["innerHTML"] = "<div>Aucune pièce-jointe actuellement.</div>";
				
				conteneur.appendChild(
					document.createElement("div")
						.appendChild(label)
				);
				
			} else {
				
				conteneur["innerHTML"] = "<h4>Pièces-jointes</h4>";
				
				conteneur.appendChild(
					document.createElement("div")
						.appendChild(label)
				);
				
				fichiers.forEach(fichier => {
					
					const suppression_fichier = document.createElement("img");
					suppression_fichier["classList"].add("image_suppression");
					suppression_fichier.setAttribute(
						  "src"
						, donnees["url_extension"] + "/liens/images/suppression.png"
					);
					
					suppression_fichier.addEventListener("click", e => {
						
						const parent = e["currentTarget"]["parentNode"];
						
						e["currentTarget"].remove();
						
						const message = document.createElement("span");
						message["classList"].add("message_en_cours");
						message["innerHTML"] = "suppression en cours…" +
							" <span class=\"en_cours\"></span>"
						;
						parent.appendChild(message);
						
						serveur_pieces_jointes(conteneur, {"suppression" : fichier});
						
					});
					
					
					const lien_fichier = document.createElement("a");
					lien_fichier["textContent"] = fichier;
					lien_fichier.setAttribute(
						  "href"
						, pieces_jointes["url_telecharger"] 
							+ "&fichier=" + encodeURIComponent(fichier)
					);
					
					
					const ligne_fichier = document.createElement("div");
					ligne_fichier.appendChild(lien_fichier);
					ligne_fichier.appendChild(suppression_fichier)
					
					conteneur.appendChild(ligne_fichier);
					
				});
				
			}
			
		})
	; // FIN fetch(url)
	
	
} // FIN function serveur_pieces_jointes(arguments)


function aff(v)
{
	console.log(v);
}


