<?php
ob_start(); ?>
	<section class="list" id="categories">
		<h1>Choisissez votre type de spot</h1>
		<?php foreach($categories as $category):
			$catImage = $category['catImage'];
			$catName = $category['catName'];
			?>
			<article id="image_<?= $catImage?>">
					<picture>
						<source srcset="public/img/categories/<?= $catImage?>_small.jpg" media="(max-width: 720px)">
						<source srcset="public/img/categories/<?= $catImage?>_medium.jpg" media="(max-width: 1024px)">
						<img src="public/img/categories/<?= $catImage?>_big.jpg" alt="<?= $catName?>" />
					</picture>
			
					<a href="?page=spotsByCategory&catId=<?= $category['catId']?>" title="<?= $catName?>"><h2><?= $catName?></h2></a>
					<p><?=$category['catDescription']?></p>
				</article>
		<?php endforeach; ?>		
	</section>
<?php $content = ob_get_clean(); ?>
<?php require('views/template.php'); ?>