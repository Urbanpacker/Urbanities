<?php ob_start(); ?>
			<h1><?= $h1 ?></h1>
			<section id="detailItem" class="detailItem">
				<picture>
					<source srcset="public/img/categories/<?= $spotDetail['spotImage'] ?>_small.jpg" media="(max-width: 720px)">
					<source srcset="public/img/categories/<?= $spotDetail['spotImage'] ?>_medium.jpg" media="(max-width: 1024px)">
					<img src="public/img/categories/<?= $spotDetail['spotImage'] ?>_big.jpg" alt="">
				</picture>
<?php if(!$spotInFavorites): ?>
				<a id="addToFav" class="btn" href="?page=addToFav&spotId=<?= $spotDetail['spotId']?>&memberId=<?=$_SESSION['memberId']?>">Ajouter aux favoris</a>
<?php else: ?>
				<a id="removeFromFav" class="btn" href="?page=removeFromFav&spotId=<?= $spotDetail['spotId']?>&memberId=<?=$_SESSION['memberId']?>">Enlever des favoris</a>
<?php endif; ?>
				<ul>
					<li data-type='postcode' data-content="<?= $spotDetail['spotPostcode'] ?>"><p>Code postal</p><p><?= $spotDetail['spotPostcode'] ?></p></li>
					<li data-type='adress' data-content="<?= $spotDetail['spotAdress'] ?>"><p>Adresse</p><p><?= $spotDetail['spotAdress'] ?></p></li>
					<li data-type='country' data-content="<?= $spotDetail['countryName'] ?>"><p>Pays</p><p><?= $spotDetail['countryName'] ?></p></li>
					<li data-type='longitude' data-content="<?= $spotDetail['spotLongitude'] ?>"><p>Longitude</p><p><?= $spotDetail['spotLongitude'] ?></p></li>
					<li data-type='latitude' data-content="<?= $spotDetail['spotLatitude'] ?>"><p>Latitude</p><p><?= $spotDetail['spotLatitude'] ?></p></li>
					<li data-type='altitude' data-content="<?= $spotDetail['spotAltitude'] ?>"><p>Altitude (en mètres)</p><p><?= $spotDetail['spotAltitude'] ?></p></li>
					<li><p>Auteur du spot</p><p><?= $spotDetail['memberPseudo'] ?></p></li>
					<li><p>Ajouté le</p><p><?= $spotDetail['spotCreationDate'] ?></p></li>
					<li><p>Visible par</p><p><?= $visibility ?></p></li>
					<li data-content="<?= $spotDetail['spotDescription'] ?>" class="description"><p>Description</p><p><?= $spotDetail['spotDescription'] ?></p></li>
				</ul>
<?php if($updatable) :?>
				<div><a class="btn edit" href="?page=editSpotForm&spotId=<?= $spotDetail['spotId'] ?>">Mettre à jour ce spot</a></div>
<?php endif; ?>
<?php if($deletable) :?>
				<div><a class="btn delete" href="?page=deleteSpot&spotId=<?= $spotDetail['spotId'] ?>">Supprimer ce spot</a></div>
<?php endif; ?>
			</section>
<?php $content = ob_get_clean(); ?>
<?php require('views/template.php'); ?>