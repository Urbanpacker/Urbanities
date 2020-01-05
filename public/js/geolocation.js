'use strict';

/*************************************************************************************************/
/* ****************************************** DATA ****************************************** */
/*************************************************************************************************/

// Defines the minimal geolocation accuracy accepted to display a map and use the coordinates to fill a form
const minGeolocAccuracy = 30 ;

// Frame in which the map will be displayed
//id of the HTML ELement mapContainer which be designed with the DOM element mapContainer
const HtmlMapContainer = 'mapContainer';

// DOM Element mapContainer
var mapContainer ;

// HTML Element body, to display a "loading" cursor icon when processing (getting the current position)
var body ;

// Boolean indicating if the geolocation has succeed or not
var geolocationSuccess ;

// Button to click to display the map
var mapTrigger ;

/*************************************************************************************************/
/* ****************************************** FUNCTIONS ****************************************** */
/*************************************************************************************************/

// Display an OSM Map with positions markers

function showOSMMap(){
    
    var positionsList = [] ;
    
    positionsList.unshift(loadDataFromDomStorage('memberPosition', 'session') || []);

	if (!mapContainer.id || (positionsList.length < 1)){
		return false;
	}
	
	// Initializes a OSM Map with options
	var map = L.map(mapContainer.id).setView(positionsList[0], 15);
	mapContainer.classList.add("map");
	// Loads the OSM tileLayer (map background) */
	L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
		maxzoom:19,
		attribution:'(c)<a href="http://www.openstreetmap.org/copyright">OpenStreetMap contributors</a>'
	}).addTo(map);
	
	// Add a marker to each identified position onto the map
	for(let i = 0, c = positionsList.length ; i < c ; ++i){
		L.marker(positionsList[i]).addTo(map);
	}
	return true;
}


/********** Functions to get the visitor's current position *********/

// If the user clicks on the page before answer to the ageolocation authorisation prompt, the cursor change to normal appeareance
function noUserAnswer(){
	body.classList.remove('loading');
}

function getCurrentPosition(){
	removeDataFromDomStorage('memberPosition', 'session');
	body = document.querySelector('body');
	body.addEventListener('click', noUserAnswer, {once : true, capture : true});
	body.classList.add('loading');
	if(navigator.geolocation){
		navigator.geolocation.getCurrentPosition(successGeo, failGeo, {enableHighAccuracy:true, maximumAge:Infinity, timeout: 5000});
		return true;
		
	} else{
		let errorMessage = "Le navigateur ne prend pas en charge l'API de geolocalisation";
		geolocErrorMessage(errorMessage);
		return false;
	}
}

function successGeo(position){
	if(!getMemberPosition(position)){
		body.classList.remove('loading');
		let errorMessage = 'La position n\'a pas pu être identifiée. Veuillez rafraîchir la page et réessayer.';
		geolocErrorMessage(errorMessage);
		return false;
	}
	if(!showOSMMap()){
		let errorMessage = 'La carte n\'a pas pu s\'afficher. Veuillez rafraîchir la page et réessayer.';
		geolocErrorMessage(errorMessage);
		return false;
	}
	geolocationSuccess = true ;
	setGeoDataIntoForm();
	body.classList.remove('loading');
}

// In case of geolocation failure, writes the erro message in place of the expected map
function failGeo(error){
	let errorMessage ="";
	switch(error.code){
		case error.UNKNOWN_ERROR :
			errorMessage ="une erreur inconnue s'est produite.";
			break;
		case error.PERMISSION_DENIED :
			errorMessage ="la permission de récupérer la position n'a pas été accordée.\nVeuillez rafraîchir la page et réeessayer.";
			break;
		case error.POSITION_UNAVAILABLE :
			errorMessage ="La position n'a pas pu être déterminée.";
			break;
		case error.TIMEOUT :
			errorMessage ="Le délai maximal d'attente défini pour récupérer la position est dépassé.";
			break;
		default :
			errorMessage = error.message;
	}
	errorMessage = 'Un problème est survenu lors de la géolocalisation : '+errorMessage;
	geolocErrorMessage(errorMessage);
	geolocationSuccess = false ;
	body.classList.remove('loading');
}

function getMemberPosition(currentPosition){
	if(!currentPosition){
		return false;
	}
	let coordsAccuracyReliable = currentPosition.coords.accuracy < minGeolocAccuracy ? true : false;
	if(coordsAccuracyReliable){
		let memberPosition = [currentPosition.coords.latitude, currentPosition.coords.longitude] ;
		saveDataToDomStorage('memberPosition', memberPosition, 'session');
		return true;
	} else {
		let errorMessage = 'Désolé, la précision de la géolocalisation n\'est pas suffisante (inférieure à 30 mètres) pour indiquer votre position de manière fiable. \n Veuillez rafraîchir la page et réeessayer ultérieurement.';
		geolocErrorMessage(errorMessage);
		return false ;
	}
}

function geolocErrorMessage(errorMessage){
	if(document.getElementById('errorGeolocMessage')){
		return;
	}
	let errorGeolocMessage = document.createElement('p');
	errorGeolocMessage.id = 'errorGeolocMessage';
	errorGeolocMessage.style.margin = "1rem auto";
	errorGeolocMessage.classList.add('error');
	errorGeolocMessage.textContent = errorMessage;
	mapContainer.append(errorGeolocMessage);
}


/*********************MAP DISPLAYER***********************/

/* Display an OSM map with the current position if the geolocation succeeds and if the accuracy is important enough */
function showCurrentPosition(){
	getCurrentPosition();
	mapTrigger.style = "display:none";
	// Remove any existing geolocation fail notice
	let errorGeolocMessage = document.getElementById('errorGeolocMessage');
	if(errorGeolocMessage){
		errorGeolocMessage.parentNode.removeChild(errorGeolocMessage);
	}
	if(document.querySelector('form #latitude') && document.querySelector('form #latitude')){
		setGeoDataIntoForm();
	}
}	


/************* COORDINATES FORM FILLER  *******************/

function setGeoDataIntoForm(){
	var useCurrentPositionButton;
	let memberPosition ;
	
	let timeOutGeolocation = 60 ;
	let timeCounter = 0 ;
	
	// While the navigator getPosition() function is searching for the current position, the creation and display of a "Add the coordinates to the form" waits for the setting of the coordinates
	var intervalId = window.setInterval((function(){
		if(geolocationSuccess === false){
			mapTrigger.style = "display:inline-block";
		}
		
		if(useCurrentPositionButton === undefined && geolocationSuccess === true){
			useCurrentPositionButton = document.createElement('button');
			useCurrentPositionButton.id = 'addCurrentPositionToForm';
			useCurrentPositionButton.textContent = 'Ajouter les coordonnées trouvées au formulaire';
			useCurrentPositionButton.setAttribute('type','button');
			useCurrentPositionButton.classList.add('optionButton');
			let form = document.querySelector('form');
			if(form && !document.getElementById('addCurrentPositionToForm')){
				form.append(useCurrentPositionButton);
			}
		}
		memberPosition = loadDataFromDomStorage('memberPosition', 'session') || [] ;
	
		if((useCurrentPositionButton !== undefined && memberPosition.length > 0)){
			useCurrentPositionButton.addEventListener('click', function(){
				document.getElementById('latitude').value = memberPosition[0];
				document.getElementById('longitude').value = memberPosition[1];
			});
		}
		
		if(memberPosition.length > 0 || geolocationSuccess === false){
			window.clearInterval(intervalId);
			return;
		}
	}), 1000);
}


/**********************************************************/
/****************** MAIN CODE ****************************/
/**********************************************************/

window.addEventListener('DOMContentLoaded', (function(){
	mapContainer = document.getElementById(HtmlMapContainer);
	
	mapTrigger = document.querySelector('button#mapTrigger');
	if(mapTrigger){
		mapTrigger.addEventListener('click',showCurrentPosition);
	}
}));
