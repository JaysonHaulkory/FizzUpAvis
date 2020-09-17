<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<style>

	   body{
	   	       Font-family:"Lucida Sans Unicode", "Lucida Grande", sans-serif;
        }
        
        #formAvis{
        text-align:center;
        background-image: linear-gradient(to bottom right, #97C5D5, white);
        width:800px;
        margin:auto;
        border-radius: 2px;
       }
       
	   .contenerAvis{
	       border-radius:10px 10px 0px 0px;
	       width:800px;
	       margin:auto;
	       background-image: linear-gradient(#818E99, white);
	       height:auto;
	   }
       
	   .contenerAvis p{
	       padding: 10px;
	       height:25px;
	       border-radius:10px 10px 0px 0px;
	       background-color:#6c757d;
	   }
	   .contenerAvis div{
	       padding: 0px 10px 10px 10px ;
	   }
	   .contenerAvis img{
	       max-width: 600px;
	   }

	   .noteEtoile{
	       font-size:35px;
	       color:#DFBB14;

	    }

	       input[type=submit]{
        	background-color: #4CAF50;
        }

        input[type=reset] {
        	background-color: #ff3333;
        }
        input[type=reset],[type=submit] {
        	background-color: #ff3333;
        	border: none;
        	color: white;
        	padding: 16px 32px;
        	text-decoration: none;
        	margin: 4px 2px 20px 0px;
        	cursor: pointer;
        	border-radius:2px;
        }

        input[type=email], [type=text] {
        	width: 200px;
        	padding: 10px 15px;
        	margin: 8px 0;
        	box-sizing: border-box;
        }
        
        #file_size_error{
            background-color:#ff3333;
            color:white;
            height:50px;
            width:500px;
            padding:5px;
            border-line:solid black;
            text-align:center;
            border-radius: 4px;
            margin:auto;
        }
        textarea { resize: none; }
        #form_commentaire {
            width:600px;
            height:300px;
        }
        #titrelisteAvis{
            text-align:center;
            margin:20px;
        }
	</style>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<title>FizzUp Form HAULKORY JAYSON</title>
	</head>
	<body>
		<?php

            require 'connexion.php';
            $errorMSg = false;
            if (isset($_POST['submit'])){
                $requete = 'INSERT INTO avis (pseudo,mail,note,commentaire,img_path) VALUES(?,?,?,?,?)';
                if (($_FILES['file']['error'] == 0 and $_FILES['file']['size'] < 2097152 and (strpos($_FILES['file']['type'], 'image') !== false)) || $_FILES['file']['error'] == 4) {
                    $bdd->prepare($requete)->execute([$_POST['pseudo'],$_POST['email'],$_POST['etoiles'],(isset($_POST['commentaire']) ?  $_POST['commentaire'] : null ),(isset($_FILES["file"]["name"]) ?  $_FILES["file"]["name"] : null )]);
                    move_uploaded_file($_FILES["file"]["tmp_name"],  "uploaded/" .$_FILES["file"]["name"]);
                }
                else {
                    $errorMSg = true;
                }
                
            }
        ?>
        
		<div id='formAvis'>
		<p>Donnez votre avis !</p>
			<form method='POST' enctype="multipart/form-data">
                <input type="text" placeholder="Pseudo" name="pseudo" id="form_pseudo" required></br>

                <input type="email" placeholder="Adresse Mail" name="email" id="form_email" required></br>

                <textarea maxlength="800" name="commentaire" rows="15" cols="50" placeholder="Commentaire (facultatif) 800 Caractères max." id="form_commentaire"></textarea></br>

                <label for="email">Note: </label>
               	<div id="note">
               	<?php for ($i=1;$i<6;$i++){
               	    echo '<input type="radio" id="rb_' . $i . '" name="etoiles" value="' . $i . '" required>
                          <label for=' . $i . '>' . $i . '★</label> ';
               	}
               	    ?>
               	</div>
                </br>
                <?php echo ($errorMSg ? "<div id='file_size_error'>Le ficher que vous tentez d'envoyer est trop lourd (2MB Max) ou le fichier que vous tentez d'envoyer n'est pas une image.</div> " : null);?>
                <input type="file" name="file" accept=".png, .jpg, .jpeg" id="form_file"></br></br>
				
				<input type="submit" name="submit" value="Envoyer">
				<input type="reset" value="Annuler">
			</form>
		</div>



		<div id='listeAvis'>
		<div id='titrelisteAvis'>Trier les avis par notes <a href="?tri=croissant">croissantes</a>, <a href="?tri=decroissant">décroissante</a> ou par date <a href="?tri=recent">le plus récent</a>, <a href="?tri=ancient">le plus ancient</a>.</div>
			<?php 
			$requeteTri = 'SELECT * FROM avis ORDER BY dt DESC;';
			if (isset($_GET['tri'])) {
			    switch ($_GET['tri']) {
			        case 'croissant':
			            $requeteTri = 'SELECT * FROM avis ORDER BY note,dt ASC;';
			            break;
			        case 'decroissant':
			            $requeteTri = 'SELECT * FROM avis ORDER BY note DESC,dt ASC;';
			            break;
			        case 'ancient':
			            $requeteTri = 'SELECT * FROM avis ORDER BY dt ASC;';
			            break;
			    }
			}
			$reponse = $bdd->query($requeteTri);

			while ($donnees = $reponse->fetch())
			{
			?>
			   <div class='contenerAvis'>
			   		<p>Avis de <?php echo $donnees['pseudo'];?></p> 
			   		<div>
			   		<?php if (!(empty($donnees['commentaire']))) {
			   		    echo $donnees['commentaire'];
			   		}?>
			   		</br>
			   		<?php if (!(empty($donnees['img_path']))) {
			   		    echo '<img src="uploaded/' . $donnees['img_path'] . '"></img>';
			   		}?>
			   		
			   		</br>
			   		<div class='noteEtoile'><?php for ($i=0;$i<$donnees['note'];$i++){ echo " ★";}?></div>
			   		</br>
			   		<?php echo $donnees['dt'];?>
			   		</div>
			   </div>
            <?php }

            $reponse->closeCursor();

            ?>
		</div>

		<?php $bdd = null;?>
	</body>
</html>
