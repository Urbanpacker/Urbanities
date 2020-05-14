'use strict'

import {UrbanMap} from "./moduls/mapCreator.js";
import {UserLocation} from "./moduls/getUserLocation.js" ;
import {AdressGetter} from "./moduls/adressGetter.js" ;
import * as Form from "./moduls/form.js" ;

/*********** FUNCTIONS ****************/

window.addEventListener('DOMContentLoaded', ()=>{

	const MAPCONTAINER = document.getElementById("mapContainer");    
	const zoomDegree = 15 ;

/******************************************************************************** */

	/* SingleSpot Displayer dedicated function */
	(()=>{
		if(!MAPCONTAINER){return};

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

		var MyMap;
		if(coords.lat && coords.long){
			MyMap = new UrbanMap(MAPCONTAINER, coords.lat, coords.long, zoomDegree);
		}else{
			let place = new AdressGetter();
			place.getCoordsFromAdress(adress, postcode)
			.then((coords) => {
				MyMap = new UrbanMap(MAPCONTAINER, coords.lat, coords.long, zoomDegree);
			})
			.catch((error)=>{
				console.error(error);
				console.warn("Impossible de récupérer les coordonnées du spot à partir de son adresse.");
			});
		}

		if(MyMap){
			let saveCoords = document.createElement("button");
			saveCoords.innerText ="Sauver la position";
			saveCoords.style = "margin: 0 auto 15px ; color: black ; maxWidth:50%; display:block"
			insertBeforeElem(saveCoords, MAPCONTAINER);
			saveCoords.onclick = () => {
				MyMap.saveCoordsToDOMStorage();
			};

			let tilelayerMenu = MyMap.selectMapMenu;
			tilelayerMenu.style.maxWidth ="80%";
			tilelayerMenu.style.margin="15px 0px"
			insertBeforeElem(tilelayerMenu, MAPCONTAINER);
		}
	})();

	/******** Récupération des informations de géolocalisation de l'internaute**************/
	let geolocateUser = false;
		if(geolocateUser){	
			var userLocation = new UserLocation() ; 
			userLocation.getCurrentPosition()
			.then((position) => userLocation.successGeo(position))
			.then((coords) => userLocation.setNewPositionIntoStorage(coords))
			.catch((error) => userLocation.failGeo(error));
		}	
	/********************************************************/

});

/********************************************************* */

window.addEventListener('DOMContentLoaded', ()=>{
    /* SingleSpot Form-dedicated functions */
        
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
    
