<?php
include_once '../../Controller/OffreController.php';

if (isset($_GET['id'])) {
    $offreC = new OffreController();
    $offreC->supprimerOffre($_GET['id']);

    header('Location:list.php');
}
?>