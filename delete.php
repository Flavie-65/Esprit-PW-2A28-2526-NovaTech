<?php
include_once 'Controller/OffreController.php';

$controller = new OffreController();

if (isset($_GET['id'])) {
    $controller->deleteOffre($_GET['id']);
}

header("Location: View/list.php?msg=deleted");
exit();
?>