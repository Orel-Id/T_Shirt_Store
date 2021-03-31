<?php
require "fonctions.php";
    print_r($_POST);

    /* Ajout Catégorie */

if(isset($_POST["NewCat"]) AND strlen($_POST["NewCat"]) !=0 ){
        if ($connexion = mysqli_connect(HOST, USER, PASS, NOM_DB)) {
            $nom = str_replace(" ","",$_POST["NewCat"]);
            $sql = "SELECT Categories.Libelle, Categories.Date_Supression,Categories.ID FROM Categories
                WHERE Categories.Libelle LIKE '".$nom."'";
            if ($results = mysqli_query($connexion, $sql)) {
                if($row = mysqli_fetch_row($results)){
                    $id = $row[2];
                    if(is_null($row[1])){
                        $error = false;
                        $message = "Catégorie: ".$nom." déja listée";
                    }else{
                        mysqli_free_result($results);
                        $sql = "UPDATE Categories SET
                                Categories.Date_Supression = null    
                                 WHERE Categories.ID = ".$id.";";
                    }
                }else{
                    mysqli_free_result($results);
                    $sql = "INSERT INTO Categories SET 
                    Libelle='".$_POST["NewCat"]."', 
                    Date_Creation = Now()";
                }
            }else{
                $error = true;
                $message = "Erreur d'ajout";

                echo $message;
            }

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
        header("Location: Gestion_Categorie.php?error=".$error."&message=".$message);
        mysqli_free_result($results);
        mysqli_close($connexion);
    }

    /* Modification Catégorie */

if(isset($_POST["ModifCategorie"]) AND isset($_POST["idToEdit"]) AND is_numeric($_POST["idToEdit"])){
    $libelle = str_replace('"','""',$_POST["ModifCategorie"]);
    $libelle = str_replace("'","''",$libelle);
    $sql = "UPDATE Categories SET
            Categories.Libelle = '".$libelle."'     
            WHERE Categories.ID = ".$_POST["idToEdit"].";";

    if ($connexion = mysqli_connect(HOST, USER, PASS, NOM_DB)) {
        if ($results = mysqli_query($connexion, $sql)) {
            $error = false;
            $message = "Catégorie ".$_POST["ModifCategorie"]." Modifiée";
        }else{
            $error = true;
            $message = "Erreur d'ajout";
        }
    }else{
        $error = true;
        $message = "Echec connexion DB";
    }
    echo $message;
    mysqli_free_result($results);
    mysqli_close($connexion);
}

/* Supprimer Catégorie */

if(isset($_POST["idSuppCategorie"]) AND is_numeric($_POST["idSuppCategorie"])){
    $sql = "UPDATE Categories SET
            Categories.Date_Supression = Now()    
            WHERE Categories.ID = ".$_POST["idSuppCategorie"].";";
    if ($connexion = mysqli_connect(HOST, USER, PASS, NOM_DB)) {
        if ($results = mysqli_query($connexion, $sql)) {
            $error = false;
            $message = "Catégorie Supprimée";
        }else{
            $error = true;
            $message = "Erreur de suppression";
        }
    }else{
        $error = true;
        $message = "Echec connexion DB";
    }
    echo $message;
    mysqli_free_result($results);
    mysqli_close($connexion);
    header("Location: Gestion_Categorie.php?error=".$error."&message=".$message);
}

    /* Ajout D'artiste */

if(isset($_POST["NewArtistePrenom"]) AND strlen($_POST["NewArtistePrenom"]) !=0 ) {
    if (isset($_POST["NewArtisteNom"]) and strlen($_POST["NewArtisteNom"]) != 0) {
        if (isset($_POST["NewArtisteDescription"]) and strlen($_POST["NewArtisteDescription"]) != 0) {
            $description = str_replace("'", "''", $_POST["NewArtisteDescription"]);
            $description = str_replace('"', '""', $description);
            $nom = str_replace("'", "''", $_POST["NewArtisteNom"]);
            $nom = str_replace('"', '""', $nom);
            $prenom = str_replace("'", "''", $_POST["NewArtistePrenom"]);
            $prenom = str_replace('"', '""', $prenom);

            if ($connexion = mysqli_connect(HOST, USER, PASS, NOM_DB)) {
                $sql = "SELECT Artiste.Nom, Artiste.Prenom,Artiste.Date_Supression,Artiste.ID FROM Artiste
                WHERE Artiste.Nom LIKE '" . $nom . "' AND Artiste.Prenom LIKE '" . $prenom . "';";
                if ($results = mysqli_query($connexion, $sql)) {
                    if ($row = mysqli_fetch_row($results)) {
                        $id = $row[3];
                        if (is_null($row[2])) {
                            $error = false;
                            $message = "Artiste: " . $nom . " déja listée";
                        } else {
                            mysqli_free_result($results);
                            $sql = "UPDATE Artiste SET
                                    Artiste.Date_Supression = null    
                                     WHERE Artiste.ID = " . $id . ";";
                            if ($results = mysqli_query($connexion, $sql)) {
                                $error = false;
                                $message = $prenom . " " . $nom . " réactivé";
                            } else {
                                $error = true;
                                $message = "Erreur d'ajout";
                            }
                        }
                    } else {
                        mysqli_free_result($results);
                        $sql = "INSERT INTO Artiste SET
                        Nom = '" . $nom . "',
                        Prenom = '" . $prenom . "',
                        Description = '" . $description . "',
                        Date_Creation = Now();";
                        if ($results = mysqli_query($connexion, $sql)) {
                            $error = false;
                            $message = "Artiste: " . $prenom . " " . $nom . " ajouté";
                            header("Location: Gestion_Artistes.php");

                        } else {
                            $error = true;
                            $message = "Erreur d'ajout";
                        }
                    }
                } else {
                    $error = true;
                    $message = "Erreur d'ajout 1";
                }
                mysqli_free_result($results);
                mysqli_close($connexion);
            } else {
                $error = true;
                $message = "Echec connexion DB";
            }
        } else {
            $error = true;
            $message = "Erreur dans la description de l'artiste";
        }
    }
}

    /* Supprimer artiste */

if(isset($_POST["idDeleteArtiste"]) AND is_numeric($_POST["idDeleteArtiste"])){
    $sql = "UPDATE Artiste SET
            Artiste.Date_Supression = Now()    
            WHERE Artiste.ID = ".$_POST["idDeleteArtiste"].";";
    if ($connexion = mysqli_connect(HOST, USER, PASS, NOM_DB)) {
        if ($results = mysqli_query($connexion, $sql)) {
            $error = false;
            $message = "Artiste Supprimée";
        }else{
            $error = true;
            $message = "Erreur de suppression";
        }
    }else{
        $error = true;
        $message = "Echec connexion DB";
    }
    echo $message;
    mysqli_free_result($results);
    mysqli_close($connexion);
}

 /* Ajout Couleur */

if(isset($_POST["NewCouleur"]) AND strlen($_POST["NewCouleur"]) !=0 ){
    if ($connexion = mysqli_connect(HOST, USER, PASS, NOM_DB)) {
        $nom = str_replace(" ","",$_POST["NewCouleur"]);
        $sql = "SELECT Couleur.Libelle, Couleur.Date_Supression,Couleur.ID FROM Couleur
                WHERE Couleur.Libelle LIKE '".$nom."'";
        if ($results = mysqli_query($connexion, $sql)) {
            if($row = mysqli_fetch_row($results)){
                $id = $row[2];
                if(is_null($row[1])){
                    $error = false;
                    $message = "Couleur: ".$nom." déja listée";
                }else{
                    mysqli_free_result($results);
                    $sql = "UPDATE Couleur SET
                                Couleur.Date_Supression = null    
                                 WHERE Couleur.ID = ".$id.";";
                }
            }else{
                mysqli_free_result($results);
                $sql = "INSERT INTO Couleur SET 
                    Libelle='".$_POST["NewCouleur"]."', 
                    Date_Creation = Now()";
            }
        }else{
            $error = true;
            $message = "Erreur d'ajout";

            echo $message;
        }

        if ($results = mysqli_query($connexion, $sql)) {
            $error = false;
            $message = "Couleur: ".$_POST["NewCouleur"]." ajoutée";
        }else{
            $error = true;
            $message = "Erreur d'ajout";
        }
    }else{
        $error = true;
        $message = "Echec connexion DB";
    }
    mysqli_free_result($results);
    mysqli_close($connexion);
}

    /* Supprimer couleur */
if(isset($_POST["idSuppCouleur"]) AND is_numeric($_POST["idSuppCouleur"])){
    $sql = "UPDATE Couleur SET
            Couleur.Date_Supression = Now()    
            WHERE Couleur.ID = ".$_POST["idSuppCouleur"].";";
    if ($connexion = mysqli_connect(HOST, USER, PASS, NOM_DB)) {
        if ($results = mysqli_query($connexion, $sql)) {
            $error = false;
            $message = "Couleur Supprimée";
        }else{
            $error = true;
            $message = "Erreur de suppression";
        }
    }else{
        $error = true;
        $message = "Echec connexion DB";
    }
    echo $message;
    mysqli_free_result($results);
    mysqli_close($connexion);
}

    /* Modification Couleur */

if(isset($_POST["ModifCouleur"]) AND isset($_POST["idToEdit"]) AND is_numeric($_POST["idToEdit"])){
    $libelle = str_replace('"','""',$_POST["ModifCouleur"]);
    $libelle = str_replace("'","''",$libelle);
    $sql = "UPDATE Couleur SET
            Couleur.Libelle = '".$libelle."'     
            WHERE Couleur.ID = ".$_POST["idToEdit"].";";

    if ($connexion = mysqli_connect(HOST, USER, PASS, NOM_DB)) {
        if ($results = mysqli_query($connexion, $sql)) {
            $error = false;
            $message = "Couleur ".$_POST["ModifCouleur"]." Modifiée";
        }else{
            $error = true;
            $message = "Erreur d'ajout";
        }
    }else{
        $error = true;
        $message = "Echec connexion DB";
    }
    echo $message;
    mysqli_free_result($results);
    mysqli_close($connexion);
}

/* Ajout Genre */

if(isset($_POST["NewGenre"]) AND strlen($_POST["NewGenre"]) !=0 ){
    if ($connexion = mysqli_connect(HOST, USER, PASS, NOM_DB)) {
        $nom = str_replace(" ","",$_POST["NewGenre"]);
        $sql = "SELECT Genre.Libelle, Genre.Date_Supression,Genre.ID FROM Genre
                WHERE Genre.Libelle LIKE '".$nom."'";
        if ($results = mysqli_query($connexion, $sql)) {
            if($row = mysqli_fetch_row($results)){
                $id = $row[2];
                if(is_null($row[1])){
                    $error = false;
                    $message = "Genre: ".$nom." déja listée";
                }else{
                    mysqli_free_result($results);
                    $sql = "UPDATE Genre SET
                                Genre.Date_Supression = null    
                                 WHERE Genre.ID = ".$id.";";
                }
            }else{
                mysqli_free_result($results);
                $sql = "INSERT INTO Genre SET 
                    Libelle='".$_POST["NewGenre"]."', 
                    Date_Creation = Now()";
            }
        }else{
            $error = true;
            $message = "Erreur d'ajout";

            echo $message;
        }

        if ($results = mysqli_query($connexion, $sql)) {
            $error = false;
            $message = "Genre: ".$_POST["NewGenre"]." ajoutée";
        }else{
            $error = true;
            $message = "Erreur d'ajout";
        }
    }else{
        $error = true;
        $message = "Echec connexion DB";
    }
    mysqli_free_result($results);
    mysqli_close($connexion);
}

/* Supprimer Genre */
if(isset($_POST["idSuppGenre"]) AND is_numeric($_POST["idSuppGenre"])){
    $sql = "UPDATE Genre SET
            Genre.Date_Supression = Now()    
            WHERE Genre.ID = ".$_POST["idSuppGenre"].";";
    if ($connexion = mysqli_connect(HOST, USER, PASS, NOM_DB)) {
        if ($results = mysqli_query($connexion, $sql)) {
            $error = false;
            $message = "Genre Supprimé";
        }else{
            $error = true;
            $message = "Erreur de suppression";
        }
    }else{
        $error = true;
        $message = "Echec connexion DB";
    }
    echo $message;
    mysqli_free_result($results);
    mysqli_close($connexion);
}

/* Modification Genre */

if(isset($_POST["ModifGenre"]) AND isset($_POST["idToEdit"]) AND is_numeric($_POST["idToEdit"])){
    $libelle = str_replace('"','""',$_POST["ModifGenre"]);
    $libelle = str_replace("'","''",$libelle);
    $sql = "UPDATE Genre SET
            Genre.Libelle = '".$libelle."'     
            WHERE Genre.ID = ".$_POST["idToEdit"].";";

    if ($connexion = mysqli_connect(HOST, USER, PASS, NOM_DB)) {
        if ($results = mysqli_query($connexion, $sql)) {
            $error = false;
            $message = "Genre ".$_POST["ModifGenre"]." Modifié";
        }else{
            $error = true;
            $message = "Erreur d'ajout";
        }
    }else{
        $error = true;
        $message = "Echec connexion DB";
    }
    echo $message;
    mysqli_free_result($results);
    mysqli_close($connexion);
}

/* Ajout Taille */

if(isset($_POST["NewTaille"]) AND strlen($_POST["NewTaille"]) !=0 ){
    if ($connexion = mysqli_connect(HOST, USER, PASS, NOM_DB)) {
        $nom = str_replace(" ","",$_POST["NewTaille"]);
        $sql = "SELECT Tailles.Libelle, Tailles.Date_Supression,Tailles.ID FROM Tailles
                WHERE Tailles.Libelle LIKE '".$nom."'";
        if ($results = mysqli_query($connexion, $sql)) {
            if($row = mysqli_fetch_row($results)){
                $id = $row[2];
                if(is_null($row[1])){
                    $error = false;
                    $message = "Taille: ".$nom." déja listée";
                }else{
                    mysqli_free_result($results);
                    $sql = "UPDATE Tailles SET
                                Tailles.Date_Supression = null    
                                 WHERE Tailles.ID = ".$id.";";
                }
            }else{
                mysqli_free_result($results);
                $sql = "INSERT INTO Tailles SET 
                    Libelle='".$_POST["NewTaille"]."', 
                    Date_Creation = Now()";
            }
        }else{
            $error = true;
            $message = "Erreur d'ajout";

            echo $message;
        }

        if ($results = mysqli_query($connexion, $sql)) {
            $error = false;
            $message = "Taille: ".$_POST["NewTaille"]." ajoutée";
        }else{
            $error = true;
            $message = "Erreur d'ajout";
        }
    }else{
        $error = true;
        $message = "Echec connexion DB";
    }
    mysqli_free_result($results);
    mysqli_close($connexion);
}

/* Supprimer Taille */
if(isset($_POST["idSuppTaille"]) AND is_numeric($_POST["idSuppTaille"])){
    $sql = "UPDATE Tailles SET
            Tailles.Date_Supression = Now()    
            WHERE Tailles.ID = ".$_POST["idSuppTaille"].";";
    if ($connexion = mysqli_connect(HOST, USER, PASS, NOM_DB)) {
        if ($results = mysqli_query($connexion, $sql)) {
            $error = false;
            $message = "Taille Supprimée";
        }else{
            $error = true;
            $message = "Erreur de suppression";
        }
    }else{
        $error = true;
        $message = "Echec connexion DB";
    }
    echo $message;
    mysqli_free_result($results);
    mysqli_close($connexion);
}

/* Modification Taille */

if(isset($_POST["ModifTaille"]) AND isset($_POST["idToEdit"]) AND is_numeric($_POST["idToEdit"])){
    $libelle = str_replace('"','""',$_POST["ModifTaille"]);
    $libelle = str_replace("'","''",$libelle);
    $sql = "UPDATE Tailles SET
            Tailles.Libelle = '".$libelle."'     
            WHERE Tailles.ID = ".$_POST["idToEdit"].";";

    if ($connexion = mysqli_connect(HOST, USER, PASS, NOM_DB)) {
        if ($results = mysqli_query($connexion, $sql)) {
            $error = false;
            $message = "Taille ".$_POST["ModifTaille"]." Modifiée";
        }else{
            $error = true;
            $message = "Erreur d'ajout";
        }
    }else{
        $error = true;
        $message = "Echec connexion DB";
    }
    echo $message;
    mysqli_free_result($results);
    mysqli_close($connexion);
}



$last_URL = explode ( "?",$_SERVER['HTTP_REFERER']);

if($error){
    header("location:  ".$last_URL[0]."?error=".$error."&message=".$message);
}else{
    header("location:  ".$last_URL[0]."?message=".$message);
}

?>