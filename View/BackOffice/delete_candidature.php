<?php
include_once '../../Controller/CandidatureController.php';

$cC = new CandidatureController();

if (isset($_GET['id'])) {

    $result = $cC->supprimerCandidature($_GET['id']);

    if ($result) {
        header('Location: candidatures.php?success=delete');
    } else {
        header('Location: candidatures.php?error=delete');
    }

    exit();
}
?>