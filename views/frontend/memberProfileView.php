<?php
ob_start(); ?>
			<section class="detailItem" id="memberProfile">
				<h2>Vos informations personnelles</h2>
				<article>	
					<ul>
						<li>
							<p>Pseudo</p>
							<p><?= $_SESSION['memberPseudo'] ?></p>
						</li>
						<li>
							<p>Nom</p>
							<p><?= $_SESSION['memberName'] ?></p>
						</li>
						<li>
							<p>Prénom</p>
							<p><?= $_SESSION['memberFirstname'] ?></p>
						</li>		
						<li>
							<p>Code postal</p>
							<p><?= $_SESSION['memberPostcode'] ?></p>
						</li>
						<li>
							<p>Email</p>
							<p><?= $_SESSION['memberEmail'] ?></p>
						</li>
						<li>
							<p>Inscrit depuis le</p>
							<p><?= $_SESSION['memberCreationDate'] ?></p>
						</li>
					</ul>
				</article>	
			</section>
			<section class="list" id="favoriteSpots">
				<h2>Vos spots favoris</h2>
<?php if($favoritesSpots):
	foreach($favoritesSpots as $spot): 
		$spotId = $spot['spotId']; ?>
				<article data-spotId="<?=$spotId?>">
					<a href="?page=spotDetail&spotId=<?=$spotId?>"><h3><?=$spot['spotName']?></h3></a>
					<p><?=$spot['catName']?></p>
					<p class="postcode"><?=$spot['spotPostcode']?></p>
					<p><?=$spot['spotAdress']?></p>
				</article>
<?php endforeach; ?>
<?php else: ?>
				<p>Vous n'avez pas encore ajouté de spots à vos favoris</p>
<?php endif ?>
			</section>
			<section class="list" id="generalLatestSpots">
				<h2>Derniers spots en ligne</h2>
<?php foreach($latestSpots as $spot):
	$spotId = $spot['spotId']; ?>
				<article data-spotId="<?=$spotId?>">
					<a href="?page=spotDetail&spotId=<?=$spotId?>"><h3><?=$spot['spotName']?></h3></a>
					<p><?=$spot['catName']?></p>
					<p class="postcode"><?=$spot['spotPostcode']?></p>
					<p><?=$spot['spotAdress']?></p>
				</article>
<?php endforeach; ?>
			</section>

<<<<<<< HEAD
			<section>
				<h2>Rechercher une adresse</h2>
				<!--<style> input, label, button{ flex-flow : column wrap;  margin: 5px auto; font-size: 1.6rem;} </style>
-->				<form style="display: flex; flex-flow: column nowrap; justify-content: space-between;">
					<p>Saisissez une adresse puis un code postal</p>
					<label for="adress">Adresse</label>
					<input id="adress"/>
					<label for="postcode">Code postal (5 chiffres)</label>
					<input id="postcode"/>
					<button id="newAdressButton" type="submit">Saisir une nouvelle adresse</button>
				</form>
				<div style="margin:auto; width:100%">
					<div class="mapContainer" id="mapContainer">
					</div>
				</div>
			</section>
=======
			<section id="displayMapFromAdress">
				<form>
					<h2>Afficher une adresse sur une carte</h2>
					<p>Saisissez une adresse puis un code postal</p>
					<label for="adressMap">Adresse</label>
					<input id="adressMap"/>
					<label for="postcodeMap">Code postal (5 chiffres)</label>
					<input id="postcodeMap"/>
					<button id="newAdressButton" class="optionButton hidden" type="submit">Nouvelle recherche.</button>
				</form>
					<div class ="hidden mapContainer" style="height:500px; width:100%; border:solid 2px black;" id="displayMapFromAdressContainer">
					</div>
			</section>
			

>>>>>>> master
			<section class="hidden" id="currentPosition">
				<h2>Votre position actuelle</h2>
				<button id="mapTrigger" class="optionButton" type="button">Cliquez ici pour afficher la carte.</button>
				<div class="hidden mapContainer" id="currentPositionmapContainer">
				</div>
			</section>
			<script type="module" src="public/js/moduls/adressMapper.js"></script>
<?php $content = ob_get_clean(); ?>
<?php require('views/template.php'); ?>
	