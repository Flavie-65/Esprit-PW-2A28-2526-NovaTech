<?php
// Fichier : models/Notification.php

class Notification {

    /**
     * Envoie un email en texte brut lors d'un changement de statut
     */
    public static function envoyerAlerte($nomProjet, $statut) {
        // Paramètres de l'email
        $destinataire = "votre-prof@domaine.com"; // À remplacer par l'email cible
        $sujet = "Notification : Mise a jour du projet " . $nomProjet;
        
        // Corps du message : TEXTE BRUT UNIQUEMENT (Pas de HTML5)
        $message = "Bonjour,\n\n";
        $message .= "Une modification importante a ete enregistree sur votre plateforme.\n\n";
        $message .= "PROJET : " . $nomProjet . "\n";
        $message .= "NOUVEAU STATUT : " . strtoupper($statut) . "\n\n";
        $message .= "Date de l'evenement : " . date('d/m/Y a H:i') . "\n";
        $message .= "Cordialement,\nLe systeme de gestion de taches.";

        // En-têtes pour un email propre
        $entetes = "From: noreply@votre-application.com\r\n";
        $entetes .= "X-Mailer: PHP/" . phpversion();

        // Envoi via la fonction native de PHP
        // Note : Sous XAMPP Windows, cela nécessite une configuration dans php.ini
        return @mail($destinataire, $sujet, $message, $entetes);
    }
}