<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
ob_start(); 

require_once 'models/config/database.php';
require_once 'models/Projet.php';
require_once 'models/Tache.php';

// INCLUSION DES CONTROLEURS
require_once 'controllers/ProjetController.php';
require_once 'controllers/TacheController.php';
require_once 'controllers/ExportController.php'; 

$module = $_GET['module'] ?? 'projet';
$action = $_GET['action'] ?? 'liste';

switch ($module) {
    case 'home':
        header('Location: index.php?module=projet&action=liste_public');
        exit();

    case 'projet':
        $controller = new ProjetController();
        switch ($action) {
            case 'liste': $controller->afficherProjets(); break;
            case 'liste_public': $controller->afficherProjetsPublic(); break;
            case 'ajouterPost': $controller->ajouterProjet(); break;
            case 'modifier': $controller->afficherFormulaireModification($_GET['id_projet'] ?? null); break;
            case 'modifierPost': $controller->modifierProjet(); break; // <-- CORRECTION ICI
            case 'supprimer': $controller->supprimerProjet($_GET['id_projet'] ?? null); break;
            default: $controller->afficherProjetsPublic(); break;
        }
        break;

    case 'tache':
        $controller = new TacheController();
        switch ($action) {
            case 'liste': $controller->afficherTaches(); break;
            case 'ajouterPost': $controller->ajouterTache(); break;
            case 'modifier': $controller->afficherFormulaireModification($_GET['id_tache'] ?? null); break;
            case 'modifierPost': $controller->modifierPost(); break;
            case 'supprimer': $controller->supprimerTache($_GET['id_tache'] ?? null); break;
            default: $controller->afficherTaches(); break;
        }
        break;

    case 'export': 
        $controller = new ExportController();
        if ($action === 'pdf' && isset($_GET['id_projet'])) {
            $controller->genererRapportProjet($_GET['id_projet']);
            exit(); 
        }
        break;

    default:
        echo "Module non trouvé.";
        break;
}

$contenu = ob_get_clean(); 

// Si c'est une action de traitement (Post), on ne charge pas de template
if ($action === 'modifierPost' || $action === 'ajouterPost' || $action === 'supprimer') {
    echo $contenu;
} elseif ($action === 'liste_public') {
    require_once 'views/frontoffice/template_front.php';
} else {
    require_once 'views/backoffice/template_back.php';
}