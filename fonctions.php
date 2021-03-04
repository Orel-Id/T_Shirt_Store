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
function card(){

}



    ?>