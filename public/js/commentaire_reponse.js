document.addEventListener('DOMContentLoaded', function() {
    // Sélectionnez tous les boutons de réponse
    var buttons = document.querySelectorAll('[data-button]');
    var activeForm = null; // Garde une référence au formulaire actuellement affiché

    // Ajoutez un gestionnaire d'événements à chaque bouton de réponse
    buttons.forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.stopPropagation(); // Empêche la propagation de l'événement de clic

            const commentaireId = button.getAttribute('data-button');
            const form_token = button.getAttribute('data-token');

            // Vérifiez s'il existe déjà un formulaire actif
            if (activeForm) {
                // Si le bouton actuel est le même que le précédent, arrêtez ici
                if (activeForm.getAttribute('data-commentaire') === commentaireId) {
                    return;
                } else {
                    // Supprimez le formulaire actif
                    activeForm.remove();
                    activeForm = null;
                }
            }

            // Créez un nouvel élément de formulaire
            var form = document.createElement('form');
            form.name = 'reponse_form';
            form.method = 'post';
            form.classList.add('comment-form');
            form.setAttribute('data-commentaire', commentaireId); // Stocke l'ID du commentaire dans l'attribut data

            // Ajoutez un champ de contenu
            var contenuTextarea = document.createElement('textarea');
            contenuTextarea.type = 'text';
            contenuTextarea.name = 'reponse_form[contenu]';
            contenuTextarea.required = 'required';
            contenuTextarea.placeholder = 'Écrivez votre réponse ici...';
            form.appendChild(contenuTextarea);

            // Ajoutez un champ caché pour l'ID du commentaire parent
            var parentInput = document.createElement('input');
            parentInput.type = 'hidden';
            parentInput.name = 'reponse_form[parent]';
            parentInput.value = commentaireId;
            form.appendChild(parentInput);

            // Ajoutez un champ caché pour l'ID du commentaire parent
            var tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = 'reponse_form[_token]';
            tokenInput.value = form_token;
            form.appendChild(tokenInput);

            // Ajoutez un bouton de soumission
            var submitButton = document.createElement('button');
            submitButton.type = 'submit';
            submitButton.class = 'btn btn-primary';
            submitButton.textContent = 'Répondre';
            form.appendChild(submitButton);

            // Ajoutez le formulaire au DOM
            button.parentNode.appendChild(form);

            // Gardez une référence au nouveau formulaire actif
            activeForm = form;

            // Ajoutez un gestionnaire d'événements pour supprimer le formulaire si un autre bouton est cliqué
            document.addEventListener('click', function(event) {
                // Vérifiez si l'élément cliqué est un bouton de réponse
                if (!event.target.hasAttribute('data-button')) {
                    // Vérifiez si l'élément cliqué est à l'intérieur du formulaire
                    if (!form.contains(event.target)) {
                        // Supprimez le formulaire actif
                        form.remove();
                        activeForm = null;
                    }
                }
            });

            // Empêchez le comportement par défaut du bouton
            event.preventDefault();
        });
    });
});
