document.addEventListener('DOMContentLoaded', function () {
    var button = document.getElementById('simplePopupButton');
    var modal = document.getElementById('simplePopupModal');

    if (!button || !modal) {
        return;
    }

    var closeButtons = modal.querySelectorAll('[data-simple-popup-close]');

    function openModal() {
        modal.hidden = false;
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('simplePopupModalOpen');
    }

    function closeModal() {
        modal.hidden = true;
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('simplePopupModalOpen');
        button.focus();
    }

    button.addEventListener('click', openModal);

    closeButtons.forEach(function (closeButton) {
        closeButton.addEventListener('click', closeModal);
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && !modal.hidden) {
            closeModal();
        }
    });
});
