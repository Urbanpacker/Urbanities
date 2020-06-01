'use strict'

import {UrbanMap} from "./modulsurbanMap.js";
import {UserLocation} from "./moduls/userLocation.js" ;
import {AdressGetter} from "./moduls/adressGetter.js" ;
import * as Form from "./moduls/form.js" ;

/*********** FUNCTIONS ****************/

window.addEventListener('DOMContentLoaded', ()=>{

	const MAPCONTAINER = document.getElementById("mapContainer");    
	const zoomDegree = 15 ;
/******************************************************************************** */

	/* SingleSpot Displayer dedicated function */
	(()=>{
		const detailItem = document.getElementById("detailItem");    
		if(!MAPCONTAINER || !detailItem){return}
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
		
		var spotMap;
		if(coords.lat && coords.long){
			spotMap = new UrbanMap(MAPCONTAINER, coords.lat, coords.long, zoomDegree);
		}else{
			let place = new AdressGetter();
			place.getCoordsFromAdress(adress, postcode)
			.then((coords) => {
				spotMap = new UrbanMap(MAPCONTAINER, coords.lat, coords.long, zoomDegree);
			})
			.catch((error)=>{
				console.error(error);
				console.warn("Impossible de récupérer les coordonnées du spot à partir de son adresse.");
			});
		}

		if(spotMap){
			let saveCoords = document.createElement("button");
			saveCoords.innerText ="Sauver la position";
			saveCoords.style = "margin: 0 auto 15px ; color: black ; maxWidth:50%; display:block"
			saveCoords.onclick = () => {
				spotMap.saveCoordsToDOMStorage();
			};
			insertBeforeElem(saveCoords, MAPCONTAINER);

			let tilelayerMenu = spotMap.selectMapMenu;
			tilelayerMenu.style.maxWidth ="80%";
			tilelayerMenu.style.margin="15px 0px"
			insertBeforeElem(tilelayerMenu, MAPCONTAINER);
		}
	})();

	/* Member Profil view dedicated function */
	/******** Getting the users's current position data**************/
	
	let currentPositionBlock = document.getElementById("currentPosition");
	
	function displayCurrentPosition(storedCoords){
		let mapTrigger = document.getElementById("mapTrigger");
		if(currentPositionBlock && mapTrigger && storedCoords){
			currentPositionBlock.classList.remove("hidden");
			mapTrigger.onclick = () => {
				new UrbanMap(MAPCONTAINER, storedCoords.latitude, storedCoords.longitude, zoomDegree);
				MAPCONTAINER.classList.remove("hidden");
				mapTrigger.classList.add("hidden)");
			};
		}	
	}

	if(currentPositionBlock){
		var storedCoords = loadDataFromDomStorage('memberPosition', 'session');
		if(!storedCoords){
			var userLocation = new UserLocation();
			userLocation.getCurrentPosition()
			.then((position) => userLocation.successGeo(position))
			.then((coords) => {
				userLocation.setNewPositionIntoStorage(coords);
				displayCurrentPosition(coords);
			})
			.catch((error) => {
				userLocation.failGeo(error);
			});
		} else{
			displayCurrentPosition(storedCoords);
		}
	}
	/********************************************************/

	/* SingleSpot Form dedicated functions */        
    let adressToUse = document.getElementById("adress");
    let postcodeToUse = document.getElementById("postcode");
    let longitude = document.getElementById("longitude");
    let latitude = document.getElementById("latitude");
          
    function getCoords(){
        if(!adressToUse.value || !postcodeToUse.value){
            return;
        }
        let place = new AdressGetter();
        place.getCoordsFromAdress(adressToUse.value, postcodeToUse.value)
        .then((result) => {
            longitude.value = result.long;
            latitude.value = result.lat;
        })
	    .catch((error)=>{
	        console.error(error);
	        console.warn("Impossible de récupérer les coordonnées du spot à partir de son adresse.");
	    });	
    }
    
    if(document.querySelector("form")){
        (()=>{
            getCoords();				
        })();
        adressToUse.addEventListener("blur", ()=>{
            getCoords();	
        });
        postcodeToUse.addEventListener("blur", ()=>{
            getCoords();
        });
    }
});
    
