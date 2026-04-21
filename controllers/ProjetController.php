<?php
require_once __DIR__ . '/../models/ProjetManager.php';
require_once __DIR__ . '/../models/Projet.php';

class ProjetController {
    private $manager;

    public function __construct() {
        $this->manager = new ProjetManager();
    }

    // Correspond au case 'liste' de ton index.php
    public function afficherProjets() {
        $projets = $this->manager->getAllProjets();
        require_once __DIR__ . '/../views/backoffice/projet/liste_projets.php';
    }

    // Correspond au case 'ajouter'
    public function afficherFormulaireAjout() {
        require_once __DIR__ . '/../views/backoffice/projet/ajouter_projet.php';
    }

    // Correspond au case 'ajouterPost'
    public function ajouterProjet() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation PHP (Sécurité serveur - indispensable car pas de HTML5)
            if (empty($_POST['nom_projet']) || strlen($_POST['nom_projet']) < 3) {
                die("Erreur : Le nom du projet est trop court ou vide.");
            }

            // Création de l'objet Projet (POO)
            $projet = new Projet(
                null, // L'ID est auto-incrémenté en base
                $_POST['nom_projet'],
                $_POST['description'],
                $_POST['date_debut'],
                $_POST['date_fin'],
                $_POST['statut']
            );

            $this->manager->ajouterProjet($projet);
            
            // Redirection vers l'index pour rafraîchir la liste
            header('Location: index.php?module=projet&action=liste');
            exit();
        }
    }

    // Correspond au case 'modifier'
    public function afficherFormulaireModification($id) {
        $projet = $this->manager->getProjetById($id);
        require_once __DIR__ . '/../views/backoffice/projet/modifier_projet.php';
    }

    // Correspond au case 'modifierPost'
    public function modifierProjet() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $projet = new Projet(
                $_POST['id_projet'],
                $_POST['nom_projet'],
                $_POST['description'],
                $_POST['date_debut'],
                $_POST['date_fin'],
                $_POST['statut']
            );
            $this->manager->modifierProjet($projet);
            header('Location: index.php?module=projet&action=liste');
            exit();
        }
    }

    // Correspond au case 'supprimer'
    public function supprimerProjet($id) {
        $this->manager->supprimerProjet($id);
        header('Location: index.php?module=projet&action=liste');
        exit();
    }
}