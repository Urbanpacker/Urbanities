<?php
ob_start(); ?>
	<section class="list" id="categories">
		<h1>Choisissez votre type de spot</h1>
		<?php foreach($categories as $category): ?>		
			<article id="image_<?= $category['catImage']?>">
					<picture>
						<source srcset="public/img/categories/<?= $category['catImage']?>_small.jpg" media="(max-width: 720px)">
						<source srcset="public/img/categories/<?= $category['catImage']?>_medium.jpg" media="(max-width: 1024px)">
						<img src="public/img/categories/<?= $category['catImage']?>_big.jpg" alt="<?= $category['catName']?>" />
					</picture>
			
					<a href="?page=spotsByCategory&catId=<?= $category['catId']?>" title="<?= $category['catName']?>"><h2><?= $category['catName']?></h2></a>
					<p><?=$category['catDescription']?></p>
				</article>
		<?php endforeach; ?>		
	</section>
<?php $content = ob_get_clean(); ?>
<?php require('views/template.php'); ?>