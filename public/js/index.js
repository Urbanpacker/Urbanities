'use strict'

import {UrbanMap} from "./moduls/urbanMap.js";
import {UserLocation} from "./moduls/userLocation.js" ;
import {AdressGetter} from "./moduls/adressGetter.js" ;
import * as Form from "./moduls/form.js" ;

/*********** FUNCTIONS ****************/

window.addEventListener('DOMContentLoaded', ()=>{

    const zoomDegree = 15;
    
    const displayMap = (mapContainer, inputCoords) => {
		if(!mapContainer || !inputCoords){return}
        let spotMap ;
		if(inputCoords.lat && inputCoords.long){
			spotMap = new UrbanMap(mapContainer, inputCoords.lat, inputCoords.long, zoomDegree);
		}else{
			let place = new AdressGetter();
			place.getCoordsFromAdress(adress, postcode)
			.then((coords) => {
                spotMap = new UrbanMap(mapContainer, coords.lat, coords.long, zoomDegree);
			})
			.catch((error)=>{
				console.error(error);
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
	(()=>{
        const mapContainer = document.getElementById("displaySpotMapContainer");
		const detailItem = document.getElementById("detailItem");    
		if(!mapContainer || !detailItem){return}
		let fields = document.querySelectorAll("li[data-type]");
		var coords = {
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
        displayMap(mapContainer, coords);
    
	})();

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
    
    /********** Display map with marker from adress input in a form *****************/
    (()=>{
        const mapContainer = document.getElementById("displayMapFromAdressContainer");
        if(null === mapContainer){return};
        mapContainer.classList.add("hidden");
        
        const newAdressButton = document.getElementById("newAdressButton") ;
        newAdressButton.classList.add("hidden");
    
        var adressField = document.getElementById("adressMap");
        var postcodeField = document.getElementById("postcodeMap");
    
        const setMap = () => {
            
            if(!mapContainer){return}
                
                let savedAdresses = loadDataFromDomStorage("savedAdresses", "session");

                if(savedAdresses){
                    adressField.value = savedAdresses[0].adress ;
                    postcodeField.value = savedAdresses[0].postcode ;
                }

                let adress = adressField.value ? adressField.value : null ;
                let postcode = postcodeField.value ? postcodeField.value : null;
                
                if(!adress || !postcode){
                    return;
                }
                
                if(postcodeField.value.length !== 5){
                    return ;
                }
    
                let place = new AdressGetter();
                place.getCoordsFromAdress(adress, postcode)
                .catch((error)=>{
                    console.error(error);
                    console.warn("Impossible de récupérer les coordonnées de l'emplacement à partir de son adresse.");
                })
                .then((coords) => {
                    adressField.setAttribute("disabled", "");
                    postcodeField.setAttribute("disabled", "");
                    adressField.style.cursor ="not-allowed";
                    postcodeField.style.cursor ="not-allowed";

                    newAdressButton.style.display = "block";
                    newAdressButton.style.margin = "15px auto";
                    mapContainer.classList.remove("hidden");
                    displayMap(mapContainer, coords);
                })
                .catch((error)=>{
                    console.error(error);
                    console.warn("Impossible d'afficher la carte avec les coordonnées fournies.");
                });
            
        }
    
        const checkFormData = () => {		
            if(adressField.value.length > 0 && postcodeField.value.length === 5){	       
                let postCodePattern = /[0-9]{5}/;
                if(postCodePattern.test(postcodeField.value)){
                    setMap();
                }
            }
        }

        setMap();

        adressField.addEventListener("blur", ()=>{
            checkFormData();
        });
    
        postcodeField.addEventListener("input", ()=>{
            postcodeField.value = postcodeField.value.trim();
            if(postcodeField.value.length > 5){
                postcodeField.value	= postcodeField.value.substr(0, 5);
            }
                checkFormData();
        });
        
/*        form.addEventListener("submit", (e)=>{
            e.preventDefault();
            removeDataFromDomStorage("savedCoords", "session");
            removeDataFromDomStorage("savedAdresses", "session");
        }) 
*/
    
        newAdressButton.addEventListener("click", (e)=>{
            e.preventDefault();
            removeDataFromDomStorage("savedCoords", "session");
            removeDataFromDomStorage("savedAdresses", "session");
            location.reload();
        });

        

        
    })();

    






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
    
    if(document.querySelector("spotForm")){
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
    
