
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Super titre</title>
    <meta charset="UTF-8"/>
    <!--<link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700;900&display=swap" >
    -->
    <link rel="stylesheet" href="css/reset.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link type="text/css" rel="stylesheet" href="css/admin.css" />
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="logo">
            <a class="navbar-brand" href="index.php"></a>
        </div>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item <?php if($_SERVER['PHP_SELF'] == '/admin.php'){echo 'active ';} ?>">
                    <a class="nav-link" href="admin.php">Stock<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item <?php if($_SERVER['PHP_SELF'] == '/commandes.php'){echo 'active ';} ?>">
                    <a class="nav-link" href="commandes.php">Commandes</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Gestion
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="Gestion_Categorie.php">Gérez les catégories</a>
                        <a class="dropdown-item" href="Gestion_Couleurs.php">Gérez les Couleurs</a>
                        <a class="dropdown-item" href="Gestion_Genres.php">Gérez les Genres</a>
                        <a class="dropdown-item" href="Gestion_Taille.php">Gérez les Tailles</a>
                        <a class="dropdown-item" href="Gestion_Artistes.php">Gérez les Artistes</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="Ajout_TShirt.php">Ajouter un t-shirt</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>

<!--
<aside class="adminMenu">
    <div class="flex f-space-between">
        <img class="profil" src="assets/user.svg" alt="Profil">
        <p class="log">Admin</p>
        <img class="profil" src="assets/logout.svg" alt="Log Out">
    </div>
    <nav>
        <ul class="adminNav"">
        <li id="Bstock"><a href="admin.php">Stock</a></li>
        <ul class="gestionStock">
            <li id="BAjout" class="options"><a href="Ajout_TShirt.php"> Ajouter un t-shirt</a></li>
            <li id="BCategorieEdit" class="options"><a href="Gestion_Categorie.php">Gérez les catégories</a></li>
            <li id="BCouleurEdit" class="options"><a href="Gestion_Couleurs.php">Gérez les Couleurs</a></li>
            <li id="BGenreEdit" class="options"><a href="Gestion_Genres.php">Gérez les Genres</a></li>
            <li id="BTailleEdit" class="options"><a href="Gestion_Tailles.php">Gérez les Tailles</a></li>
            <li id="BArtistEdit" class="options"><a href="Gestion_Artistes.php">Gérez les Artistes</a></li>
        </ul>
        <li id="Bcommandes">Commandes</li>
        </ul>
    </nav>
</aside>
-->
<main>