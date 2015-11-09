<?php
if((isset($_POST['ia1URL'])) OR (isset($_POST['ia1URL']))){
        if((filter_var($_POST['ia1URL'], FILTER_VALIDATE_URL))===false){
                echo "erreur"; die;
        }
        if((filter_var($_POST['ia2URL'], FILTER_VALIDATE_URL))===false){
         echo "erreur"; die;
        }
 if(!preg_match("/^(http|https):\/\//", $_POST['ia1URL'])) 
	{echo "erreur"; die;} 
  if(!preg_match("/^(http|https):\/\//", $_POST['ia2URL']))     
        {echo "erreur"; die;} 
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

</head>
<body>
 <header><h1>Arène à morpions</h1></header>
  <section>
	<form method="POST" action="#">
		<p>
			<input type="text" placeholder="URL de la premiere IA" name="ia1URL"<?php if(isset($_POST['ia1URL'])){echo "value=\"".$_POST['ia1URL']."\"";} ?>/>
			<input type="text" placeholder="X ou O" name="youIA1" <?php if(isset($_POST['youIA1'])){echo "value=\"".$_POST['youIA1']."\"";} ?>/>
		</p>
		<p>
			<input type="text" placeholder="URL de la deuxième IA" name="ia2URL" <?php if(isset($_POST['ia2URL'])){echo "value=\"".$_POST['ia2URL']."\"";} ?>/>
			<input type="text" placeholder="X ou O" name="youIA2" <?php if(isset($_POST['youIA2'])){echo "value=\"".$_POST['youIA2']."\"";} ?>/>

		</p>
	<p><input type="submit" value="Fight!" name="submit">
	</form>	
<pre>
<?php
if(isset($_POST['submit'])){
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
                $playerURL=$_POST['ia1URL'];
                $playerCHAR=$_POST['youIA1'];
                break;
            case 2:
                $playerURL=$_POST['ia2URL'];
                $playerCHAR=$_POST['youIA2'];
                break;
                
            default:
                echo "une erreur est survenue";
                die;
        }
       $playerRep=getIAResponse($playerCHAR,$playerURL,$grille);
      	echo "Reponse: ".$playerRep."\n"; 
	//tester la validité de la réponse
       if((isset($grille[$playerRep])) && ($grille[$playerRep]=="")){
            //reponse conforme
            echo  $playerCHAR." joue en ".$playerRep." la nouvelle grille est \n";       
            $grille[$playerRep]=$playerCHAR;
            for($j=0;$j<3;$j++){
                for($i=0;$i<3;$i++){
                    echo $grille[$j."-".$i];
                    if ($grille[$j."-".$i]==""){
                        echo " ";
                    }
                }
                echo "\n";
            }
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
                echo "le joueur ".$playerCHAR." a gagné.";
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
                echo "Match nul";
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
            echo "le joueur ".$playerCHAR." a fait une réponse non conforme. Il perd";
            break;
       
       }
    }
}

function getIAResponse($youChar,$iaBaseUrl,$grille){
    $paramsGrille="";
    foreach($grille as $key => $case){
        $paramsGrille.="&".$key."=".$case;
    }
    $url=$iaBaseUrl."?you=".$youChar.$paramsGrille;
   echo "\n".$url."\n"; 
    //return http_get($url);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // $output contains the output string
     $output = curl_exec($ch);
    // close curl resource to free up system resources
    curl_close($ch);   
    return htmlentities($output);
}

?>
</pre>
</section>
</body>
</html>

