<?php
	ob_start();
	define("SPOT_EXISTING_DATA_ID", $spotExistingData['spotId']);
	define("SPOT_EXISTING_DATA_COUNTRY_NAME", $spotExistingData['countryName']);
	define("SPOT_EXISTING_DATA_VISIBILITY", $spotExistingData['spotVisibility']);
?>
<<<<<<< HEAD
			<section id="spotForm" class="form">
=======
			<section id="sportForm" class="form">
>>>>>>> master
				<h1><?= $h1 ?></h1>
				<form class="" method="post" action ="?page=recordSpot">
					<input type="hidden" id="memberId" name="memberId" value="<?= $authorId ?>"/>
<?php if(SPOT_EXISTING_DATA_ID): ?>
					<input type="hidden" id="spotId" name="spotId" value="<?= SPOT_EXISTING_DATA_ID ?>"/>
<?php endif;?>
					<fieldset>
				        <legend>Informations sur le lieu</legend>
						<ul>
							<li><label class="required" for="categoryName">Type de lieu</label>
								<select id="categoryId" name="categoryId" required>
									<option value="">Saisissez un type de lieu</option>
<?php foreach($categories as $category):
	$catName = $category['catName'] ;
?>
									<option value="<?= $category['catId']?>" <?php if($catName == $spotExistingData['catName']){ echo 'selected'; } ?>><?= $catName?></option>
<?php endforeach; ?>
								</select>
					        </li>
							<li><label class="required" for="name">Nom du lieu</label>
								<input type="text" id="name" name="name" value="<?= $spotExistingData['spotName'] ?>" required maxlength="100"/>
							</li>
							<li><label for="latitude">Latitude</label>
								<input type="text" id="latitude" name="latitude" value="<?= $spotExistingData['spotLatitude'] ?>" maxlength="100"/>
							</li>
							<li><label for="longitude">Longitude</label>
								<input type="text" id="longitude" name="longitude" value="<?= $spotExistingData['spotLongitude'] ?>" maxlength="100"/>
							</li>
							<li><label for="altitude">Altitude</label>
								<input type="text" id="altitude" name="altitude" value="<?= $spotExistingData['spotAltitude'] ?>" maxlength="100"/>
							</li>
							<li><label for="postCode">Code postal du lieu</label>
								<input type="text" id="postcode" name="postcode" value="<?= $spotExistingData['spotPostcode'] ?>" maxlength="10"/>
							</li>
							<li><label class="required" for="countryId">Pays</label>
						    	<select id="countryId" name="countryId" required>
									<option value="">-- Sélectionner un pays --</option>
<?php foreach($countries as $country): 
	$countryName = $country['countryName'] ; ?>
									<option value="<?= $country['countryId']?>"<?php if(($countryName == 'France' && $countryName != SPOT_EXISTING_DATA_COUNTRY_NAME) || $countryName == SPOT_EXISTING_DATA_COUNTRY_NAME){echo ' selected ';} ?>><?= $countryName?></option>
<?php endforeach; ?>
					            </select>
				            </li>
							<li><label for="description">Spot visible par les autres utilisateurs ?</label>	    	
								<input type="radio" name="visibility" value="1" id="visibilityTrue" <?php if(SPOT_EXISTING_DATA_VISIBILITY == '1'){ echo 'checked="checked"'; }?>/>
								<label for="visibilityTrue">Oui</label>
								<input type="radio" name="visibility" value="0" id="visibilityFalse" <?php if(SPOT_EXISTING_DATA_VISIBILITY == '0' || SPOT_EXISTING_DATA_VISIBILITY == ''){ echo 'checked="checked"';}?>/>
								<label for="visibilityFalse">Non</label>
							</li>
							<li><label class="required" for="adress">Adresse (240 caractères maximum)</label>
								<textarea class="longTextArea" id="adress" name="adress" required><?= $spotExistingData['spotAdress'] ?></textarea>
							</li>
							<li><label for="description">Description (240 caractères maximum)</label>
								<textarea class="longTextArea" id="description" name="description" ><?= $spotExistingData['spotDescription'] ?></textarea>
							</li>
							<li>
								<button class="button" type="submit"><?php if(!SPOT_EXISTING_DATA_ID): ?>Créer le spot<?php else: ?>Mettre à jour le spot<?php endif; ?></button>
							</li>
							<li>
								<button class="button" type="reset">Annuler</button>
							</li>
						</ul>
					</fieldset>
				</form>
			</section>
<?php $content = ob_get_clean(); ?>
<?php require('views/template.php'); ?>
	