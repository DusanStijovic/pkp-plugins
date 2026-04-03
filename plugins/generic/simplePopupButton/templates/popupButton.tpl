<button
    type="button"
    id="simplePopupButton"
    class="simplePopupButton"
    aria-haspopup="dialog"
    aria-controls="simplePopupModal"
>
    {$simplePopupButtonLabel|escape}
</button>

<div
    id="simplePopupModal"
    class="simplePopupModal"
    hidden
    aria-hidden="true"
>
    <div class="simplePopupModal__backdrop" data-simple-popup-close></div>
    <div
        class="simplePopupModal__dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="simplePopupModalTitle"
    >
        <button
            type="button"
            class="simplePopupModal__close"
            aria-label="Close popup"
            data-simple-popup-close
        >
            x
        </button>
        <h2 id="simplePopupModalTitle" class="simplePopupModal__title">
            {$simplePopupModalTitle|escape}
        </h2>
        <p class="simplePopupModal__body">
            {$simplePopupModalBody|escape}
        </p>
    </div>
</div>
