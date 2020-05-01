class UserLocation {
	constructor(gettingCoordsMaxDelay = 5000, minGeolocAccuracy = 50){
		//Set the time allocated to the navigator to get the current position
		this.gettingCoordsMaxDelay = gettingCoordsMaxDelay ;
		//Set the minimal accuracy of the process of geolocation authorized to accept the data thzt provides
		this.minGeolocAccuracy = minGeolocAccuracy ;
	}
	getCurrentPosition = () => {
		return new Promise((successCallback, failureCallback) => {
			if(navigator.geolocation){
				navigator.geolocation.getCurrentPosition(successCallback, failureCallback, {enableHighAccuracy:false, maximumAge:Infinity, timeout: this.gettingCoordsMaxDelay});
			} else {
				let errorMessage = "Votre navigateur ne supporte pas la fonction geolocalisation.";
				failureCallback(errorMessage);
			}
		});
	}

	//Callback in case of success of the geolocation
	successGeo = ({coords}) => {
		return new Promise((successCallback, failureCallback) => {
			let coordsAccuracyReliable = coords.accuracy <= this.minGeolocAccuracy ? true : false;
			if(coordsAccuracyReliable){
				console.log("Coordonnées courantes récupérées");
				console.log("La lat est "+coords.latitude);
				console.log("La long est "+coords.longitude);
				console.log("La précision de la mesure (en mètres) est "+coords.accuracy);
				successCallback(coords);
			} else{
				let warnMessage = "La précision de la géolocalisation n\'est pas suffisante (inférieure à "+ this.minGeolocAccuracy + " mètres) pour indiquer votre position de manière fiable. \n Veuillez rafraîchir la page et réeessayer ultérieurement.";
				failureCallback(warnMessage);
			}
		});
	}
	
	failGeo = (error) => {
		if((typeof error) === "string"){
			console.warn(error);
			return;
		}
		let errorMessage = "";
		if(error.code === undefined || error.message === undefined){return}
		switch(error.code){
			case error.UNKNOWN_ERROR :
				errorMessage += "Une erreur inconnue s'est produite.";
				break;
			case error.PERMISSION_DENIED :
				errorMessage += "La permission de récupérer la position n'a pas été accordée.\nVeuillez rafraîchir la page et réeessayer.";
				break;
			case error.POSITION_UNAVAILABLE :
				errorMessage += "La position n'a pas pu être déterminée.";
				break;
			case error.TIMEOUT :
				errorMessage += "Le délai maximal d'attente défini pour récupérer la position est dépassé.";
				break;
			default :
				errorMessage += error.message;
		}
		console.error(errorMessage);
	}
	
	//Set the current position in the session DOMstorage, replace the former one if already present
	setNewPositionIntoStorage = (coords) => {
		console.log("test : setNewPositionIntoStorage ");
		let formerMemberPosition = loadDataFromDomStorage('memberPosition', 'session');
		if(formerMemberPosition !== undefined){
			if(formerMemberPosition === coords){
				return;
			}
			removeDataFromDomStorage('memberPosition', 'session');
		}
		saveDataToDomStorage('memberPosition', coords, 'session');
	}
}

export {UserLocation};
