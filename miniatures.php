<?php
error_reporting(E_ALL | E_STRICT);

define ("NBRE_COLONNES", 4);

$types_ok = array ('image/jpeg', 'image/gif', 'image/png');
$tabl_exclus = array ('.', '..', 'miniature');
$tabl_liens = array();

// Parcourt le répertoire courant et tous ses sous-répertoires récursivement.
function liste_repertoire($dir) {
    if ($handle = opendir($dir)) {
        while (($file = readdir($handle)) !== false) {
            $chemin_fichier = $dir.'/'.$file;
            if (is_dir($chemin_fichier)) {
                if (!in_array($file, $GLOBALS['tabl_exclus'])) {
                    liste_repertoire($dir.'/'.$file);
                }
            } else {
                if (est_image($chemin_fichier)) {
                    $chemin_miniature = $dir.'/miniature/'.$file;
                    if (!file_exists($chemin_miniature)) {
                        genere_miniature($dir, $chemin_fichier, $chemin_miniature);
                    }
                    ajoute_lien($chemin_fichier, $chemin_miniature, $file);
                }
            }
        }
        closedir($handle);
    }
}

// Teste si le fichier passé en paramètre correspond à l'un des trois types d'images définis
function est_image($chemin_fichier) {
    if (list($GLOBALS['largeur'], $GLOBALS['hauteur'], $type) = getimagesize($chemin_fichier)) {
        $type = image_type_to_mime_type($type);
        if (in_array($type, $GLOBALS['types_ok'])) {
            $ext = explode("/", $type);
            $GLOBALS['extension'] = $ext[1];
            return true;
        }
    }
    return false;
}

// Génère la miniature de l'image dans le sous-répertoire 'miniature' si elle n'existe pas déjà
function genere_miniature($dir, $chemin_image, $chemin_miniature) {
    // Calcul du ratio entre la grande image et la miniature
    $taille_max = 100;
    if ($GLOBALS['largeur'] <= $GLOBALS['hauteur']) {
        $ratio = $GLOBALS['hauteur'] / $taille_max;
    } else {
        $ratio = $GLOBALS['largeur'] / $taille_max;
    }

    // Définition des dimensions de la miniature
    $larg_miniature = $GLOBALS['largeur'] / $ratio;
    $haut_miniature = $GLOBALS['hauteur'] / $ratio;

    // Crée la ressource image pour la miniature
    $destination = imagecreatetruecolor($larg_miniature, $haut_miniature);

    // Retourne un identifiant d'image jpeg, gif ou png
    $source = call_user_func('imagecreatefrom'.$GLOBALS['extension'], $chemin_image);

    // Redimensionne la grande image
    imagecopyresampled(    $destination,
        $source,
        0, 0, 0, 0,
        $larg_miniature,
        $haut_miniature,
        $GLOBALS['largeur'],
        $GLOBALS['hauteur']);

    // Si le répertoire de miniature n'existe pas, on le crée
    if (!is_dir($dir.'/miniature')) {
        mkdir ($dir.'/miniature', 0700);
    }

    // Écriture physique de l'image
    call_user_func('image'.$GLOBALS['extension'], $destination, $chemin_miniature);

    // Détruit les ressources temporaires créées
    imagedestroy($destination);
    imagedestroy($source);
}

// Crée le lien dans le tableau global
function ajoute_lien($chemin_image, $chemin_miniature, $file) {
    // Récupère la taille de la miniature sous forme HTML (width="xxx" height="yyy")
    $taille_html_miniature = getimagesize($chemin_miniature);
    $taille_html_miniature = $taille_html_miniature[3];

    // Rajoute le lien vers l'image au tableau global $GLOBALS['tabl_liens']
    $lien = '<a href="'.$chemin_image.'">';
    $lien .= '<img src="'.$chemin_miniature.'" '.$taille_html_miniature.' alt="'.$file.'">';
    $lien .= '</a>'."\n";

    array_push($GLOBALS['tabl_liens'], $lien);
}

// Gère l'affichage du tableau $GLOBALS['tabl_liens']
function affichage() {
    $compteur = 1;
    foreach ($GLOBALS['tabl_liens'] as $val_lien) {
        if ($compteur % NBRE_COLONNES == 1) {
            echo '<br>';
        }
        echo $val_lien;
        $compteur++;
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>Affichage images</title>
    <style type="text/css">
        <!--
        a img {
            border-color:transparent;
        }
        -->
    </style>
</head>
<body>
<?php
liste_repertoire('.');
affichage();
?>
</body>
</html>