$(function() {
  function DateDuJour(){
      // les noms de jours / mois
      var jours = new Array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
      var mois = new Array("Jan", "Fév", "Mars", "Avril", "Mai", "Juin", "Juil", "Août",
      "Sep", "Oct", "Nov", "Déc");
      // on recupere la date
      var laDate = new Date();
      var message = jours[laDate.getDay()] + " ";   // nom du jour
      message += laDate.getDate() + " ";   // numero du jour
      message += mois[laDate.getMonth()] + " ";   // mois
      message += laDate.getFullYear()+ " ";
      //Composition de l'heure'
      var heure = laDate.getHours();
      var minutes = laDate.getMinutes();
      var secondes = laDate.getSeconds();
      if(minutes < 10)
          minutes = "0" + minutes;
      if(secondes < 10)
          secondes = "0" + secondes;
      var message2 = heure + ":" + minutes+ ":" + secondes;
      $("#datedujour").html(message+"<br>"+message2);
  }
  setInterval(DateDuJour, 1000);
});