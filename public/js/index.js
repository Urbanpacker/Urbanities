'use strict'

import {UrbanMap} from "./moduls/mapCreator.js";
import {UserLocation} from "./moduls/getUserLocation.js" ;
import {AdressGetter} from "./moduls/adressGetter.js" ;
import * as Form from "./moduls/form.js" ;

/*********** FUNCTIONS ****************/

window.addEventListener('DOMContentLoaded', ()=>{


	/********** Création de ma dynamique via coords ou adresses *************/

		let adress;
		let postcode;
		const MAPCONTAINER = document.getElementById("mapContainer");    
		const zoomDegree = 15 ;

		let fields = document.querySelectorAll("li[data-type]");

		var coords = {
			lat : null,
			long : null
		};

		function setMap(coords){
			var myMap = new UrbanMap(MAPCONTAINER, coords.lat, coords.long, zoomDegree);

			var tileLayers = [] ;
			
			// Put every tilelayer of a the created UrbanMap into an array in order to an easyer use of it
			for(let prop in myMap.mapTileLayers){
				tileLayers.push(myMap.mapTileLayers[prop]);
			}
			
			/*  Tilelayers to use : 
				0. default
				1. richer
				2. neighbourhood
				3. landscape
				4. toner
				5. transport
				6. cycloMap
				7. spinalMap
				8. hikeBike
				9. waterColor
				10. humanitarian
				11. outdoors
			*/
			// Ask the visitor which kind of OSM he wants to be displayed
			let carte = prompt(`Quelle carte voulez-vous ? (tapez le numéro correspondant)\n0. default\n1. richer\n2. neighbourhood\n3. landscape\n4. toner\n5. transport\n6. cycloMap\n7. spinalMap\n8. hikeBike\n9. waterColor\n10. humanitarian\n11. outdoors`);

			// Set an OSM Map with the tilelayer as a parameter, the default tilelayer is used in case of missing or irrelevant answer from the user 
			myMap.setOSMMap(tileLayers[carte]);
			myMap.setMarker();
		}

		for (let value of fields){
			if(value.dataset.type == "latitude"){
				if((value.dataset.content !== "") && (value.dataset.content !== "Inconnue")){
					coords.lat = value.dataset.content ;
				}
			}
			if(value.dataset.type == "longitude"){
				if((value.dataset.content !== "") && (value.dataset.content !== "Inconnue")){
					coords.long = value.dataset.content ;
				}
			}
			if(value.dataset.type == "postcode"){
				postcode = value.dataset.content;
			}
			if(value.dataset.type == "adress"){
				adress = value.dataset.content;
			}
		}

		if(coords.long && coords.lat){
			setMap(coords);
		}else{
			let place = new AdressGetter();
			place.getCoordsFromAdress(adress, postcode)
			.then((result) => setMap(result))
			.catch((error)=>{
				console.error(error);
				console.warn("Impossible de récupérer les coordonnées du spot à partir de son adresse.");
			});
		}
	/***************************************************************************************/
	/******** Récupération des informations de géolocalisation de l'internaute**************/
	let geolocateUser = false;
		if(geolocateUser){	
			var userLocation = new UserLocation() ; 
			userLocation.getCurrentPosition()
			.then((position) => userLocation.successGeo(position))
			.then((coords) => userLocation.setNewPositionIntoStorage(coords))
			.catch((error) => userLocation.failGeo(error));
		}	
	/***************************************************************************************/


});