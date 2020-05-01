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
			myMap.setOSMMap();
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