<?php
require_once("_functions.php");
$bots=getBotsArray();

if(isset($_POST['act'])){
  //un formulaire a été soumis
  switch ($_POST['act']){
    case "reDownloadBotsList":
	file_put_contents("listOfBots.txt", file_get_contents("https://raw.githubusercontent.com/jeannedhack/programmingChallenges/master/morpionsFights/Master/listOfBots.txt"));
	$bots=getBotsArray();
	break;
   case "fight":
      //nettoyer les variables en entrée
      /*
      * $_POST['bot1'] et $_POST['bot2']
      * doivent exister
      *	etres numériques
      * etre compris entre 0 et le nombre de bots
      */
      $keysBots=array('bot1','bot2');
      foreach($keysBots as $botKey){
	if(!isset($_POST[$botKey])){
	  echo "une erreur est survenue"; die;
	}
	if(!is_numeric(($_POST[$botKey]))){
	  echo '<script>window.location = "http://rickrolled.fr/";</script>'; die;
	}
	if(($_POST[$botKey] < 0) OR ($_POST[$botKey] > count($bots))){
	  echo "une erreur est survenue"; die;
	}
      }
      $bot1=$_POST['bot1'];
      $bot2=$_POST['bot2'];
         
               
      //initialiser la grille
      $grille=array(
        '0-0' => '','0-1' => '','0-2' => '',
        '1-0' => '','1-1' => '','1-2' => '',
        '2-0' => '','2-1' => '','2-2' => '');
    
      $end=false;
      $playerEnCours=1;
      while($end==false){
        switch($playerEnCours){
            case  1:
                $playerURL=$bots[$bot1]['url'];
                $playerCHAR='X';
                $playerName=$bots[$bot1]['name'];
                break;
            case 2:
                $playerURL=$bots[$bot2]['url'];
                $playerCHAR='O';
                $playerName=$bots[$bot2]['name'];
                break;
                
            default:
                echo "une erreur est survenue";
                die;
        }
	$playerRep=getIAResponse($playerCHAR,$playerURL,$grille);
	//tester la validité de la réponse
	if((isset($grille[$playerRep])) && ($grille[$playerRep]=="")){
	      //reponse conforme
	      echo  "<p>".$playerName." joue en ".$playerRep." la nouvelle grille est <br/>";       
	      $grille[$playerRep]=$playerCHAR;
	      echo "<table>";
	      for($j=0;$j<3;$j++){
		  echo "<tr>";
		  for($i=0;$i<3;$i++){
		      echo "<td>".$grille[$j."-".$i]."</td>";
		  }
		  echo "</tr>";
	      }
	      echo "</table>";
	      //tester si trois caracteres allignés
	      if(
		      (($grille['0-0']==$grille['0-1'])&&($grille['0-1']==$grille['0-2'])&&($grille['0-2']!==""))
		  OR  (($grille['1-0']==$grille['1-1'])&&($grille['1-1']==$grille['1-2'])&&($grille['1-2']!==""))
		  OR  (($grille['2-0']==$grille['2-1'])&&($grille['2-1']==$grille['2-2'])&&($grille['2-2']!==""))
		  OR  (($grille['0-0']==$grille['1-0'])&&($grille['1-0']==$grille['2-0'])&&($grille['2-0']!==""))
		  OR  (($grille['0-1']==$grille['1-1'])&&($grille['1-1']==$grille['2-1'])&&($grille['2-1']!==""))
		  OR  (($grille['0-2']==$grille['1-2'])&&($grille['1-2']==$grille['2-2'])&&($grille['2-2']!==""))
		  OR  (($grille['0-0']==$grille['1-1'])&&($grille['1-1']==$grille['2-2'])&&($grille['2-2']!==""))
		  OR  (($grille['0-2']==$grille['1-1'])&&($grille['1-1']==$grille['2-0'])&&($grille['2-0']!==""))
	      ){
		  echo "<p>".$playerName." ".$playerCHAR." a gagné.</p>";
		  $end=true;
		  break;
	      }
	      //tester si toutes les cases ne seraient pas prises
	      $full=true;	      
	      foreach($grille as $char){
		  if($char==""){
		      $full=false;
		      break;
		  }
	      }
	      if($full){
		  echo "<p>Match nul</p>";
		  $end=true;
		  break;
	      }
	      
	      //on change de joueur
	      if($playerEnCours==1){
		  $playerEnCours=2;
	      }else{
		  $playerEnCours=1;
	      }
	}else{
	      echo "<p>".$playerName." a fait une réponse non conforme. Il perd</p>";
	      break;
	
	}
    }
     
        die;
	break;
    default:
	break;
  }
}else{
  $bot1=0;
  $bot2=0;
}
?><!DOCTYPE html><html lang="fr"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="author" content="Gnieark"><meta name="viewport" content="width=device-width, initial-scale=1.0">	
<title>Arbitre Morpion</title>
<style type="text/css">
<!-- @font-face {font-family: 'Special Elite';font-style: normal; font-weight: 400; src: local('Special Elite'), local('SpecialElite-Regular'), url(http://themes.googleusercontent.com/static/fonts/specialelite/v4/9-wW4zu3WNoD5Fjka35Jm_n8qdNnd5eCmWXua5W-n7c.woff) format('woff');}
body{width:100%;font-size:100%; top: 0px;line-height:140%;word-wrap:break-word;text-rendering:optimizelegibility;margin:0 auto;font-family : "lucida grande", "gill sans", arial, sans-serif;}
header{background-color:#502FD2; width: 100%; overflow: hidden;height: auto;}
header h1{display: block; font-size:300%; color:#FFF;float: left; width: 45%;font-family: 'Special Elite', cursive;margin-left: 5%;}
header p{display: block; width: 45%;color:#FFF; float: left;}
section {border-bottom: 1px solid rgb(204, 204, 204); margin: 0 auto;overflow: hidden;width: 90%;}
-->
</style>
<script type="text/javascript">
<!--
  function Ajx(){
      var request = false;
	  try {request = new ActiveXObject('Msxml2.XMLHTTP');}
	  catch (err2) {
		  try {request = new ActiveXObject('Microsoft.XMLHTTP');}
		  catch (err3) {
			  try { request = new XMLHttpRequest();}
			  catch (err1) {
				  request = false;
			  }
		  }
	  }
      return request;
  }
  function tictactoe(bot1,bot2){
    document.getElementById('fightResult').innerHTML = '<p>Please wait...</p>';
    var xhr = Ajx(); 
    xhr.onreadystatechange  = function(){if(xhr.readyState  == 4){ 
	if(xhr.status  == 200) {
	  document.getElementById('fightResult').innerHTML = xhr.responseText;				
	}
     }};
     xhr.open("POST", 'index.php',  true);
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
     xhr.send('act=fight&bot1=' + bot1 + '&bot2=' + bot2);
   }

  function refreshBots(){
    var xhr = Ajx(); 
    xhr.onreadystatechange  = function(){if(xhr.readyState  == 4){ 
	if(xhr.status  == 200) {
	  window.location.reload();		
	}
     }};
     xhr.open("POST", 'index.php',  true);
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
     xhr.send('act=reDownloadBotsList');
   }
  
-->
</script>
</head>
<body>
 <header><h1>Arène à morpions</h1></header>
  <section>
    <article>
      <p>Bienvenue sur notre Arène pour pseudos intelligences artificielles de morpions.</p>
      <p>L'explication sur quoi que c'est ce site est sur la page <a href="https://github.com/jeannedhack/programmingChallenges/tree/master/morpionsFights">challenge de programmation Morpion</a>.</p>
      <p>Pour ajouter un bot, ajoutez une ligne contenant votre pseudo et l'url dans le fichier 
      <a href="https://github.com/jeannedhack/programmingChallenges/blob/master/morpionsFights/Master/listOfBots.txt">listOfBots.txt</a> et cliquez sur <a href="#" onclick="refreshBots();"> Raffraichir la liste des bots</a>.</p>
    </article>
    <article>
    <h2>Lancer un combat</h2>
		<p>
			<select name="bot1" id="bot1">
			  <?php
			    for($i=0;$i<count($bots);$i++){
			      if($i==$bot1)
				$selected='selected="selected"';
			      else
				$selected='';
			      
			      echo '<option value="'.$i.'" '.$selected.'>'.$bots[$i]['name'].'</option>';
			    }
			  ?>
			</select>
			&nbsp;VS&nbsp;
			<select name="bot2" id="bot2">
			  <?php
			    for($i=0;$i<count($bots);$i++){
			      if($i==$bot2)
				$selected='selected="selected"';
			      else
				$selected='';
			      echo '<option value="'.$i.'" '.$selected.'>'.$bots[$i]['name'].'</option>';
			    }
			  ?>
			</select>
		</p>
	<p><input type="button" value="Fight!" onclick="tictactoe(document.getElementById('bot1').value,document.getElementById('bot2').value);"></p>
    </article>
    <article id="fightResult"></article>
	
</section>
</body>
</html>
