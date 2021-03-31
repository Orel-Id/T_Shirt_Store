<?php
require ('header_admin.php');
require ('fonctions.php');

    /**** OUVERTURE DE CONNEXION ****/
?>
<div id="Stock" class="stock">
    <h2><span>Stock</span></h2>
    <?php echo showStock($_GET); ?>
</div>

<script type="text/javascript">

    <?php
    if(isset($_GET["page"])){
        echo 'document.getElementById("Stock").style.display = "none";';
        echo ' document.getElementById("'.$_GET["page"].'").style.display = "block";';
    }
    if(isset($_GET["categorie"])){
        echo 'document.getElementById("mySelectCategorie").options[checkSelect("mySelectCategorie","categorie")].selected = "selected";';
    }
    if(isset($_GET["taille"])){
        echo 'document.getElementById("mySelectTaille").options[checkSelect("mySelectTaille","taille")].selected = "selected";';
    }
    if(isset($_GET["genre"])){
        echo 'document.getElementById("mySelectGenre").options[checkSelect("mySelectGenre","genre")].selected = "selected";';
    }
    if(isset($_GET["genre"])){
        echo 'document.getElementById("mySelectNom").options[checkSelect("mySelectNom","nom")].selected = "selected";';
    }
    if(isset($_GET["artiste"])){
        echo 'document.getElementById("mySelectArtiste").options[checkSelect("mySelectArtiste","artiste")].selected = "selected";';
    }
    if(isset($_GET["couleur"])){
        echo 'document.getElementById("mySelectCouleur").options[checkSelect("mySelectCouleur","couleur")].selected = "selected";';
    }

    if(isset($_POST["idModifCategorie"]) OR isset($_POST["libelleCatModif"]) OR isset($_POST["IdCatModif"])){
        echo 'document.getElementById("Categories").style.display = "block";';
        echo 'document.getElementById("Stock").style.display = "none";';
        unset ($_POST["idModifCategorie"]);
    }
    ?>

       function checkSelect(id,NomSection){
        var selectBox = document.getElementById(id);

        switch (NomSection) {
            case "categorie":
                var get = '<?php echo $_GET["categorie"]?>';
                break;
            case "genre":
                var get = '<?php echo $_GET["genre"]?>';
                break;
            case "taille":
                var get = '<?php echo $_GET["taille"]?>';
                break;
            case "nom":
                var get = '<?php echo $_GET["nom"]?>';
                break;
            case "couleur":
                var get = '<?php echo $_GET["couleur"]?>';
                break;
            case "artiste":
                var get = '<?php echo $_GET["artiste"]?>';
                break;
        }

        for(var i=0 ; i<selectBox.options.length ;i++){
            if(get == selectBox.options[i].value){
                var nb = i ;
            }

        }
        return nb;
    }
    function filtreCategorie() {
        var x = document.getElementById("mySelectCategorie").value;
        <?php
        $location = filtreUrl("categorie");
        ?>
        var url = '<?php echo $location; ?>';
        document.location.href=url+x;
    }
    function filtreArtiste() {
        var x = document.getElementById("mySelectArtiste").value;
        <?php
        $location = filtreUrl("artiste");
        ?>
        var url = '<?php echo $location; ?>';
        document.location.href=url+x;
    }
    function filtreCouleur() {
        var x = document.getElementById("mySelectCouleur").value;
        <?php
        $location = filtreUrl("couleur");
        ?>
        var url = '<?php echo $location; ?>';
        document.location.href=url+x;
    }
    function filtreTaille() {
        var x = document.getElementById("mySelectTaille").value;

        <?php
        $location = filtreUrl("taille");
        ?>
        var url = '<?php echo $location; ?>';
        document.location.href=url+x;

    }
    function filtreGenre(){
        var x = document.getElementById("mySelectGenre").value;
        <?php
        $location = filtreUrl("genre");
        ?>
        var url = '<?php echo $location; ?>';
        document.location.href=url+x;

    }
    function  filtreNom(){
        var x = document.getElementById("mySelectNom").value;
        <?php
        $location = filtreUrl("nom");
        ?>
        var url = '<?php echo $location; ?>';
        document.location.href=url+x;

    }


</script>



















