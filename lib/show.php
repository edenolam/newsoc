<?php require 'autoload.inc.php';
$db = DBFactory:: getMysqlConnexionWithMySQLi();
$manager = new NewsManager_MySQLi ($db); ?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Administration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>
<body>
<?php
include 'navbar.inc.php';
?>

<div class="container mt-5 mb-5">


    <?php if (isset($_GET['id'])) {
        $news = $manager->getUnique(( int )$_GET ['id']);
        echo '<p>Par <em>', $news->getAuteur(), '</em>, ', $news->getDateAjout(), '</p>', " \n ", '<h2>', $news->getTitre(), '</h2>', " \n ", '<p>', nl2br($news->getContenu()), '</p>', " \n ";
        if ($news->getDateAjout() != $news->getDateModif()) {
            echo '<p><small><em>Modifiée ', $news->getDateModif(), '</em></small></p>';
        }
    } else {


    ?>
    <h2><?= $manager->count() ?> news</h2>

    <div class="row">
        <?php
        foreach ($manager->getList(0, 5) as $news) {
            if (strlen($news->getContenu()) <= 200) {
                $contenu = $news->getContenu();
            } else {
                $debut = substr($news->getContenu(), 0, 200);
                $debut = substr($debut, 0, strrpos($debut, ' ')) . '...';
                $contenu = $debut;
            }
            ?>
            <div class="col-4">
                <div class="card" style="width: 18rem;">
                    <div class="card-header">
                        <a href="show.php/?id=<?= $news->getId() ?>"><?= $news->getTitre() ?></a>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><?= nl2br($contenu) ?></li>
                    </ul>
                </div>
            </div>
            <?php
        }
        } ?>

    </div>
</div>
<?php
include 'footer.inc.php';
?>
</body>
</html>
