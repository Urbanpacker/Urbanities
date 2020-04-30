
    window.addEventListener('DOMContentLoaded', ()=>{
		let adress;
		let postcode;
		const mapContainerId = "mapContainer" ;
		const MAPCONTAINER = document.getElementById(mapContainerId);    
		const zoomDegree = 15 ;
//		var map ;    
//		var myMap ;
		
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
		
		function setMap(coords){
			var myMap = new UrbanMap(MAPCONTAINER, coords.lat, coords.long, zoomDegree);
			myMap.setOSMMap();
			myMap.setMarker();
			
		}

		if(coords.long && coords.lat){
			setMap(coords)
		}else{
			getCoordsFromAdress(adress, postcode)
			.then((result) => {
				setMap(result);
			},()=>{
				console.warn("Impossible de récupérer les coordonnées du spot à partir de son adresse.");
			});
		}
});