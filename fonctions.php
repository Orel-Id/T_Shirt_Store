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
function voirStock($tab = 'none')
{
    $html = "
    <TABLE>
         <thead>
            <tr>
                <th>Nom T-shirt</th>
                <th><select id='mySelect' onchange='filtreCategorie()'>
                 <option value=''>--Choisir Catégorie--</option>
                <option value='0'>Aucun Filtre</option>";

    if ($connexion = mysqli_connect(HOST, USER, PASS, NOM_DB)) {
        $sql_Categorie = "SELECT Categories.ID, Categories.Libelle FROM categories
                WHERE Categories.Date_Supression IS NULL";
        if ($results_categories = mysqli_query($connexion, $sql_Categorie)) {
            while ($row = mysqli_fetch_row($results_categories)) {
                $html = $html . "<option value='" . $row[0] . "'>" . $row[1] . "</option>";
            }
        } else {
            $error = true;
            $message = "Erreur requête";
        }
        mysqli_free_result($results_categories);

        $html = $html . "</select></th><th><select id='mySelectTaille' onchange='filtreTaille()'>
                 <option value=''>--Choisir Taille--</option>
                <option value='0'>Aucun Filtre</option>";

        $sql_Taille = "SELECT Tailles.ID, Tailles.Libelle FROM Tailles
                WHERE Tailles.Date_Supression IS NULL";
        if ($results_Taille = mysqli_query($connexion, $sql_Taille)) {
            while ($row = mysqli_fetch_row($results_Taille)) {
                $html = $html . "<option value='" . $row[0] . "'>" . $row[1] . "</option>";
            }
        } else {
            $error = true;
            $message = "Erreur requête";
        }
        mysqli_free_result($results_Taille);

        $html = $html . "</select></th>
            <th><select id='mySelectGenre' onchange='filtreGenre()'>
                 <option value=''>--Choisir Genre--</option>
                <option value='0'>Aucun Filtre</option>";

        $sql_Genre = "SELECT Genre.ID, Genre.Libelle FROM Genre
                WHERE Genre.Date_Supression IS NULL";
        if ($results_Genre = mysqli_query($connexion, $sql_Genre)) {
            while ($row = mysqli_fetch_row($results_Genre)) {
                $html = $html . "<option value='" . $row[0] . "'>" . $row[1] . "</option>";
            }
        } else {
            $error = true;
            $message = "Erreur requête";
        }
        mysqli_free_result($results_Genre);

        $html = $html . "</select></th>
                <th>Stock</th>
            </tr>
        </thead>
        <tbody>";


        $sql = "SELECT T_Shirt.ID, T_Shirt.Name, Categories.Libelle, T_Shirt.NB_stock, Tailles.Libelle , Genre.Libelle FROM T_Shirt
JOIN Categories ON Categories.ID = T_Shirt.ID_Catégorie
JOIN Tailles ON Tailles.ID = T_Shirt.ID_Taille
JOIN Genre ON Genre.ID = T_Shirt.ID_Sexe
WHERE T_Shirt.Date_suppression IS NULL";
        if($tab['categorie'] != 0 AND $tab['taille'] != 0 AND $tab['genre'] != 0){
            $sql = $sql." AND Categories.ID = ".$tab['categorie'] ." ";
            $sql = $sql." AND Tailles.ID = ".$tab['taille'] ." ";
            $sql = $sql." AND Genre.ID = ".$tab['genre'] ."; ";
    }elseif($tab['categorie'] != 0 AND $tab['taille'] != 0){
        $sql = $sql." AND Categories.ID = ".$tab['categorie'] ." ";
        $sql = $sql." AND Tailles.ID = ".$tab['taille'] ."; ";
    }elseif($tab['categorie'] != 0 AND $tab['genre'] != 0) {
            $sql = $sql . " AND Categories.ID = " . $tab['categorie'] . " ";
            $sql = $sql . " AND Genre.ID = " . $tab['genre'] . "; ";
    }elseif($tab['genre'] != 0 AND $tab['taille'] != 0){
            $sql = $sql." AND Genre.ID = ".$tab['genre'] ." ";
            $sql = $sql." AND Tailles.ID = ".$tab['taille'] ."; ";
    }elseif($tab['categorie'] != 0){
        $sql = $sql." AND Categories.ID = ".$tab['categorie'] ."; ";
    }elseif($tab['taille'] != 0){
       $sql = $sql." AND Tailles.ID = ".$tab['taille'] ."; ";
    }elseif($tab['genre'] != 0){
            $sql = $sql." AND Genre.ID = ".$tab['genre'] ."; ";
        }


        if ($results = mysqli_query($connexion, $sql)) {
            while ($row = mysqli_fetch_row($results)) {
                $html = $html . "<tr><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[4] . "</td><td>" . $row[5] . "</td><td>" . $row[3] . "</td></tr>";
                $error = false;
            }
        } else {
            $error = true;
            $message = "Erreur requête";
        }
        $html = $html . "</tbody></TABLE>";

        mysqli_free_result($results);
        mysqli_close($connexion);
    } else {
        $error = true;
        $message = "Echec connexion DB";
    }


    if ($error) {
        return $message;
    } else {
        return $html;
    }

}
function card(){

}



    ?>