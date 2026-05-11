document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formAjouterProjet');
    
    // Sécurité : on vérifie si le formulaire existe sur la page actuelle
    if (form) {
        form.addEventListener('submit', function(e) {
            let error = false;
            
            // Nettoyage des erreurs précédentes
            document.querySelectorAll('.erreur').forEach(el => el.textContent = "");

            const nom = document.getElementById('nom_projet').value;
            const debut = document.getElementById('date_debut').value;
            const fin = document.getElementById('date_fin').value;

            // Vérifier si le nom est vide
            if (nom.trim() === "") {
                const errNom = document.getElementById('erreur_nom');
                if(errNom) errNom.textContent = "Le nom est requis.";
                error = true;
            }

            // Vérifier format date
            if (debut === "") {
                const errDebut = document.getElementById('erreur_date_debut');
                if(errDebut) errDebut.textContent = "La date de début est requise.";
                error = true;
            }

            // Bonus : Vérifier que la date de fin n'est pas avant la date de début
            if (debut && fin && fin < debut) {
                alert("La date de fin ne peut pas être avant la date de début !");
                error = true;
            }

            if (error) {
                e.preventDefault(); // Bloque l'envoi du formulaire
            }
        });
    }
});