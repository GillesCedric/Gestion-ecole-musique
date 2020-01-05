<?php
 
/////////////////////////////////////////////////
// Sauvegarde automatique de la base de donnée
// Version 1.1
//
// Licence GNU/GPL
//
// Développé par Brice Sanchez
// http://www.brice-sanchez.com/
//
// Contribution : Flashx
// http://www.siteduzeo.com/
//
// Mars 2010
/////////////////////////////////////////////////
 
 
/*
INFO : Pour plus d'efficacité,
ce script doit être exécuté au moins 1 fois par jour par un CRON (si serveur UNIX).
*/
 
 
/////////////////////////
// DEBUT - Configuration
 
// Nom de la base de donnée à enregistrer
$nom_bdd = 'eimol' ;
 
// Hôte de la base de donnée à enregistrer
$hote = 'localhost' ;
 
// Nom d'utilisateur de la base de donnée à enregistrer
$utilisateur = 'root' ;
     
// Mot de passe de la base de donnée à enregistrer
$mot2passe = '' ;
 
// Chemin absolu de l'emplacement du site
$chemin_absolu_site = dirname(__FILE__);
 
 
// Emplacement sur le FTP du répertoire des sauvegardes
$repertoire_sauvegardes = $chemin_absolu_site.'/sauvegarde_mysql/' ;
 
// Choisir entre latin1 ou utf8 ( pour plus d'infos recherchez la documentation de mysqldump )
$encodage_caracteres_mysql = 'utf8' ;
 
// Remplir le tableau si on veut sauvegarder uniquement certaines tables ( ex : $selection_tables_mysql = array('table1','table2','table5',) ; )
$selection_tables_mysql = array() ; //  
 
// Nom du fichier de sauvegarde ( ex : nomdelabasededonnee_2010-03-01_01-01-01.sql )
$nom_fichier = $nom_bdd.'_'.date('Y-m-d_H-i-s').'.sql' ;
 
// Nombre de sauvegardes à garder sur le FTP
$nbre_sauvegardes_a_garder = 5 ;
 
// FIN - Configuration
//////////////////////
 echo "$chemin_absolu_site";
 echo "<br>";
 echo "$repertoire_sauvegardes";
 echo "<br>";
 echo "$nom_fichier";
 echo "<br>";
 
///////////////////////////////////////////////////////
// DEBUT - Vérification et création dossier sauvegarde
 
if( is_dir ($repertoire_sauvegardes) === FALSE ) {

    if(mkdir ($repertoire_sauvegardes, 0700) === FALSE ) {

        exit('Impossible de créer le répertoire pour la sauvegarde de la mysql');
    }
}
 
// FIN - Vérification et création dossier sauvegarde
///////////////////////////////////////////////////////
 
 
///////////////////////////////////////////
// DEBUT - Sauvegarde de la base de donnée
 
$commande  = 'mysqldump --host='.$hote.' --user='.$utilisateur.' --password='.$mot2passe ;
$commande .= ' --skip-opt --compress --add-locks --create-options --disable-keys --quote-names --quick --extended-insert --complete-insert' ;
$commande .= ' --default-character-set='.$encodage_caracteres_mysql.' --compatible=mysql40 --result-file='.$repertoire_sauvegardes.$nom_fichier ;
$commande .= ' '.$nom_bdd ;
 
if( !empty($selection_tables_mysql) ) {
 
    $commande .= ' '.implode(' ',$selection_tables_mysql) ;
}
 echo "$commande";
// Execution de la commande de sauvegarde
system($commande) ;
if(system($commande)){
    echo "reusssi";
}else{
    echo "non";
}
// Compression au format GZIP du fichier sauvegardé
system('cd '.$repertoire_sauvegardes.'; gzip '.$nom_fichier) ;
 
// FIN - Sauvegarde de la base de donnée
////////////////////////////////////////
 
 
///////////////////////////////////
// DEBUT - Gestion des sauvegardes
 
// Initialisation des variables
$tableau_sauvegardes = array();
$numero_fichier = 1;
 
 
// Ouverture du dossier, ,
if ($repertoire_ouvert = opendir($repertoire_sauvegardes)) {
 
    // Lecture des entrées
    while($fichier_en_cours = readdir($repertoire_ouvert)) {
         
        if(is_file($repertoire_sauvegardes.$fichier_en_cours)) {
       
            // Mise en tableau des résultats
            $tableau_sauvegardes[] = array($fichier_en_cours, filectime($repertoire_sauvegardes.$fichier_en_cours)) ;
        }
    }
 
    // fermeture du dossier
    closedir($repertoire_ouvert) ; 
}
 
 
//tri du tableau sur les dates
function cmp($a,$b) {
    if ($a[1] == $b[1])
        return 0;
    return ($a[1] < $b[1]) ? 1 : -1 ;
}
 
usort($tableau_sauvegardes, 'cmp') ;
 
 
// Lecture des entrées triées par date
foreach($tableau_sauvegardes as $element) {
 
    if( $numero_fichier > $nbre_sauvegardes_a_garder ) {
     
        // Suppression des sauvegardes obsolètes
        unlink($repertoire_sauvegardes.$element[0]) ;
    }
 
    $numero_fichier++ ;
}
 
// FIN - Gestion des sauvegardes
////////////////////////////////
 
?>