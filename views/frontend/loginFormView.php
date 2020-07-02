<?php
ob_start(); ?>
	<section id="loginForm" class="form">
    	<h3>Veuillez vous connecter afin d'accéder à l'annuaire</h3>
    
    	<form class=" form login" method="post" action="?page=login">
    		<fieldset>
                <legend>Informations de connexion</legend>
    		    <ul>
    				<li><label class="required" for="email">Email</label>
    					<input type="email" id="email" name="email" value="" required/>
    				</li>
    				<li><label class="required" for="password">Mot de passe</label>
    					<input type="password" id="password" name="password" value="" required/>
    				</li>
			<li class="button">
    			<button id="submit" type="submit">
    			Se connecter</button>
    		</li>
    		<li class="button">
    			<button id="cancel" type="reset">
    			Annuler</button>
    		</li>
			
    		</ul>
    		</fieldset>
			<div><a href="index.php?page=addNewMember">Créer un compte</a></div>
    	</form>
    	
<?php if($invalidLogin):?>
       <p class="error">Les informations saisies sont incorrectes, veuillez réessayer.</p>
<?php endif; ?>
    </section>
<?php $content = ob_get_clean(); ?>
<?php require('views/template.php'); ?>
	

