<?php
include_once '../../Controller/CandidatureController.php';

$cC = new CandidatureController();

if (isset($_GET['id'])) {
    $cC->supprimerCandidature($_GET['id']);
}

header('Location: candidatures.php');