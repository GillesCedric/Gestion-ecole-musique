<?php
session_start();

$connect=new PDO("mysql:host=localhost;dbname=EIMOL","root","");
if(isset($_GET['id']) AND $_GET['id']>0 AND isset($_GET['pv']) AND !empty($_SESSION)){
$getid=intval($_GET['id']);
$getpriv=$_GET['pv'];
$habilitation=array('Administrateur','Utilisateur');
$requser=$connect->prepare('SELECT * FROM utilisateur WHERE id=?');
$requser->execute(array($getid)); 
$userinfo=$requser->fetch();
$today = date("F j, Y, g:i a");
$tableau_date=array('01' => 'Janvier', '02' => 'Février','03' => 'Mars','04' => 'Avril','05' => 'Mai','06' => 'Juin','07' => 'Juillet','08' => 'Août','09' => 'Septembre','10' => 'Octobre','11' => 'Novembre','12' => 'Décembre');
$msg="";

if(isset($_POST['submit'])){
	$tmp1=trim($_POST['nom']);
	$tmp2=trim($_POST['prenom']);
	$tmp3=trim($_POST['ville']);
	$tmp5=trim($_POST['profession']);
	$tmp6=trim($_POST['age']);
	$tmp7=trim($_POST['somme']);
	$tmp8=trim($_POST['email']);
	$tmp9=trim($_POST['nomtuteur']);
	$tmp10=trim($_POST['prenomtuteur']);
	$tmp11=trim($_POST['teleleve']);
	$tmp12=trim($_POST['teltuteur']);
	$tmp13=trim($_POST['date']);
	$tmp14=trim($_POST['categorie']);
	$tmp15=trim($_POST['formation']);
	$tmp16=trim($_POST['session']);
	$tmp17=trim($_POST['etude']);
	$tmp18=trim($_POST['preference']);
	if(!empty($tmp1) AND !empty($tmp2) AND !empty($tmp3) AND !empty($tmp5) AND !empty($tmp6) AND !empty($tmp7) AND !empty($tmp8) AND !empty($tmp9) AND !empty($tmp10) AND !empty($tmp12) AND !empty($tmp13) AND !empty($tmp14) AND !empty($tmp15) AND !empty($tmp16) AND !empty($tmp17) AND !empty($tmp18)){

			$nom=htmlspecialchars($_POST['nom']);
			$prenom=htmlspecialchars($_POST['prenom']);
			$ville=htmlspecialchars($_POST['ville']);
			$email=htmlspecialchars($_POST['email']);
			$profession=htmlspecialchars($_POST['profession']);
			$age=htmlspecialchars($_POST['age']);
			$nomtuteur=htmlspecialchars($_POST['nomtuteur']);
			$prenomtuteur=htmlspecialchars($_POST['prenomtuteur']);
			$teleleve=htmlspecialchars($_POST['teleleve']);
			$teltuteur=htmlspecialchars($_POST['teltuteur']);
			$receptioniste=$userinfo['login'];
			$somme=htmlspecialchars($_POST['somme']);
			$etablissement=htmlspecialchars($_POST['etablissement']);
			$classe=htmlspecialchars($_POST['classe']);
			$date=htmlspecialchars($_POST['date']);
			$categorie=htmlspecialchars($_POST['categorie']);
			$formation=htmlspecialchars($_POST['formation']);
			$session=htmlspecialchars($_POST['session']);
			$etude=htmlspecialchars($_POST['etude']);
			$preference=htmlspecialchars($_POST['preference']);
			$instrument=htmlspecialchars($_POST['instrument']);

			if(filter_var($email,FILTER_VALIDATE_EMAIL)){
			
					$reqeleve=$connect->prepare('INSERT INTO eleve (nom,prenom,ville,profession,age,etablissement,classe,tel,somme,receptioniste,avatar,date,categorie,formation,session,etude,preference,instrument) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
					$reqeleve->execute(array($nom,$prenom,$ville,$profession,$age,$etablissement,$classe,$teleleve,$somme,$receptioniste,"default.png",$date,$categorie,$formation,$session,$etude,$preference,$instrument));
			
					$reqtuteur=$connect->prepare('INSERT INTO tuteur (nom,prenom,teltuteur,email) VALUES (?,?,?,?)');
					$reqtuteur->execute(array($nomtuteur,$prenomtuteur,$teltuteur,$email));

					header("location: http://localhost/Logitem/index.php?id=".$getid."&pv=".$_SESSION['habilitation']."&msg=2");
			}else{
				$msg="L\'adresse e-mail saisie n\'est pas une adresse mail valide";
			}

			
	}else{
		$msg="Veuillez bien remplir tous les champs!!!Les champs marqués d\'une étoile sont obligatoires";
	}
	
}

if(isset($_POST['deconnexion'])){
	$_SESSION=array();
	session_destroy();
	header("location: http://localhost/Logitem/connexion.php");
}

if(isset($_POST['consulter'])){
	header("location: http://localhost/Logitem/consultation.php?id=".$getid."&pv=".$_SESSION['habilitation']);
}

if(isset($_POST['inscrire'])){

	header("location: http://localhost/Logitem/inscription.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']);
}

if(isset($_POST['supprimer'])){
	
	header("location: http://localhost/Logitem/supression_utilisateur.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']);
}

if(isset($_POST['modifier'])){

	header("location: http://localhost/Logitem/modifier_profil.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']);
}
if(isset($_POST['acceuil'])){

	header("location: http://localhost/Logitem/index.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']);
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Acceuil</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="shortcut icon" type="images/x-icon" href="css/img/favicon.ico" />
	<style type="text/css">
	body{
	background-image: url("css/img/M1.JPG");
	background-size: cover;
}
	.img{
		background-image: url('employes/avatars/<?=$userinfo['avatar'];?>');
		background-repeat: no-repeat;
		width: 130px;
		border-radius: 50%;
		margin-top: 10px;
		margin-left: 30px;
		margin-bottom: 10px;

	}
	.img:after{
		content: "";
		padding-bottom: 100%;
		display: block;
	}
	.rad{
	margin-left: 90px;
	}
	#vs{
		margin-left: 10px;
	}
	input[name='ajouter']{
	margin-top: 5px;
	margin-left: 15px;
	border-radius: 5px;
	}
	input[name='nom']{
	margin-top: 25px;
	margin-left: -42px;
	border-radius: 5px;
	position: absolute;
	}
	label[for="prenom"]{
		margin-left: 132px;
	}
	input[name='prenom']{
	margin-top: 25px;
	margin-left: -58px;
	border-radius: 5px;
	position: absolute;
	}
	label[for="ville"]{
		margin-left: 122px;
	}
	input[name='ville']{
	margin-top: 25px;
	margin-left: -118px;
	border-radius: 5px;
	position: absolute;
	}
	label[for="age"]{
		margin-left: 50px;
	}
	input[name='age']{
	margin-top: 25px;
	margin-left: -35px;
	border-radius: 5px;
	position: absolute;
	width: 32px;
	}
	select[name='profession']{
	margin-top: 25px;
	margin-left: -130px;
	border-radius: 5px;
	position: absolute;
	}
	label[for="profession"]{
		margin-left: 15px;
	}
	label[for="etablissement"]{
		position: absolute;
		margin-top: 60px;
		margin-left: -706px;
	}
	input[name='etablissement']{
	margin-top: 100px;
	margin-left: -706px;
	border-radius: 5px;
	position: absolute;
	}
	label[for="classe"]{
		position: absolute;
		margin-top: 60px;
		margin-left: -532px;
	}
	input[name='classe']{
	margin-top: 100px;
	margin-left: -532px;
	border-radius: 5px;
	position: absolute;
	width: 50px;
	}
	label[for="nomtuteur"]{
		position: absolute;
		margin-top: 60px;
		margin-left: -452px;
	}
	input[name='nomtuteur']{
	margin-top: 100px;
	margin-left: -452px;
	border-radius: 5px;
	position: absolute;
	}
	label[for="prenomtuteur"]{
		position: absolute;
		margin-top: 60px;
		margin-left: -280px;
	}
	input[name='prenomtuteur']{
	margin-top: 100px;
	margin-left: -280px;
	border-radius: 5px;
	position: absolute;
	}
	label[for="teleleve"]{
		position: absolute;
		margin-top: 60px;
		margin-left: -110px;
	}
	input[name='teleleve']{
	margin-top: 100px;
	margin-left: -110px;
	border-radius: 5px;
	position: absolute;
	}
	label[for="teltuteur"]{
		position: absolute;
		margin-top: 140px;
		margin-left: -706px;
	}
	input[name='teltuteur']{
	margin-top: 165px;
	margin-left: -706px;
	border-radius: 5px;
	position: absolute;
	}
	label[for="email"]{
		position: absolute;
		margin-top: 140px;
		margin-left: -532px;
	}
	input[name='email']{
	margin-top: 165px;
	margin-left: -532px;
	border-radius: 5px;
	position: absolute;
	}
	label[for="categorie"]{
		position: absolute;
		margin-top: 140px;
		margin-left: -352px;
	}
	select[name='categorie']{
	margin-top: 165px;
	margin-left: -352px;
	border-radius: 5px;
	position: absolute;
	}
	label[for="formation"]{
		position: absolute;
		margin-top: 140px;
		margin-left: -128px;
	}
	select[name='formation']{
	margin-top: 165px;
	margin-left: -128px;
	border-radius: 5px;
	position: absolute;
	}
	label[for="session"]{
		position: absolute;
		margin-top: 200px;
		margin-left: -706px;
	}
	select[name='session']{
	margin-top: 225px;
	margin-left: -706px;
	border-radius: 5px;
	position: absolute;
	width: 58px;
	}
	label[for="etude"]{
		position: absolute;
		margin-top: 200px;
		margin-left: -630px;
	}
	select[name='etude']{
	margin-top: 225px;
	margin-left: -630px;
	border-radius: 5px;
	position: absolute;
	width: 154px;
	}
	label[for="preference"]{
		position: absolute;
		margin-top: 200px;
		margin-left: -452px;
	}
	select[name='preference']{
	margin-top: 225px;
	margin-left: -452px;
	border-radius: 5px;
	position: absolute;
	width: 154px;
	}
	label[for="instrument"]{
		position: absolute;
		margin-top: 258px;
		margin-left: -706px;
	}
	input[name='instrument']{
	margin-top: 300px;
	margin-left: -706px;
	border-radius: 5px;
	position: absolute;
	}
	label[for="somme"]{
		position: absolute;
		margin-top: 200px;
		margin-left: -280px;
	}
	input[name='somme']{
	margin-top: 225px;
	margin-left: -280px;
	border-radius: 5px;
	position: absolute;
	}
	label[for="date"]{
		position: absolute;
		margin-top: 200px;
		margin-left: -110px;
	}
	input[name='date']{
	margin-top: 225px;
	margin-left: -110px;
	border-radius: 5px;
	position: absolute;
	width: 151px;
	}
	input[type='reset']{
		margin-left: 550px;
	}
	input[value='ENREGISTRER']{
		margin-left: 50px;
	}
	#ajout{
		margin-left: 15px;
	}
	</style>
</head>
<body>
<form method="POST" action="" accept-charset="utf-8">
	<div id="connex"><div class="img"></div><span class="out">Login: <?=$userinfo['login'];?></span><br><br><span class="out">Type: <?= $_SESSION['habilitation']; ?></span><br><input type="submit" name="acceuil" value="Acceuil" class="in"></input><br><input type="submit" name="deconnexion" value="Se déconnecter" class="in"></input><input type="submit" name="modifier" value="Modifier mon profil"><div id='ajout'><input type="submit" name="ajouter" value="Ajouter un élève" id="in" disabled></div></input></input><?php if($getpriv==$habilitation[0] AND $getpriv==$_SESSION['habilitation']){ ?><input type="submit" name="inscrire" value="Ajouter un employé" class="in"></input><input type="submit" name="supprimer" value="Supprimer un employé" class="in"></input></span><?php }elseif($getpriv==$habilitation[1] AND $getpriv==$_SESSION['habilitation']){ ?>
	<input type="submit" name="inscrire" value="Ajouter un employé" class="in" disabled="true"></input><input type="submit" name="supprimer" value="Supprimer un employé" class="in" disabled="true"></input></span><?php } ?>
	</div>
	<div class="entete"><div id="rev">Version 1.0</div><div class="titre">TOUS EN MUSIQUE</div>
		<div id="st">Fiche d'enregistrement des élèves</div>
		<div class="logo"><img src="css/img//P1.PNG" width="150px" height="90px;" style="border-radius: 10px;"></div>
		<div id="dt">
			<div id="g">Fiche d'enregistrement</div>
			<div id="d"><?= date('j').' '.$tableau_date[date('m')]." ".date('Y'); ?></div>
		</div>
				<label for="nom">Nom*</label><input type="text" name="nom" id="nom" value="<?php if(isset($_POST['nom'])){echo $_POST['nom'];}?>"><label for="prenom">Prenom*</label><input type="text" name="prenom" id="prenom" value="<?php if(isset($_POST['prenom'])){echo $_POST['prenom'];}?>"><label for="ville">ville de résidence*</label><input type="text" name="ville" id="ville" value="<?php if(isset($_POST['ville'])){echo $_POST['ville'];}?>">
				<label for="age">Age*</label><input type="text" name="age" id="age" placeholder="ex:18" value="<?php if(isset($_POST['age'])){echo $_POST['age'];}?>">
				<label for="profession">Vous êtes élève du*</label>
				<select name="profession" value="<?php if(isset($_POST['profession'])){echo $_POST['profession'];}?>">
					<option value="Primaire" id="profession">Pimaire</option>
					<option value="College, Lycee">College, Lycee</option>
					<option value="Etudiant">Etudiant</option>
					<option value="Autres">Autres</option>
				</select>
				<label for="etablissement">Etablissement <br>fréquenté (élève)</label><input type="text" name="etablissement" id="etablissement" value="<?php if(isset($_POST['etablissement'])){echo $_POST['etablissement'];}?>">
					<label for="classe">Classe</label><input name="classe" id="classe" value="<?php if(isset($_POST['classe'])){echo $_POST['classe'];}?>">
					<label for="nomtuteur">Nom tuteur*</label><input type="text" name="nomtuteur" id="nomtuteur" value="<?php if(isset($_POST['nomtuteur'])){echo $_POST['nomtuteur'];}?>"></input>
					<label for="prenomtuteur">Prénom tuteur*</label><input type="text" name="prenomtuteur" id="prenomtuteur" value="<?php if(isset($_POST['prenomtuteur'])){echo $_POST['prenomtuteur'];}?>"></input>
					<label for="teleleve">Tel élève*</label><input type="text" name="teleleve" id="teleleve" placeholder="ex: 698158192" value="<?php if(isset($_POST['teleleve'])){echo $_POST['teleleve'];}?>"></input>
									
					<label for="teltuteur">Tel tuteur*</label><input type="text" name="teltuteur" id="teltuteur" value="<?php if(isset($_POST['teltuteur'])){echo $_POST['teltuteur'];}?>"></input>
					<label for="email">Email*</label><input type="text" name="email" id="email" value="<?php if(isset($_POST['email'])){echo $_POST['email'];}?>"></input>
					<label for="categorie">Catégorie*</label>
					<select name="categorie" id="categorie">
					<option value="Enfant">Enfant</option>
					<option value="Jeune">Jeune</option>
					<option value="Adulte">Adulte</option>
					</select>
					<label for="formation">Type de formation*</label>
					<select name="formation" id="formation">
					<option value="Individuelle">Individuelle</option>
					<option value="En groupe">En groupe</option>
					<option value="A domicile">A domicile</option>
					</select>
					<label for="session">Session*</label>
					<select name="session" id="session">
					<option value="Juin">Juin</option>
					<option value="Juillet">Juillet</option>
					<option value="Aout">Aout</option>
					</select>
					<label for="etude">Etude shouaitée*</label>
					<select name="etude" id="etude">
					<option value="Flute">Flute</option>
					<option value="Guitare">Guitare</option>
					<option value="Piano">Piano</option>
					<option value="Danse">Danse</option>
					<option value="Peinture">Peinture</option>
					<option value="Chant">Chant</option>
					<option value="Eveil Musical">Eveil Musical</option>
					<option value="Violon">Violon</option>
					<option value="Batterie">Batterie</option>
					<option value="Tout">Tout</option>
					</select>
					<label for="preference">Préférences horaires*</label>
					<select name="preference" id="preference">
					<option value="Matin 08h00 - 12h30">Matin 08h00 - 12h30</option>
					<option value="Apres midi 13h30 - 17h30">Apres midi 13h30 - 17h30</option>
					<option value="Soir 18h30 - 20h30">Soir 18h30 - 20h30</option>
					</select>
					<label for="somme">Somme à payer* :</label>
					<input type="text" name="somme" id="somme" placeholder="ex: 90000" value="<?php if(isset($_POST['somme'])){echo $_POST['somme'];}?>"></input>
					<label for="date">Date du jour*</label>
					<input type="date" name="date" id="date" placeholder="AAAA-MM-JJ" value="<?php if(isset($_POST['date'])){echo $_POST['date'];}?>"></input>
					<label for="instrument">Accès régulier à un instrument?<br> Si oui lequel? :</label><input type="text" name="instrument" id="instrument" value="<?php if(isset($_POST['instrument'])){echo $_POST['instrument'];}?>"></input>
					<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
					<input type="reset" name="reset" value="EFFACER"></input>
					<input type="submit" name="submit" value="ENREGISTRER"></input>
				
		</table>
		<?php 
		if(!empty($msg)){ 
			echo "<script type='text/javascript'>alert('".$msg."');</script>";
			}
		if(isset($_GET['mp'])){
			if($_GET['mp']==1){
				echo "<script type='text/javascript'>alert('Votre profil a bien été modifié');</script>";
			}else{
				echo "<script type='text/javascript'>alert('Les modifications ont bien été ajoutées');</script>";
			}		
		}
		if(isset($_GET['log']) AND isset($_GET['erreur'])){
					if($_GET['erreur']==2){
						echo "<script type='text/javascript'>alert('L\'utilisateur ".$_GET['log']." a bien été ajouté');</script>";
					}else{
						echo "<script type='text/javascript'>alert('L\'utilisateur ".$_GET['log']." n'a pas pu être ajouté pour des raisons inconnues');</script>";
					}
				}	
		?>
	</div>
	<div id="pied_de_page">Développée par ANOUMEDEM NGUEFACK GILLES CEDRIC; élève en classe de Terminale TI au LBY; Tel: 698-15-81-92; Email: nguefackgilles@gmail.com</div>
</body>
</html>
<?php
}else{
	header("location: http://localhost/Logitem/connexion.php");
}
?>