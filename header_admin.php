
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Super titre</title>
    <meta charset="UTF-8"/>
    <!--<link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700;900&display=swap" >
    -->
    <link rel="stylesheet" href="css/reset.css" />
    <link type="text/css" rel="stylesheet" href="css/admin.css" />

</head>
<body>
<aside class="adminMenu">
    <div class="flex f-space-between">
        <img class="profil" src="assets/user.svg" alt="Profil">
        <p class="log">Admin</p>
        <img class="profil" src="assets/logout.svg" alt="Log Out">
    </div>
    <nav>
        <ul class="adminNav"">
        <li id="Bstock"><a href="admin.php?stock=true">Stock</a></li>
        <ul class="gestionStock">
            <li id="BAjout" class="options"><a href="admin.php?ajout=true"> Ajouter un t-shirt</a></li>
            <li id="BCategorieEdit" class="options"><a href="admin.php?categorie=true">Gérez les catégories</a></li>
            <li id="BCouleurEdit" class="options"><a href="admin.php?couleur=true">Gérez les Couleurs</a></li>
            <li id="BGenreEdit" class="options"><a href="admin.php?genre=true">Gérez les Genres</a></li>
            <li id="BTailleEdit" class="options"><a href="admin.php?taille=true">Gérez les Tailles</a></li>
            <li id="BArtistEdit" class="options"><a href="admin.php?artiste=true">Gérez les Artistes</a></li>
        </ul>
        <li id="Bcommandes">Commandes</li>
        </ul>
    </nav>
</aside>