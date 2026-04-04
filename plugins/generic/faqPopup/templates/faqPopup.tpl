<button
    type="button"
    id="faqPopup"
    class="faqPopup"
    aria-haspopup="dialog"
    aria-controls="faqPopupModal"
>
    {$faqPopupLabel|escape}
</button>

<div
    id="faqPopupModal"
    class="faqPopupModal"
    hidden
    aria-hidden="true"
>
    <div class="faqPopupModal__backdrop" data-faq-popup-close></div>
    <div
        class="faqPopupModal__dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="faqPopupModalTitle"
    >
        <button
            type="button"
            class="faqPopupModal__close"
            aria-label="Close popup"
            data-faq-popup-close
        >
            x
        </button>
        <h2 id="faqPopupModalTitle" class="faqPopupModal__title">
            {$faqPopupModalTitle|escape}
        </h2>
        <p class="faqPopupModal__body">
            {$faqPopupModalBody|escape}
        </p>
        <div class="faqPopupModal__questions">
            {foreach from=$faqPopupQuestions item=question}
                <button type="button" class="faqPopupModal__question" data-answer="{$question.answer|escape:'html'}">
                    {$question.question|escape}
                </button>
            {/foreach}
        </div>
        <div class="faqPopupModal__answer" id="faqPopupAnswer">
            {$faqPopupDefaultAnswer|escape}
        </div>
    </div>
</div>
