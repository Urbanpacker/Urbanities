<?php ob_start();
if(!$spotsCategorized): ?>
	<h1><?= $h1 ?></h1>
	<a href="?page=categoryList">Revenir à la page de recherche par catégorie</a>
<?php else : ?>
		<section class="list" id="categorizedSpotsList">
			<h1><?= $h1 ?></h1>
<?php foreach($spotsCategorized as $spot): 
	$spotImage = $spot['spotImage']; 
	$spotName = $spot['spotName']; ?>
				<article id="<?=$spot['spotId']?>">
					<picture>
						<source srcset="public/img/categories/<?=$spotImage?>_small.jpg" media="(max-width: 720px)">
						<source srcset="public/img/categories/<?=$spotImage?>_medium.jpg" media="(max-width: 1024px)">
						<img src="public/img/categories/<?=$spotImage?>_big.jpg" alt="<?=$spotName?>" />
					</picture>	
						<a href="?page=spotDetail&spotId=<?=$spot['spotId']?>"><h3><?=$spotName?></h3></a>
						<p><?=$spot['catName']?></p>
						<p class="postcode"><?=$spot['spotPostcode']?></p>
						<p><?=$spot['spotAdress']?></p>
					</article>
<?php 		endforeach; ?>
        </section>
<?php endif ?>
<?php $content = ob_get_clean(); ?>
<?php require('views/template.php'); ?>