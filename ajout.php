
<?php
require "fonctions.php";

var_dump($_POST);
echo '<br><br><br><br>';
var_dump($_FILES);

if(isset($_POST["nom"]) AND $_POST["nom"] != "" AND strlen($_POST["nom"]) <100){
    $nom = $_POST["nom"];
}else{
    $error = true;
    $message = "Le nom est trop long ou vide";
}

if(isset($_POST["description"]) AND $_POST["description"] != "" ){

    $description = $_POST["description"];
    $description = str_replace("'","''",$description);
}else{
    $error = true;
    $message = "Description vide";
}

if(isset($_POST["categorie"]) AND is_numeric($_POST["categorie"])){
    $idCategorie = $_POST["categorie"];
}else{
    $error = true;
    $message = "La Catégorie est incorrecte";
}

if(isset($_POST["couleur"]) AND is_numeric($_POST["couleur"])){
    $idCouleur = $_POST["couleur"];
}else{
    $error = true;
    $message = "La Couleur est incorrecte";
}

if(isset($_POST["artistes"]) AND is_numeric($_POST["artistes"])){
    $idArtiste = $_POST["artistes"];
}else{
    $error = true;
    $message = "L'artiste' est incorrecte";
}
if(isset($_POST["genre"]) AND is_numeric($_POST["genre"])){
    $idGenre = $_POST["genre"];
}else{
    $error = true;
    $message = "Le genre est incorrecte";
}

if(isset($_POST["prix"]) AND is_numeric($_POST["prix"])){
    $prix = $_POST["prix"];
}else{
    $error = true;
    $message = "Le prix est incorrecte";
}
if(isset($_POST["taille"]) AND is_numeric($_POST["taille"])){
    $idTaille = $_POST["taille"];
}else{
    $error = true;
    $message = "La taille est incorrecte";
}
if(isset($_POST["nbstock"]) AND is_numeric($_POST["nbstock"])){
    $nbStock = $_POST["nbstock"];
}else{
    $error = true;
    $message = "Le nombre en stock est incorrecte";
}



if(!$error AND !isset($_POST["idModif"])){
    if(isset($_POST["img"])){  // Si il y a des images je crée un dossier et une liste d'image
        if($connexion = mysqli_connect(HOST,USER,PASS,NOM_DB)) {
            $sql = "INSERT INTO liste_images SET
                    Date_creation = Now();";
            if ($results = mysqli_query($connexion, $sql)) {
                $idlistImage = mysqli_insert_id($connexion);
            }else {
                $error = true;
                $message = "Erreur requête LISTE IMAGE";
            }
            mysqli_free_result($results);

            /***** IMAGES *****/
            $nom_dossier = str_replace(" ","_",$nom);
            rename("assets/upload/","assets/".$nom_dossier."/");
            if(!file_exists("assets/upload/")){
                mkdir("assets/upload/");
            }
            $liste_image = liste_repertoire("assets/".$nom_dossier."/");
            foreach ($liste_image as $key => $val){
                    $sql = "INSERT INTO Image SET
                    lien_image = '".$val['chemin_image']."',
                    lien_miniature = '".$val['chemin_miniature']."',
                    AltImage = '".$val['altFichier']."',
                    Date_Creation = Now(),
                    ID_Liste_Image ='".$idlistImage."';";

                if ($results = mysqli_query($connexion, $sql)) {
                    $errar =false;
                    $message = "Image ajoutée";
                }else {
                    $error = true;
                    $message = "Erreur requête IMAGE";
                }
                mysqli_free_result($results);
            }
            mysqli_close($connexion);
        }else{
            $error = true;
            $message = "Echec connexion DB";
        }
    }else{
        $idlistImage = null; // Sinon l'id liste image est nulle
    }
        // J'ajoute chaque T_shirt avec ces caratéristiques en fonction de sa taille
        foreach ($_POST["tab"] as $key => $val) {
            $sql = "INSERT INTO T_Shirt SET
            Name = '".$nom."',
            Description = '".$description."',
            Date_creation = Now(),
            ID_Catégorie =".$idCategorie.",
            ID_Couleur = ".$idCouleur.",
            ID_Artiste = ".$idArtiste.",
            ID_Sexe = ".$idGenre.",
            ID_liste_img = ".$idlistImage.",
             Prix = ".$prix.",
           ";
                $idTaille = $key;
                $nbStock = $val;
                $sql = $sql." NB_stock=".$val;
                $sql = $sql.", ID_Taille=".$key;

                if($connexion = mysqli_connect(HOST,USER,PASS,NOM_DB)) {
                    if ($results = mysqli_query($connexion, $sql)) {
                        $idTshirtAdd[] = mysqli_insert_id($connexion);
                        $message= "TShirt Ajouté";
                        $error = false;
                    }else {
                        $error = true;
                        $message = "Erreur requête T_SHirt";
                    }
                    mysqli_free_result($results);
                    mysqli_close($connexion);
                }else{
                    $error = true;
                    $message = "Echec connexion DB";
                }
            }
    header('Location: Ajout_TShirt.php?error='.$error.'&message='.$message);
}elseif(!$error AND isset($_POST["idModif"])){ // Si il n'y a pas d'erreur dans mes valeurs pour ma DB et que je suis en modification
    print_r($_POST);
    if(isset($_POST["imgListe"])){ // Je vérifie qu'il y a une liste d'images
        $idlistImage = $_POST["imgListe"]; // J'attribue la liste d'image
        }else{
        if($connexion = mysqli_connect(HOST,USER,PASS,NOM_DB)) {
            $sql = "INSERT INTO liste_images SET
                    Date_creation = Now();"; // Sinon j'en crée une nouvelle et je crée le dossier et je range les images dedans (déplacement depuis upload)
            if ($results = mysqli_query($connexion, $sql)) {
                $idlistImage = mysqli_insert_id($connexion);
                $error = false;
                $message = "Liste d'images Ajoutée";
            }else {
                $error = true;
                $message = "Erreur requête LISTE IMAGE";
            }
            mysqli_free_result($results);
            mysqli_close($connexion);
            /* CREATION DU DOSSIER */
            $nom_dossier = str_replace(" ","_",$nom);
            if(!file_exists("assets/".$nom_dossier."/")){
                mkdir("assets/".$nom_dossier."/");
            }

            if(!file_exists("assets/".$nom_dossier."/miniature/")){
                mkdir("assets/".$nom_dossier."/miniature/");
            }
        }else{
            $error = true;
            $message = "Erreur Connexion";
        }
    }

    // Je sauvegarde mes images en DB en parcourant le dossier de meme nom que le t-shirt

    if($connexion = mysqli_connect(HOST,USER,PASS,NOM_DB)) {
            /***** IMAGES *****/
            $nom_dossier = str_replace(" ","_",$nom);

            rename("assets/upload/","assets/uploadTempo/");
            $liste_image = liste_repertoire("assets/uploadTempo/");


            echo "<br> La liste image: ";
            print_r($liste_image);

            foreach ($liste_image as $val){
                $NewCheminImg = str_replace("assets/uploadTempo/","assets/".$nom_dossier."/",$val['chemin_image']);
                $NewCheminMiniature = str_replace("assets/uploadTempo/","assets/".$nom_dossier."/",$val['chemin_miniature']);

                $sql = "INSERT INTO Image SET
                    lien_image = '".$NewCheminImg."',
                    lien_miniature = '".$NewCheminMiniature."',
                    AltImage = '".$val['altFichier']."',
                    Date_Creation = Now(),
                    ID_Liste_Image =".$idlistImage.";";

                echo "<br> La requete est: ".$sql;
                echo "<br> Le chemin miniature est: ".$NewCheminMiniature;
                echo "<br> Le chemin ancien miniature est: ".$val['chemin_miniature'];
                $fileMoved2 = rename($val['chemin_miniature'],$NewCheminMiniature);
                echo "<br> Le chemin NEW miniature est: ".$val['chemin_miniature'];
                $fileMoved = rename($val['chemin_image'], $NewCheminImg);
                if($fileMoved AND $fileMoved2){
                    if ($results = mysqli_query($connexion, $sql)) {
                        $error =false;
                        $message = "Image ajoutée";
                    }else {
                        $error = true;
                        $message = "Erreur requête IMAGE";
                    }
                    mysqli_free_result($results);
                }
            }
        rename("assets/uploadTempo/","assets/upload/");
            mysqli_close($connexion);
        }else{
            $error = true;
            $message = "Echec connexion DB";
        }
    header('Location: Ajout_TShirt.php?error='.$error.'&message='.$message);

    if($connexion = mysqli_connect(HOST,USER,PASS,NOM_DB)) {
        /* MODIFICATION T_SHIRT */
        $sql_Update_TShirt = "UPDATE T_Shirt SET
            T_Shirt.Name = " . $nom . ",
            T_Shirt.Prix = " . $prix . ",
            T_Shirt.Description = " . $description . ",
            T_Shirt.NB_stock = " . $nbStock . ",
            T_Shirt.ID_Taille = " . $idTaille . ",
            T_Shirt.ID_Couleur = " . $idCouleur . ",
            T_Shirt.ID_Sexe = " . $idGenre . ",
            T_Shirt.ID_Catégorie = " . $idCategorie . ",
            T_Shirt.ID_Artiste = " . $idArtiste . " 
            WHERE T_Shirt.ID = ;";
        if ($results = mysqli_query($connexion, $sql_Update_TShirt)) {
            $error = false;
            $message = "TShirt Modifié";
        } else {
            $error = true;
            $message = "Erreur requête TShirt Modifié";
        }
        mysqli_free_result($results);
        mysqli_close($connexion);
    }else{
        $error = true;
        $message = "Erreur Connexion DB";
    }
}


if(isset($_FILES["tshirt"])){
    upload_image('assets/upload/',array('.png', '.gif', '.jpg', '.jpeg'),"tshirt",100000);
    $liste_image = liste_repertoire('assets/upload/');
    $liste_image = serialize ( $liste_image);

    if(isset($_POST["idModif"])){
        header('Location: Ajout_TShirt.php?tshirtListeMin='.$liste_image."&ModifTshirt=".$_POST["idModif"]);
    }else{
        header('Location: Ajout_TShirt.php?tshirtListeMin='.$liste_image);
    }
}
 /*** REQUETE SQL IMAGE T-shirt consulté
 SELECT Image.lien_image, Image.lien_miniature, T_Shirt.Name FROM liste_images
  * JOIN T_Shirt ON T_Shirt.ID_liste_img = liste_images.ID
  * JOIN Image ON Image.ID_Liste_Image = liste_images.ID
  * WHERE T_Shirt.Name = 'Rock2'
  ***/

?>