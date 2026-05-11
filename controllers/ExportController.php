<?php
require_once __DIR__ . '/../models/config/database.php';
require_once __DIR__ . '/../libraries/fpdf/fpdf.php';

class ExportController {

    public function genererRapportProjet($id_projet) {
        ini_set('display_errors', 0);

        $db = config::getConnexion();

        $stmtP = $db->prepare("SELECT * FROM projets WHERE id_projet = ?");
        $stmtP->execute([$id_projet]);
        $projet = $stmtP->fetch(PDO::FETCH_OBJ);

        if (!$projet) {
            die("Erreur : Projet introuvable.");
        }

        $stmtT = $db->prepare("SELECT * FROM taches WHERE id_projet = ?");
        $stmtT->execute([$id_projet]);
        $taches = $stmtT->fetchAll(PDO::FETCH_OBJ);

        $pdf = new FPDF();
        $pdf->AddPage();
        
        $pdf->SetFont('Arial', 'B', 18);
        $pdf->SetTextColor(33, 150, 243); 
        // Remplacement de utf8_decode par iconv pour éviter l'erreur "obsolète"
        $pdf->Cell(0, 15, iconv('UTF-8', 'windows-1252', "RAPPORT DE PROJET"), 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', "Nom du projet : " . $projet->nom_projet), 0, 1);
        
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 8, iconv('UTF-8', 'windows-1252', "Statut actuel : " . $projet->statut), 0, 1);
        $pdf->Ln(10);

        $pdf->SetFillColor(230, 230, 230);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(100, 10, iconv('UTF-8', 'windows-1252', "Description de la tâche"), 1, 0, 'C', true);
        $pdf->Cell(45, 10, "Priorite", 1, 0, 'C', true);
        $pdf->Cell(45, 10, "Statut", 1, 1, 'C', true);

        $pdf->SetFont('Arial', '', 10);
        foreach ($taches as $t) {
            $pdf->Cell(100, 10, iconv('UTF-8', 'windows-1252', $t->nom_tache), 1);
            $pdf->Cell(45, 10, iconv('UTF-8', 'windows-1252', $t->priorite), 1, 0, 'C');
            $pdf->Cell(45, 10, iconv('UTF-8', 'windows-1252', $t->statut), 1, 1, 'C');
        }

        if (ob_get_length()) ob_end_clean();

        $pdf->Output('I', 'Rapport_' . $projet->nom_projet . '.pdf');
    }
}