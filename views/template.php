<!DOCTYPE html>
<html lang="fr">
	<head>
		<title><?= $title ?></title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- Vendors -->
		<!-- Font awesome -->
		<script src="https://kit.fontawesome.com/28e1129916.js"></script>
		<!--- Loads Leaflet stylesheet and JS API and for the inclusion of an OSM Map -->
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css">
		<script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"></script>
		<!--Personnal CSS -->
		<link rel="stylesheet" href="public/css/style.css">
		<!--Personnal scripts -->
		<script src="public/js/utilities.js"></script>
		<!--<script src="public/js/geolocation.js"></script>-->
		<script src="public/js/adresseGetter.js"></script>
		<script src="public/js/mapCreator.js"></script>
		<script src="public/js/form.js"></script>
		<script src="public/js/main.js"></script>
		
	</head>
	<body>
		<!--HEADER-->
		<header>
			<div id="logo">
				<a href="index.php">
					<h1><i class="fas fa-city"></i>Urbanities<i class="fas fa-city"></i></h1>
				</a>
			</div>
<?php if(isset($_SESSION['memberId'])) : ?>
			<nav id="topMenu" class="">
			 	<a href="#topMenu" class="menuButton" id="openTopMenu" data-copyright="Timothy Miller [CC BY-SA 3.0 (https://creativecommons.org/licenses/by-sa/3.0)], via Wikimedia Commons" data-ref="https://commons.wikimedia.org/wiki/File:Hamburger_icon.svg"><img alt="Ouvrir le menu" src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b2/Hamburger_icon.svg/32px-Hamburger_icon.svg.png"/></a>
			    <a href="#" class="menuButton" id="closeTopMenu" data-copyright="Timo Müller [Public domain], via Wikimedia Commons" data-ref="https://commons.wikimedia.org/wiki/File:Saint_Andrew%27s_cross_black.svg"><img  alt="Fermer le menu" src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b1/Saint_Andrew%27s_cross_black.svg/64px-Saint_Andrew%27s_cross_black.svg.png"/></a>
				<ul>
					<li><a href="?page=home#memberProfile">Informations personnelles</a>
					</li>
					<li><a href="?page=home#favoriteSpots">Vos spots favoris</a>
					</li>
					<li><a href="?page=home#generalLatestSpots">Derniers spots en ligne</a>
					</li>
						<li><a href="?page=home#mapContainer">Votre position actuelle</a>
					</li>
					<li>
						<a href="?page=addNewSpot">Ajouter un spot
						</a>
					</li>
					<li>
						<a href="?page=categoryList">Types de spot</a>
					</li>
					<li>
						<a id="logout" href="?page=logout">Se deconnecter</a>
					</li>
				</ul>
			</nav>
			<p id="memberNotice" class="info_header">Vous êtes connecté sous le pseudo "<?= $_SESSION['memberPseudo'] ?>"</p>
<?php endif ?>
		</header>
		<!--MAIN CONTENT-->
		<main>
<?= $content ?>
		</main><!--END OF MAIN CONTENT-->
		<!--FOOTER-->
		<footer>
			<aside>
				<h2>Sites en lien avec le sujet</h2>
				<ul>
					<li>
						<a href="#" title="Projet OpenStreetMaps">OpenStreetMaps</a>
					</li>
					<li>
						<a href="#" title="Annuaire des équipements de la ville de Lyon">Equipements de le ville de Lyon</a>
					</li>
					<li>
						<a href="#" title="Service Google Maps">Google Maps</a>
					</li>
					<li>
						<a href="#" title="Site de Sleeping in Airport">Sleeping in Airport</a>
					</li>
				</ul>
			</aside>
			<aside>
			<p>
				<small id="licence">
					Cet exercice a été réalisé par <strong>Jules Desmaroux</strong> dans le cadre de la formation d'Intégrateur - Développeur en réalisation d'applications web suivi au sein de la <a href="#">3W Academy</a>. Toute reproduction de tout ou partie de ce site web est interdite sans l'accord de son auteur.
				</small>
			</p>
			</aside>
<!--
				<h3>Compte de test membre administrateur</h3>
				<ul>
					<li>login : admintest@yopmail.com</li>
					<li>password : testtest</li>
				</ul>
-->			
		</footer>
	</body>
</html>
