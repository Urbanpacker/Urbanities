<?php
	ob_start();
	define("MEMBER_EXISTING_DATA_ID", $memberExistingData['memberId']);
	define("MEMBER_EXISTING_DATA_COUNTRY_NAME", $memberExistingData['countryName']);
?>
			<section id="memberForm" class="form">
				<h1><?= $h1 ?></h1>
<?php if(!MEMBER_EXISTING_DATA_ID): ?>
				<form class="" method="post" action ="?page=recordMember">
<?php else: ?>
				<form class="" method="post" action ="?page=editMember">
<?php endif;?>
					<fieldset>
				        <legend>Informations sur le membre</legend>
						<ul>
							<li><label class="required" for="name">Nom</label>
								<input type="text" id="name" name="name" value="<?= $memberExistingData['memberName'] ?>" required maxlength="100"/>
							</li>
							<li><label class="required" for="name">Prénom</label>
								<input type="text" id="firstname" name="firstname" value="<?= $memberExistingData['memberFirstname'] ?>" required maxlength="100"/>
							</li>
							<li><label class="required" for="pseudo">Pseudo</label>
								<input type="text" id="pseudo" name="pseudo" value="<?= $memberExistingData['memberPseudo'] ?>" required maxlength="100"/>
							</li>
							<li><label class="required" for="email">Email</label>
								<input type="email" id="email" name="email" value="<?= $memberExistingData['memberEmail'] ?>" required maxlength="100"/>
							</li>
							<li><label class="required" for="password">Mot de passe</label>
								<input type="password" id="password" name="password" value="<?= $memberExistingData['memberPassword'] ?>" required maxlength="100"/>
							</li>
							<li><label class="required" for="postcode">Code postal</label>
								<input type="text" id="postcode" name="postcode" value="<?= $memberExistingData['memberPostcode'] ?>" required maxlength="5"/>
							</li>
							<li><label for="countryId">Pays</label>
						    	<select id="countryId" name="countryId">
									<option value="">-- Sélectionner un pays --</option>
<?php foreach($countries as $country): 
	$countryName = $country['countryName'] ; ?>
									<option value="<?= $country['countryId']?>"<?php if(($countryName == 'France' && $countryName != MEMBER_EXISTING_DATA_COUNTRY_NAME) || $countryName == MEMBER_EXISTING_DATA_COUNTRY_NAME){echo ' selected ';} ?>><?= $countryName?></option>
<?php endforeach; ?>
					            </select>
				            </li>
							<li>
								<button class="button" type="submit"><?php if(!MEMBER_EXISTING_DATA_ID): ?>Créer le compte<?php else: ?>Mettre à jour le compte<?php endif; ?></button>
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
	