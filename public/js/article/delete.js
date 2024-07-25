function getCurrentMode() {
    return document.body.classList.contains('data-bs-theme') ? 'dark' : 'light';
}

function confirmDelete(articleId) {
    const mode = getCurrentMode();
    const backgroundColor = mode === 'dark' ? '#2e2e2e' : '#ffffff';
    const textColor = mode === 'dark' ? '#ffffff' : '#000000';
    const confirmButtonColor = mode === 'dark' ? '#3085d6' : '#3085d6'; // Personnalisez selon vos besoins
    const cancelButtonColor = mode === 'dark' ? '#d33' : '#d33'; // Personnalisez selon vos besoins

    Swal.fire({
        title: 'Êtes-vous sûr?',
        text: "Vous ne pourrez pas annuler cette action!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: confirmButtonColor,
        cancelButtonColor: cancelButtonColor,
        confirmButtonText: 'Oui, supprimer!',
        cancelButtonText: 'Annuler',
        background: backgroundColor,
        customClass: {
            popup: 'swal-popup-custom'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "{{ path('delete_article', {'id': 'ARTICLE_ID'}) }}".replace('ARTICLE_ID', articleId);
        }
    });
}