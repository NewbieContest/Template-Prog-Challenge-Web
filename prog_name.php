<?php

session_start();

/* START NC Emulation */
$context['user']['username'] = 'Beta test';
/* END NC Emulation */

// Config
define('CHALL_SESSION', 'Chall_Name'); // Remplacer Chall_Name par un intitulé unique lié au chall
define('CHALL_FLAG', 'NC{...}'); // Remplacer par le flag !
define('MAX_RESOLUTION_TIME', 2); // En secondes

// Check acces
if(!isset($context['user']['username']) || $context['user']['username'] == '')
	die('<h3 align="center">Attention, vous n\'&ecirc;tes pas loggu&eacute; !</h3><br /><p>Vous devez faire suivre votre session &agrave; l\'ex&eacute;cution de ce script</p>');

if(!empty($_SESSION[CHALL_SESSION]) && is_array($_SESSION[CHALL_SESSION])){
	$inTime = (microtime(true)-$_SESSION[CHALL_SESSION]['time_start'] < MAX_RESOLUTION_TIME)? true : false;
	if(!isset($_GET['res']) && isset($_POST['res']))
		$_GET['res'] = $_POST['res'];
	if(!isset($_GET['res']) && $inTime){
		die('Merci de patienter au moins '.(int)MAX_RESOLUTION_TIME.' secondes avant de faire une nouvelle tentative.');
	}elseif(isset($_GET['res'])){
		$res = $_SESSION[CHALL_SESSION]['res'];
		unset($_SESSION[CHALL_SESSION]);
		if(is_string($_GET['res'])){ // Check format de la réponse
			if($inTime){
				if($res == $_GET['res']){ // Check de la réponse
					die('Bravo ! Le mot de passe pour valider est <b>'.CHALL_FLAG.'</b>');
				}else{
					die('Rat&eacute; !');
				}
			}else{
				die('Trop lent !! Vous n\'avez que '.(int)MAX_RESOLUTION_TIME.' secondes pour envoyer votre r&eacute;ponse');
			}
		}else{
			die('Erreur : Le résultat doit être une chaine de caractères !'); // Message d'erreur en cas de format incorrect
		}
	}
}

/* START HELP Optionnel */
if(isset($_GET['help'])){
?><html>
<head>
	<meta charset="utf-8">
	<title>NC Chall ... - Help</title>
</head>
<body>
	<p>Blabla description</p>
</body>
</html>
<?php
	die();
}
/* END HELP Optionnel */

// Process

$res = 'valeur_a_trouver'; // Démo chall ?res=valeur_a_trouver

$_SESSION[CHALL_SESSION] = array(
	'res' => $res, // Enregistrement dans la session
	'time_start' => microtime(true)
);