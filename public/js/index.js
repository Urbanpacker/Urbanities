'use strict'

import {UrbanMap} from "./moduls/mapCreator.js";
import {UserLocation} from "./moduls/getUserLocation.js" ;
import {AdressGetter} from "./moduls/adressGetter.js" ;
import * as Form from "./moduls/form.js" ;

/*********** FUNCTIONS ****************/

window.addEventListener('DOMContentLoaded', ()=>{

	const MAPCONTAINER = document.getElementById("mapContainer");    
	const zoomDegree = 15 ;
	function setMap(coords, zoomDegree){
		if(!MAPCONTAINER){return;}
		var myMap = new UrbanMap(MAPCONTAINER, coords.lat, coords.long, zoomDegree);
		
		/* Put every tilelayer of a the created UrbanMap into an array in order to use of it later easily */
		var tileLayers = [] ;
		for(let prop in myMap.mapTileLayers){
			tileLayers.push(myMap.mapTileLayers[prop]);
		}
		/* Load the user chosen tilelayer */
		let selectedMap = loadDataFromDomStorage("preferedMapTileLayer", "local") || 0;
		
		/****Scrolling menu to choose a map tilelayer */
		let selectMap = document.createElement("select");
		selectMap.style.maxWidth ="80%";
		selectMap.style.margin="15px 0px"
		let optNumber = 0 ;
		for(let prop in myMap.mapTileLayers){
			let opt = document.createElement("option");
			opt.value = optNumber;
			opt.innerText = ucFirst(prop);
			if(optNumber === selectedMap){
				opt.setAttribute("selected", "");
			}
			selectMap.appendChild(opt);
			++optNumber;
		}
		insertBeforeElem(selectMap, MAPCONTAINER);
	
		/* Event listener watching the change of map tilelayer by the user  */
		selectMap.onchange = (e) => {
			let preferedMapTileLayer = 0;
			for (let i =0, c = tileLayers.length ; i< c ; i++){
				if(i === e.target.selectedIndex){
					preferedMapTileLayer = i;
					break
				}
			}
			removeDataFromDomStorage("preferedMapTileLayer", "local");
			saveDataToDomStorage("preferedMapTileLayer", preferedMapTileLayer, "local");
			location.reload(true);
		};

		// Set an OSM Map with the tilelayer as a parameter, the default tilelayer is used in case of missing or irrelevant answer from the user 
		//myMap.setOSMMap(tileLayers[carte]);
		myMap.setOSMMap(tileLayers[selectedMap]);
		myMap.setMarker();
	}

		/* SingleSpotDisplayer-dedicated functions */
		let adress;
		let postcode;
		let fields = document.querySelectorAll("li[data-type]");
		var coords = {
			lat : null,
			long : null
		};

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
			.then((result) => setMap(result, zoomDegree))
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