'use strict'

import {UrbanMap} from "./moduls/urbanMap.js";
import {UserLocation} from "./moduls/userLocation.js" ;
import {AdressGetter} from "./moduls/adressGetter.js" ;
import * as Form from "./moduls/form.js" ;

/*********** FUNCTIONS ****************/

const zoomDegree = 15;

const displayMap = (mapContainer, inputCoords) => {
    if(!mapContainer || !inputCoords){return}
    let spotMap ;
    if(inputCoords.lat && inputCoords.long){
        spotMap = new UrbanMap(mapContainer, inputCoords.lat, inputCoords.long, zoomDegree);
    }else{
        let place = new AdressGetter();

        place.getCoordsFromAdress(inputCoords.adress, inputCoords.postcode)
        .then((coords) => {
            spotMap = new UrbanMap(mapContainer, coords.lat, coords.long, zoomDegree);
        })
        .catch((error)=>{
            //console.error(error);
            console.warn("Impossible de récupérer les coordonnées du spot à partir de son adresse.");
        });
    }

    if(spotMap instanceof UrbanMap){

        removeDataFromDomStorage("savedAdresses", "session");
        if(document.getElementById("displayMapFromAdress")){
            spotMap.saveAdressToDOMStorage("savedAdresses", inputCoords.adress, inputCoords.postCode);
        }

        let saveCoords = document.createElement("button");
        saveCoords.innerText ="Sauver la position";
        saveCoords.classList.add("optionButton");
        saveCoords.style = "margin: 0 auto 15px ; maxWidth:50%; display:block";
        saveCoords.onclick = () => {
            spotMap.saveCoordsToDOMStorage("savedCoords");
        };

        let tilelayerMenu = spotMap.selectMapMenu;
        tilelayerMenu.style.maxWidth ="80%";
        tilelayerMenu.style.margin="15px 0px"
        tilelayerMenu.style.textAlign="center";
        insertBeforeElem(tilelayerMenu, mapContainer);

    }
    return spotMap ;
}

/* SingleSpot Displayer dedicated function */
if(document.getElementById("detailItem")){
	let fields = document.querySelectorAll("[data-type]");
	const coords = {
        postcode : null,
        adress : null,
		lat : null,
		long : null
	};
	for (let field of fields){
		if(field.dataset.type == "latitude"){
			if((field.dataset.content !== "") && (field.dataset.content !== "Inconnue")){
				coords.lat = field.dataset.content ;
			}
		}
		if(field.dataset.type == "longitude"){
			if((field.dataset.content !== "") && (field.dataset.content !== "Inconnue")){
				coords.long = field.dataset.content ;
			}
		}
		if(field.dataset.type == "postcode"){
			coords.postcode = field.dataset.content;
		}
		if(field.dataset.type == "adress"){
			coords.adress = field.dataset.content;
		}
       }
       const mapContainer = document.getElementById("displaySpotMapContainer");
       if(null !== mapContainer){
           displayMap(mapContainer, coords);
       }
}

/* Member Profil view dedicated function */
/******** Getting the users's current position data**************/
	
let currentPositionBlock = document.getElementById("currentPosition");
	
function displayCurrentPosition(storedCoords){
       const mapContainer = document.getElementById("currentPositionmapContainer")
	let mapTrigger = document.getElementById("mapTrigger");
	if(currentPositionBlock && mapTrigger && storedCoords){
		currentPositionBlock.classList.remove("hidden");
		mapTrigger.onclick = () => {
			new UrbanMap(mapContainer, storedCoords.latitude, storedCoords.longitude, zoomDegree);
			mapContainer.classList.remove("hidden");
			mapTrigger.classList.add("hidden)");
		};
	}	
}

if(currentPositionBlock){
	const storedCoords = loadDataFromDomStorage('memberPosition', 'session');
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
    
    /********** Display map with marker from adress input in a form *****************/
window.addEventListener('DOMContentLoaded', ()=>{
    const displayMapFromAdress = (container) => {
        const mapContainer = document.getElementById(container);
        if(null === mapContainer){return}
        
        const newAdressButton = document.getElementById("newAdressButton") ;
        newAdressButton.textContent = "Afficher sur la carte";
        newAdressButton.style.display= "none";
        newAdressButton.style.margin = "1rem auto 0";

        var adressField = document.getElementById("adressMap");
        var postcodeField = document.getElementById("postcodeMap");
    
        const setMap = () => {
            
            if(!mapContainer){return}
                
                let savedAdresses = loadDataFromDomStorage("savedAdresses", "session");

                if(savedAdresses){
                    adressField.value = savedAdresses[0].adress ;
                    postcodeField.value = savedAdresses[0].postcode ;
                }

                //Store the adress into a variable to generate a new map with a new tilelayer
                let adressToUse = adressField.value ? adressField.value : null ;
                let postcodeToUse = postcodeField.value ? postcodeField.value : null;
                
                // Remove the "+" used in the AdressGetter.getCoordsFromAdress API to search geocoordinates
                let char = /\+/gi;
                adressField.value = adressField.value.replace(char, ' ');
                postcodeField.value = postcodeField.value.replace(char, ' ');
                
                if(!adressToUse || !postcodeToUse){
                    return;
                }
                
                if(postcodeField.value.length !== 5){
                    return ;
                }
    
                let place = new AdressGetter();
                place.getCoordsFromAdress(adressToUse, postcodeToUse)
                .catch((error)=>{
                    //console.error(error);
                    console.warn("Impossible de récupérer les coordonnées de l'emplacement à partir de son adresse.");
                })
                .then((coords) => {
                    adressField.setAttribute("disabled", "");
                    postcodeField.setAttribute("disabled", "");
                    adressField.style.cursor ="not-allowed";
                    postcodeField.style.cursor ="not-allowed";

                    newAdressButton.style.display = "block";
                    newAdressButton.textContent = "Nouvelle recherche";
                    newAdressButton.style.margin = "15px auto";
                    newAdressButton.dataset.action = "newSearch";
                    mapContainer.classList.remove("hidden");
                    displayMap(mapContainer, coords);
                })
                .catch((error)=>{
                    console.error(error);
                    console.warn("Impossible d'afficher la carte avec les coordonnées fournies.");
                });
            
        }
    
        const checkFormData = (data) => {
            adressField.value = data.adressField.value;
            postcodeField.value = data.postcodeField.value.trim();
            postcodeField.value = postcodeField.value.length > 5 ? postcodeField.value.substring(0,5) : postcodeField.value; 
            if(adressField.value.length > 0 && postcodeField.value.length === 5){	       
                // Limit the length of the postcode field intput to 5 characters
                let postCodePattern = /[0-9]{5}/;
                if(postCodePattern.test(postcodeField.value)){
                    return true
                }
            }
            return false
        }

        setMap();

        adressField.addEventListener("input", ()=>{
            if(checkFormData({adressField, postcodeField})){
                newAdressButton.style.display = "block";
            } else{
                newAdressButton.style.display = "none";
            }
        });
    
        postcodeField.addEventListener("input", ()=>{
            if(checkFormData({adressField, postcodeField})){
                newAdressButton.style.display = "block";
            } else{
                newAdressButton.style.display = "none";
            }
        });
        
        newAdressButton.addEventListener("click", (e)=>{
            e.preventDefault();
            
            if(e.currentTarget.dataset.action == "newSearch"){
                removeDataFromDomStorage("savedCoords", "session");
                removeDataFromDomStorage("savedAdresses", "session");
                location.reload();
            } else {
                if(checkFormData({adressField, postcodeField})){
                    newAdressButton.style.display = "block";
                    setMap();
                }
            }
        });
    };

    displayMapFromAdress("displayMapFromAdressContainer");
	/********************************************************/
});    
    /* SingleSpotForm dedicated functions */   

window.addEventListener('DOMContentLoaded', ()=>{
    if(document.getElementById("sportForm")){

        let adressToUse = document.getElementById("adress");
        let postcodeToUse = document.getElementById("postcode");
        let longitude = document.getElementById("longitude");
        let latitude = document.getElementById("latitude");
            
        function getCoords(){
            if(adressToUse.value.trim().length < 3 || postcodeToUse.value.trim().length !== 5){
                return;
            }
            let place = new AdressGetter();
            place.getCoordsFromAdress(adressToUse.value, postcodeToUse.value)
            .then((result) => {
                longitude.value = result.long;
                latitude.value = result.lat;
            })
            .catch((error)=>{
                //console.error(error);
                console.warn("Impossible de récupérer les coordonnées du spot à partir de son adresse.");
            });	
        }
        
            (()=>{
                getCoords();				
            })();
            adressToUse.addEventListener("input", ()=>{
                getCoords();	
            });
            postcodeToUse.addEventListener("input", ()=>{
                // Limit the length of the postcode field intput to 5 characters
                postcodeToUse.value = postcodeToUse.value.trim().length > 5 ? postcodeToUse.value.substring(0,5) : postcodeToUse.value.trim(); 
                let postCodePattern = /[0-9]{5}/;
                    if(postCodePattern.test(postcodeToUse.value)){
                        getCoords();
                    }
            });
    }
});
    
