<?php
require "fonctions.php";
    print_r($_POST);
/* Ajout Catégorie */

    if(isset($_POST["NewCat"]) AND strlen($_POST["NewCat"]) !=0 ){
        if ($connexion = mysqli_connect(HOST, USER, PASS, NOM_DB)) {

            $sql = "INSERT INTO Categories SET 
                    Libelle='".$_POST["NewCat"]."', 
                    Date_Creation = Now()";
            echo $sql;
            if ($results = mysqli_query($connexion, $sql)) {
                $error = false;
                $message = "Catégorie: ".$_POST["NewCat"]." ajoutée";
            }else{
                $error = true;
                $message = "Erreur d'ajout";
            }
        }else{
            $error = true;
            $message = "Echec connexion DB";
        }
    }
    echo "<br>".$message;
    if(isset($error)){
        header("Location: admin.php?page=Categories&message=".$message);
    }

    /* Modification Catégorie */



echo "<br>".$message;
if(isset($error)){
    header("Location: admin.php?page=Categories&message=".$message);
}

?>