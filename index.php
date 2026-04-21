<?php
require_once 'models/config/database.php';
require_once 'models/Projet.php';
require_once 'models/ProjetManager.php';
require_once 'models/Tache.php';
require_once 'models/TacheManager.php';

$module = isset($_GET['module']) ? $_GET['module'] : 'projet';
$action = isset($_GET['action']) ? $_GET['action'] : 'liste';

switch ($module) {
    case 'projet':
        require_once 'controllers/ProjetController.php';
        $controller = new ProjetController();
        switch ($action) {
            case 'liste':
                $controller->afficherProjets();
                break;
            case 'ajouter':
                $controller->afficherFormulaireAjout();
                break;
            case 'ajouterPost':
                $controller->ajouterProjet();
                break;
            case 'modifier':
                $controller->afficherFormulaireModification($_GET['id_projet']);
                break;
            case 'modifierPost':
                $controller->modifierProjet();
                break;
            case 'supprimer':
                $controller->supprimerProjet($_GET['id_projet']);
                break;
            default:
                $controller->afficherProjets();
                break;
        }
        break;

    case 'tache':
        require_once 'controllers/TacheController.php';
        $controller = new TacheController();
        switch ($action) {
            case 'liste':
                $controller->afficherTaches();
                break;
            case 'ajouter':
                $controller->afficherFormulaireAjout();
                break;
            case 'ajouterPost':
                $controller->ajouterTache();
                break;
            case 'modifier':
                $controller->afficherFormulaireModification($_GET['id_tache']);
                break;
            case 'modifierPost':
                $controller->modifierTache();
                break;
            case 'supprimer':
                $controller->supprimerTache($_GET['id_tache']);
                break;
            default:
                $controller->afficherTaches();
                break;
        }
        break;

    default:
        require_once 'controllers/ProjetController.php';
        $controller = new ProjetController();
        $controller->afficherProjets();
        break;
}
?>