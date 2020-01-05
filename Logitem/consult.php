<?php
session_start();
$connect=new PDO("mysql:host=localhost;dbname=EIMOL","root","");
// verification d'un utilisateur connecté
if(isset($_GET['id'])  AND !empty($_GET['id']) AND !empty($_SESSION)){
	$getid = htmlspecialchars($_GET['idu']);
	$req=$connect->prepare('SELECT * FROM eleve WHERE id=?');
	$req->execute(array($getid));
	$userinfo=$req->fetch();
	$req2=$connect->prepare('SELECT * FROM tuteur WHERE id=?');
	$req2->execute(array($getid)); 
	$userinfo2=$req2->fetch();
	$avance = $connect->prepare('SELECT SUM(somme) FROM paiement WHERE ideleve=?');
	$avance->execute(array($getid));
	$avance=$avance->fetch();
	$msg='';
if(isset($_POST['nouveau'])){
	header("location: http://localhost/Logitem/Index.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']);
}
if (isset($_POST['payer'])) {
	if(!empty($_POST['montant']) AND !empty($_POST['date'])){
		$montant=htmlspecialchars($_POST['montant']);
		$date=htmlspecialchars($_POST['date']);
		
		$todaynotif=new Datetime("$date +1 month");
		$mois=explode('-', $date);

		$insertion=$connect->prepare('INSERT INTO paiement(ideleve,somme,date,dateprochaine) VALUES (?,?,?,?)');
		$insertion->execute(array($_GET['idu'],$montant,$date,$todaynotif->format('y-m-d')));
		$deletenotif=$connect->prepare('DELETE FROM notification WHERE ideleve=? AND mois=?');
		$deletenotif->execute(array($_GET['idu'],$mois['1']));
		header("location: http://localhost/Logitem/Index.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']."&er=1");
	}else{
		$msg='Veuillez remplir tous les champs';
	}
}
$tableau_date=array('01' => 'Janvier', '02' => 'Février','03' => 'Mars','04' => 'Avril','05' => 'Mai','06' => 'Juin','07' => 'Juillet','08' => 'Août','09' => 'Septembre','10' => 'Octobre','11' => 'Novembre','12' => 'Décembre');
$time=explode('-', $userinfo['date']);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Consult</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/style2.css">
	<link rel="shortcut icon" type="images/x-icon" href="css/img/favicon.ico" />
	<style type="text/css">
		label[for="montant"]{
		position: absolute;
		margin-top: 320px;
		margin-left: 33px;
	}
	input[name='montant']{
	margin-top: 350px;
	margin-left: 32px;
	border-radius: 5px;
	position: absolute;
	}
	label[for="date"]{
		position: absolute;
		margin-top: 320px;
		margin-left: 222px;
	}
	input[name='date']{
	margin-top: 350px;
	margin-left: 220px;
	border-radius: 5px;
	position: absolute;
	}
	body{
	background-image: url("css/img/M1.JPG");
	background-size: cover;
}
@media print{
			#pied_de_page,input[type="submit"],#rev{
				display: none;
			}
		}
	</style>
</head>
<body>
	<div class="entete"><div id="rev">Version 1.0</div><div class="titre">TOUS EN MUSIQUE</div>
		<div id="st">Fiche de consultation</div>
		<div class="logo"><img src="css/img//P1.PNG" width="150px" height="90px;" style="border-radius: 10px;"></div>
		<div id="dt">
			<div id="g">Reçu d'enregistrement</div>
			<div id="d"><?= $time['2'].' '.$tableau_date[$time['1']].' '.$time['0'] ?></div>
		</div>
		<form method="POST" action="" accept-charset="utf-8" style="margin-top: 20px;">
			<table border="0" style="margin-left: 10px" width="900px">
				<tr>
					<td><label for="nom">Nom :</label></td><td><?= $userinfo['nom']; ?></td>
					<td><label for="prenom">Prenom :</label></td><td><?= $userinfo['prenom']; ?></td>
					<td><label for="tel">Ville :</label></td><td><?= $userinfo['ville']; ?></td>
				</tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr>
					<td><label for="machine">élève du :</label></td><td><?= $userinfo['profession']; ?></td>
				<?php
				if($userinfo['profession']=='Autres'){
				?>
					<td><label for="model">Age :</label></td><td><?= $userinfo['age']; ?></td>
				<?php
				}else{
				?>	
					<td><label for="model">Age :</label></td><td><?= $userinfo['age']; ?></td>
					<?php
					if(!empty($userinfo['etablissement'])){
					?>
					<td><label for="model">Etablissement :</label></td><td><?= $userinfo['etablissement']; ?></td>
					<?php
					}
					if(!empty($userinfo['classe'])){
					?>	
					<td><label for="panne">Classe :</label><?= $userinfo['classe']; ?></td>
					<?php
					}
					?>	
				<?php
				}
				?>
				</tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr>
					<td><label for="et">Nom du tuteur :</label></td><td><?= $userinfo2['nom']; ?></td>
					<td align="left"><label for="et">prénom du tuteur :</label></td><td><?= $userinfo2['prenom']; ?></td>
					<td><label for="et">Email :</label></td><td><?= $userinfo2['email']; ?></td>
				</tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr>
					<td><label for="dateretrait">Tel élève :</label></td><td><?= $userinfo['tel']; ?></td>
					<td><label for="dateretrait">Tel tuteur :</label></td><td><?= $userinfo2['teltuteur']; ?></td>
					<td><label for="receptioniste">Réceptioniste :</label></td><td><?= $userinfo['receptioniste']; ?></td> 
				</tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr>
					<td><label for="dateretrait">Catégorie :</label></td><td><?= $userinfo['categorie']; ?></td>
					<td><label for="dateretrait">Formation :</label></td><td><?= $userinfo['formation']; ?></td>
					<td><label for="receptioniste">Session :</label></td><td><?= $userinfo['session']; ?></td> 
				</tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr>
					<td><label for="dateretrait">Etude :</label></td><td><?= $userinfo['etude']; ?></td>
					<td><label for="dateretrait">Préférence :</label></td><td><?= $userinfo['preference']; ?></td>
					<td><label for="receptioniste">Instrument :</label></td><td><?= $userinfo['instrument']; ?></td> 
				</tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr>
					<td><label for="dateretrait">Somme totale :</label></td><td><?=$userinfo['somme'];?></td>
					<td><label for="dateretrait">Avancé :</label></td><td><?php if ($avance['SUM(somme)']=='') { echo "0";}else{ echo $avance['SUM(somme)'];}?></td>
					<td><label for="receptioniste">Reste :</label></td><td><?= $userinfo['somme']-$avance['SUM(somme)']; ?></td> 
				</tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<?php if(isset($_GET['mode']) AND $_GET['mode']== 'payer'){ ?>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>

				<?php } ?>
				<tr>
					<td><label for="dateretrait">Avatar :</label></td><td><img src="eleves/avatars/<?=$userinfo['avatar'];?>"style='border-radius: 50%;width:75px;height:75px; '/></td> 
				</tr>
				<?php if(isset($_GET['mode']) AND $_GET['mode']== 'payer'){ ?>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
					<label for="montant">Montant :</label><input type="text" name="montant" id="montant">
					<label for="date">Date :</label><input type="date" name="date" id="date" placeholder="AAAA-MM-JJ"> 
				
				<?php } ?>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr><br>
				<tr></tr><tr></tr>
				<tr></tr><tr></tr>
				<tr></tr><tr></tr>
				<tr></tr><tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr><br>
				<tr></tr><tr></tr>
				<tr></tr><tr></tr>
				<tr></tr><tr></tr>
				<tr></tr><tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr>
				<tr></tr><br>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<?php if(isset($_GET['mode']) AND $_GET['mode']== 'payer'){ ?>
					<td><input type="submit" name="payer" value="ENREGISTRER"></input></td></td>
					<td><input type="submit" name="nouveau" value="ACCEUIL"></input></td></td>
					<?php }else{ ?>
					<td><input type="submit" name="nouveau" value="ACCEUIL"></input></td></td>
					<td><input type="submit" name="imprimer" value="IMPRIMER" onclick="window.print();"></input></td></td><br>
					<?php } ?>
				</tr>
		</table>
		<?php 
			if(!empty($msg)){ 
			echo "<script type='text/javascript'>alert('".$msg."');</script>";
			}
			?>
		<br>
	</div>
	<div id="pied_de_page">Développée par ANOUMEDEM NGUEFACK GILLES CEDRIC; élève en classe de Terminale TI au LBY; Tel: 698-15-81-92; Email: nguefackgilles@gmail.com</div>
</body>
</html>
<?php
}else{
	header("location: http://localhost/Logitem/connexion.php");
}
?>