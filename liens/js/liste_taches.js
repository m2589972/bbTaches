"use strict";

let id_compte_a_rebours;

const duree_inactivite = 600000; // en millisecondes, 600 000 ms = 10 minutes



jQuery($ => {
	
	/* * /
	console.clear();
	/* */
	
	// rafraichissement des pages seulement en cas d'inactivité
	redemarrer_compte_a_rebours();
	document.addEventListener("mousemove", redemarrer_compte_a_rebours);
	
	
	// tri des tâches
	
	$(".liste_taches").sortable({
		"containment" : ".conteneur_liste_taches",
		"axis" : "y",
		"stop" : (event, ui) => {
			
			// calcul d'une nouvelle date lors du déplacement d'une tâche
			
			const element = ui["item"][0]
			const precedent = element["previousElementSibling"];
			const suivant = element["nextElementSibling"];
			
			/*
			let date_precedent = "", date_suivant = "";
			
			if (null !== precedent) {
				date_precedent = date_element(precedent);
			}
			
			if (null !== suivant) {
				date_suivant = date_element(suivant);
			}
			*/
			
			
			if (null !== precedent) {
				
				const jour_precedent = precedent.querySelector(".date_traitement input")["value"];
				
				if ("" !== jour_precedent) {
					
					const choix_jour = element.querySelector(".date_traitement input")
					choix_jour["classList"].add("modifie");
					choix_jour["value"] = jour_precedent;
					
					
					const choix_heures = element
						.querySelector(".date_traitement select[name^=heures]")
					;
					choix_heures["classList"].add("modifie");
					choix_heures["value"] = 
						precedent.querySelector(".date_traitement select[name^=heures]")["value"]
					;
					
					
					const choix_minutes = element
						.querySelector(".date_traitement select[name^=minutes]")
					;
					choix_minutes["classList"].add("modifie");
					choix_minutes["value"] = 
						precedent.querySelector(".date_traitement select[name^=minutes]")["value"]
					;
					
				}
				
			}
			
			
		}, // FIN "stop" : (event, ui) => {
	}); // FIN $(".liste_taches").sortable({
	
	
}); // FIN jQuery($ => {


function redemarrer_compte_a_rebours()
{
	if ("undefined" !== typeof id_compte_a_rebours) {
		clearTimeout(id_compte_a_rebours);
	}
	
	id_compte_a_rebours = setTimeout(e => {
		location.reload(true);
	}, duree_inactivite);
	
}


function date_element(e)
{
	
	const jour = e.querySelector(".date_traitement input")["value"];
	
	if ("" === jour) {
		return null;
	}
	
	
	const heures = e.querySelector(".date_traitement select[name^=heures]")["value"];
	const minutes = e.querySelector(".date_traitement select[name^=minutes]")["value"];
	
	const date = new Date(jour + "T" + heures + ":" + minutes);
	
	
	return date;
	
}


function aff(v)
{
	console.log(v);
}


