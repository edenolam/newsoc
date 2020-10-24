<?php
require 'autoload.inc.php';

$db = DBFactory::getMysqlConnexionWithMySQLi();
$manager = new NewsManager_MySQLi($db);

if (isset($_GET['modifier'])) {
    $news = $manager->getUnique((int)$_GET['modifier']);
}

if (isset($_GET['supprimer'])) {
    $manager->delete((int)$_GET['supprimer']);
    $message = 'La news a bien ete supprimé';
}

if (isset($_POST['auteur'])) {
    $news = new News(
        array(
            'auteur' => $_POST['auteur'],
            'titre' => $_POST['titre'],
            'contenu' => $_POST['contenu']
        )
    );

    if (isset($_POST['id'])) {
        $news->setId($_POST['id']);
    }

    if ($news->isValid()) {
        $manager->save($news);
        $message = $news->isNew() ? 'La news a bien ete ajouté' : 'la news a bien ete modifié';
    } else {
        $erreurs = $news->erreurs();
    }

}
?>

<?php
include 'header.inc.php';
include 'navbar.inc.php';
?>
<div class="container mt-5 mb-5">
    <form action="admin.php" method="post">
        <div class="form-group">



                <?php
                if (isset($message)) {
                    echo '<div class="alert alert-primary" role="alert">';
                    echo $message, '<br>';
                    echo '</div>';
                }
                ?>
                <?php
                if (isset($erreurs) && in_array(News::AUTEUR_INVALIDE, $erreurs)) {
                    echo '<div class="alert alert-danger" role="alert">';
                    echo 'auteur invalide';
                    echo '</div>';
                }
                ?>
                <label>Auteur
                    <input type="text" class="form-control" name="auteur" value="<?= isset($news) ? $news->getAuteur() : '' ?>">
                </label>

                <?php
                if (isset($erreurs) && in_array(News::TITRE_INVALIDE, $erreurs)) {
                    echo '<div class="alert alert-danger" role="alert">';
                    echo 'Titre invalide';
                    echo '</div>';
                }
                ?>
                <label> Titre :
                    <input type="text" class="form-control" name="titre" value=" <?= isset($news) ? $news->getTitre() : ''; ?> "/>
                </label><br/>

                <?php
                if (isset($erreurs) && in_array(News::CONTENU_INVALIDE, $erreurs)) {
                    echo '<div class="alert alert-danger" role="alert">';
                    echo 'Contenu invalide';
                    echo '</div>';
                }
                ?>
                <label> Contenu :
                    <textarea name="contenu" class="form-control">  <?= isset($news) ? $news->getContenu() : ''; ?> </textarea>
                </label><br/>

                <?php if (isset ($news) && !$news->isNew()) { ?>
                    <input type="hidden" name="id" value=" <?php echo $news->getId(); ?> "/>
                    <input type="submit" value="Modifier" name="modifier"/>
                <?php } else { ?>
                    <input type="submit" value="Ajouter"/>
                <?php } ?>

        </div>
    </form>

    <p>il y a actuellement <?= $manager->count() ?> news.</p>
    <p>voici la liste </p>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Auteur</th>
            <th>Titre</th>
            <th>Date ajout</th>
            <th>Date modif</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($manager->getList() as $news) {
            echo '<tr>';
            echo '<td>', $news->getAuteur(), '</td>';
            echo '<td>', $news->getTitre(), '</td>';
            echo '<td>', $news->getDateAjout(), '</td>';
            echo '<td>', ($news->getDateAjout() == $news->getDateModif() ? '-' : $news->getDateModif()), '</td>';
            echo '<td><a href="?modifier=', $news->getId(), '">Modifier</a> | <a href="?supprimer=', $news->getId(), '">Supprimer</a></td>';
            echo '</tr>';
        } ?>
        </tbody>
    </table>
</div>

<?php
include 'footer.inc.php';
?>
</body>
</html>
