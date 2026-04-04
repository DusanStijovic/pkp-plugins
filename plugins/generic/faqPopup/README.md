# Quick Questions Popup Plugin

Generic OJS plugin that adds a frontend FAQ button and modal with question/answer items.

## What It Does

- Adds a floating FAQ button on frontend pages.
- Opens a modal with a list of questions.
- Shows the selected answer inside the same modal.

## Enable In OJS

1. Restart the `app` container so OJS detects plugin changes.
2. In OJS Admin, go to `Settings > Website > Plugins`.
3. Find `Quick Questions Popup` and enable it.
4. In that same plugin list, plugin description now also includes short usage guidance.

## How To Edit Questions

1. Edit locale files:
   - `locale/en_US/locale.po`
   - `locale/sr_RS/locale.po`
2. Add or update pairs with keys:
   - `plugins.generic.faqPopup.faq.question.X`
   - `plugins.generic.faqPopup.faq.answer.X`
3. Keep question and answer with the same index `X`.

## Question Order

- Default behavior: plugin auto-detects available indexes from locale files and displays them in numeric order (`1,2,3,...`).
- Optional override: set `questionIndexes` in `config.ini`.
- Example:
  - `questionIndexes = 3,1,4`
  - This order is respected exactly as written.

## Files

- `FAQPopupPlugin.php`: plugin class and hook registration
- `templates/faqPopup.tpl`: button and modal markup
- `styles/faqPopup.css`: frontend styles
- `js/faqPopup.js`: popup behavior
- `config.ini`: optional `questionIndexes` override
- `locale/*/locale.po`: translated labels and FAQ items
