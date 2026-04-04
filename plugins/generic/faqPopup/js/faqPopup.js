document.addEventListener('DOMContentLoaded', function () {
    var button = document.getElementById('faqPopup');
    var modal = document.getElementById('faqPopupModal');

    if (!button || !modal) {
        return;
    }

    var closeButtons = modal.querySelectorAll('[data-faq-popup-close]');

    function openModal() {
        modal.hidden = false;
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('faqPopupModalOpen');
    }

    function closeModal() {
        modal.hidden = true;
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('faqPopupModalOpen');
        button.focus();
    }

    button.addEventListener('click', openModal);

    closeButtons.forEach(function (closeButton) {
        closeButton.addEventListener('click', closeModal);
    });

    var answerBox = modal.querySelector('#faqPopupAnswer');
    var questionButtons = modal.querySelectorAll('.faqPopupModal__question');

    questionButtons.forEach(function (questionButton) {
        questionButton.addEventListener('click', function () {
            var answerText = questionButton.getAttribute('data-answer');
            if (answerBox) {
                answerBox.textContent = answerText;
            }
        });
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && !modal.hidden) {
            closeModal();
        }
    });
});
