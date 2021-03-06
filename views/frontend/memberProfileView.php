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
			

			<section class="hidden" id="currentPosition">
				<h2>Votre position actuelle</h2>
				<button id="mapTrigger" class="optionButton" type="button">Cliquez ici pour afficher la carte.</button>
				<div class="hidden mapContainer" id="currentPositionmapContainer">
				</div>
			</section>
<?php $content = ob_get_clean(); ?>
<?php require('views/template.php'); ?>
	