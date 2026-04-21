<?php


class TacheController {
    private $tacheManager;
    private $projetManager;

    public function __construct() {
        $this->tacheManager = new TacheManager();
        $this->projetManager = new ProjetManager();
    }

    // Afficher toutes les taches
    public function afficherTaches() {
        $taches = $this->tacheManager->getAllTaches();
        $projets = $this->projetManager->getAllProjets();
        // Chemin relatif à l'index.php
        include 'views/backoffice/tache/liste_taches.php';
    }

    // Afficher le formulaire d'ajout
    public function afficherFormulaireAjout() {
        $projets = $this->projetManager->getAllProjets();
        include 'views/backoffice/tache/ajouter_tache.php';
    }

    // Ajouter une tache
    public function ajouterTache() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // --- CONTRÔLE DE SAISIE PHP
            if (empty($_POST['nom_tache']) || empty($_POST['id_projet']) || empty($_POST['date_debut'])) {
                $erreur = "Veuillez remplir tous les champs obligatoires (Nom, Projet, Date début).";
                $projets = $this->projetManager->getAllProjets();
                include 'views/tache/ajouter_tache.php';
                return; // On arrête l'ajout
            }

            $tache = new Tache(
                null,
                $_POST['nom_tache'],
                $_POST['description'],
                $_POST['date_debut'],
                $_POST['date_fin'],
                $_POST['statut'],
                $_POST['priorite'],
                $_POST['id_projet']
            );
            
            $this->tacheManager->ajouterTache($tache);
            header('Location: index.php?module=tache&action=liste');
            exit();
        }
    }

    // Afficher le formulaire de modification
    public function afficherFormulaireModification($id_tache) {
        $tache = $this->tacheManager->getTacheById($id_tache);
        $projets = $this->projetManager->getAllProjets();
        include 'views/backoffice/tache/modifier_tache.php';
    }

    // Modifier une tache
    public function modifierTache() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // Contrôle de saisie aussi pour la modification
            if (empty($_POST['nom_tache'])) {
                $id_tache = $_POST['id_tache'];
                $tache = $this->tacheManager->getTacheById($id_tache);
                $projets = $this->projetManager->getAllProjets();
                $erreur = "Le nom de la tâche ne peut pas être vide.";
                include 'views/tache/modifier_tache.php';
                return;
            }

            $tache = new Tache(
                $_POST['id_tache'],
                $_POST['nom_tache'],
                $_POST['description'],
                $_POST['date_debut'],
                $_POST['date_fin'],
                $_POST['statut'],
                $_POST['priorite'],
                $_POST['id_projet']
            );
            $this->tacheManager->modifierTache($tache);
            header('Location: index.php?module=tache&action=liste');
            exit();
        }
    }

    // Supprimer une tache
    public function supprimerTache($id_tache) {
        $this->tacheManager->supprimerTache($id_tache);
        header('Location: index.php?module=tache&action=liste');
        exit();
    }
}