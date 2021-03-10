<?php
define('USER', 'root');
define('PASS', 'hello');
define('NOM_DB', 'TshirtStore');
define('HOST', 'localhost');

function MenuCatMainNav(){
    if($connexion = mysqli_connect(HOST,USER,PASS,NOM_DB)) {
        $sql = "SELECT Categories.Libelle FROM categories
                WHERE Categories.Date_Supression IS NULL";
        $html= "";
        if ($results = mysqli_query($connexion, $sql)) {
            while($row = mysqli_fetch_row($results)) {
                $html = $html."<li><a href='".$row[0].".php'> ".$row[0]."</a></li> ";
                $error = false;
            }
            mysqli_free_result($results);
            mysqli_close($connexion);
            return $html;
        }else{
            $error = true;
            $message = "Erreur requête";
        }
    }else{
        $error = true;
        $message = "Echec connexion DB";
    }
    return $message;
}

function selectAdminAjout($sql){
    if($connexion = mysqli_connect(HOST,USER,PASS,NOM_DB)) {
        $html= "";
        if ($results = mysqli_query($connexion, $sql)) {
            while ($row = mysqli_fetch_row($results)) {
                $html = $html."<option value='" . $row[0] . "'>" . $row[1] . "</option>";
                $error = false;
            }
            mysqli_free_result($results);
            mysqli_close($connexion);
            return $html;
        } else {
            $error = true;
            $message = "Erreur requête";
        }
    }else{
        $error = true;
        $message = "Echec connexion DB";
    }
    return $message;

}

/*********************************************************************************************************
 *   **SelectConstrutor**                                                                                *
 * Fonction permettant de retourner le code html d'un select contenant l'id ou le nom d'un élément d'une *
 *  DB comme valeur et le nom du champs comme attribut                                                   *
 *   title => titre du select                                                                            *
 *   sql => requete DB                                                                                   *
 *   idSelect => Id a attribuer au select                                                                *
 *   FunctionName => Le nom de la fonction JS au "On Change"                                             *
 *   distinct => bool afin de déterminer si c'est l'id ou le nom qui doit etre attribué et distinct      *
 *                                                                                                       *
 *********************************************************************************************************/
function SelectConstrutor($title,$sql,$idSelect,$FunctionName,$distinct){
    $selectHtml = "<select id=$idSelect onchange=$FunctionName>";
    if ($connexion = mysqli_connect(HOST, USER, PASS, NOM_DB)) {

        if ($results = mysqli_query($connexion, $sql)){
            if($distinct){
                $selectHtml = $selectHtml."<option value='none'>--".$title."--</option>";
                while ($row = mysqli_fetch_row($results)) {
                    $nomId = str_replace(" ","_",$row[0]);
                    $nomId = str_replace("&","ET",$nomId);
                    $selectHtml =  $selectHtml . "<option value='" . $nomId . "'>" . $row[0] . "</option>";
                }
            }else{
                $selectHtml = $selectHtml."<option value='0'>--Choisir ".$title."--</option>";
                while ($row = mysqli_fetch_row($results)) {
                    $selectHtml = $selectHtml . "<option value='" . $row[0] . "'>" . $row[1] . "</option>";
                }
            }

            $selectHtml = $selectHtml."</select>";
            mysqli_free_result($results);
            mysqli_close($connexion);
            return $selectHtml;
        } else {
            $error = true;
            $message = "Erreur requête";
        }
    }else {
        $error = true;
        $message = "Echec connexion DB";
    }
    return array("error"=>$error,"message"=>$message);
}

/*********************************************************************************************************
*   **show Stock**                                                                                       *
 * Fonction permettant de retourner le code html d'un tableau contenant le stock en fonction de filtres  *
 * spécifiés dans le tableau tab passé en parametre. Dans tab la clé correspond à la table sur laquelle  *
 * le filtre s'applique et la valeur l'élément à filtrer                                                 *
 *                                                                                                       *
 *     ----- ATTENTION ----- Cette fonction utilise la fonction 'SelectConstructor                       *
 *                                                                                                       *
 *********************************************************************************************************/
function showStock($tab = 'none'){
    $html = "
    <TABLE class='TableStock'>
         <thead>
            <tr>";
    /********** NOM **********/
    $sql_TShirt = "SELECT DISTINCT T_Shirt.Name FROM T_Shirt
                WHERE T_Shirt.Date_suppression IS NULL";
    $html = $html."<th>".SelectConstrutor("Nom",$sql_TShirt,"mySelectNom","filtreNom()",true)."</th>";

    /********** CATEGORIES **********/
    $sql_Categorie = "SELECT Categories.ID, Categories.Libelle FROM categories
                WHERE Categories.Date_Supression IS NULL";
    $html = $html."<th>".SelectConstrutor("Catégorie",$sql_Categorie,"mySelectCategorie","filtreCategorie()",false)."</th>";

    /********** TAILLES **********/
    $sql_Taille = "SELECT Tailles.ID, Tailles.Libelle FROM Tailles
                WHERE Tailles.Date_Supression IS NULL";
    $html = $html."<th>".SelectConstrutor("Tailles",$sql_Taille,"mySelectTaille","filtreTaille()",false)."</th>";

    /********** GENRES **********/
    $sql_Genre = "SELECT Genre.ID, Genre.Libelle FROM Genre
                WHERE Genre.Date_Supression IS NULL";
    $html = $html."<th>".SelectConstrutor("Genre",$sql_Genre,"mySelectGenre","filtreGenre()",false)."</th>";

    /********** ARTISTES **********/
    $sql_Artiste = "SELECT Artiste.ID, Artiste.Nom FROM Artiste
                WHERE Artiste.Date_Supression IS NULL";
    $html = $html."<th>".SelectConstrutor("Artiste",$sql_Artiste,"mySelectArtiste","filtreArtiste()",false)."</th>";

    /********** COULEURS **********/
    $sql_Couleur = "SELECT Couleur.ID, Couleur.Libelle FROM Couleur
                WHERE Couleur.Date_Supression IS NULL";
    $html = $html."<th>".SelectConstrutor("Couleur",$sql_Couleur," mySelectCouleur","filtreCouleur()",false)."</th>";

    $html = $html."<th>Stock</th>
                <th><button> <img class='stockImg' src='assets/edit.svg' alt='Modifier' width='15px' height='15px'></button></th>
                <th><button> <img class='stockImg' src='assets/delete.svg' alt='Supprimer' width='15px' height='15px'></button></th>
            </tr>
        </thead>";

    /*********** TABLEAU DE RESULTATS ***********/

    if ($connexion = mysqli_connect(HOST, USER, PASS, NOM_DB)) {
        $sql = "SELECT T_Shirt.ID, T_Shirt.Name, Categories.Libelle, T_Shirt.NB_stock, Tailles.Libelle , Genre.Libelle,CONCAT(Artiste.Nom,' ',Artiste.Prenom),Couleur.Libelle FROM T_Shirt
                JOIN Categories ON Categories.ID = T_Shirt.ID_Catégorie
                JOIN Tailles ON Tailles.ID = T_Shirt.ID_Taille
                JOIN Genre ON Genre.ID = T_Shirt.ID_Sexe
                JOIN Artiste ON Artiste.ID = T_Shirt.ID_Artiste
                JOIN Couleur ON Couleur.ID = T_Shirt.ID_Couleur
                WHERE T_Shirt.Date_suppression IS NULL";

        /***** FILTRES *****/

        if(isset($tab['categorie']) AND $tab['categorie'] != 0){
            $sql = $sql." AND Categories.ID = ".$tab['categorie'] ." ";
        }
        if(isset($tab['taille']) AND $tab['taille'] != 0){
            $sql = $sql." AND Tailles.ID = ".$tab['taille'] ." ";
        }
        if(isset($tab['genre']) AND $tab['genre'] != 0){
            $sql = $sql." AND Genre.ID = ".$tab['genre'] ." ";
        }
        if(isset($tab['couleur']) AND $tab['couleur'] != 0){
            $sql = $sql." AND Couleur.ID = ".$tab['couleur'] ." ";
        }
        if(isset($tab['artiste']) AND $tab['artiste'] != 0){
            $sql = $sql." AND Artiste.ID = ".$tab['artiste'] ." ";
        }

        if(isset($tab['nom']) AND strlen($tab['nom']) != 0 AND $tab['nom'] != 'none'){
            $nom = str_replace("_"," ",$tab['nom']);
            $nom = "'".$nom."'";
            $nom = str_replace("ET","&",$nom);
            echo "<br>Le nom est: ".$nom;
            $sql = $sql." AND T_Shirt.Name = ".$nom ." ";
            }

            /* VARIFICATION A SUPPRIMER
                print_r($tab);
                echo "<br>La requete est: ".$sql;
            // VARIFICATION A SUPPRIMER */

            if ($results = mysqli_query($connexion, $sql)) {
                while ($row = mysqli_fetch_row($results)) {
                    $html = $html . "<tr><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[4] . "</td><td>" . $row[5] . "</td><td>" . $row[6] . "</td><td>" . $row[7] . "</td><td>" . $row[3] . "</td><td> <input type='radio' id='tshirtModifId".$row[0]."' name='DeleteTshirt' value=".$row[0]."
            ></td><td> <input type='checkbox' id='tshirtDelId".$row[0]."' name='ModifTshirt' value=".$row[0]."
            ></td></tr>";
                    $error = false;
                }
                mysqli_free_result($results);
                mysqli_close($connexion);
                $html = $html . "</tbody></TABLE>";
            } else {
                $error = true;
                $message = "Erreur requête";
            }

        }else {
            $error = true;
            $message = "Echec connexion DB";
        }
    if ($error) {
        return $message;
    } else {
        return $html;
    }
}

function filtreUrl($filtre){
    if(count($_GET) != 0){
        $location = "admin.php?";
        if(!array_key_exists ($filtre,$_GET)){
            $end_location = $filtre."=";
        }
        foreach ($_GET as $key => $val){
            if($key != $filtre){
                $location = $location.$key."=".$val."&";
            }else{
                $end_location = $filtre."=";
            }
        }
        $location = $location.$end_location;
    }else{
        $location = "admin.php?".$filtre."=";
    }

    return $location;
}

function MotifCategorie($id,$bool_save,$newName){
    if(isset($id) AND is_numeric($id) AND $id !=0 AND $bool_save){
        if ($connexion = mysqli_connect(HOST, USER, PASS, NOM_DB)) {
            $html = "<div id='motifCategorie' class='motifCategorie'><form action='admin.php' method='post'><table>";
            $sql = "SELECT Categories.ID, Categories.Libelle FROM Categories WHERE Categories.ID=".$id." " ;
            echo $sql."<br>";
            if ($results = mysqli_query($connexion, $sql)) {
                while($row = mysqli_fetch_row($results)){
                    $html = $html."<tr><td><input name='IdCatModif' type='hidden' value=".$id."></td>";
                    $html = $html."<tr><td><label for='libelleCatModif'>Nouveau Nom:</label></td>";
                    $html = $html."<td><input id='libelleCatModif' type='text' name='libelleCatModif' value='".$row[1]."'></td></tr>";
                    $html = $html."<tr><td colspan='2'><button type='submit'>Enregistrer</button></td> </tr>";
                }
            }else{
                $error = true;
                $message = "Erreur d'ajout";
            }
        }else{
            $error = true;
            $message = "Echec connexion DB";
        }
        $html = $html."</table></form></div>";
        if($error){
            return $message;
        }else{
            return $html;
        }
    }elseif (isset($id) AND is_numeric($id) AND $id !=0 AND !$bool_save) {
        if ($connexion = mysqli_connect(HOST, USER, PASS, NOM_DB)) {
            $sql = "UPDATE Categories SET
             Categories.Libelle ='" . $newName . "'
            WHERE Categories.ID=" . $id . " ";
            echo "<br>La requete de sauvegarde est: ".$sql;
            if ($results = mysqli_query($connexion, $sql)) {
                while ($row = mysqli_fetch_row($results)) {
                    $html = $html . "<tr><td><label for='libelleCatModif'>Nouveau Nom:</label></td>";
                    $html = $html . "<td><input id='libelleCatModif' type='text' name='libelleCatModif' value='" . $row[1] . "'></td></tr>";
                    $html = $html . "<tr><td colspan='2'><button type='submit'>Enregistrer</button></td> </tr>";
                }
            } else {
                $error = true;
                $message = "Erreur d'ajout";
            }
        } else {
            $error = true;
            $message = "Echec connexion DB";
        }
    }
}

function card(){

}
    ?>