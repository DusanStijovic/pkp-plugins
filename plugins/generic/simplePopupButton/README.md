# Simple Popup Button Plugin

Minimal generic OJS plugin that adds a frontend button and opens a popup modal.

## Files

- `SimplePopupButtonPlugin.php`: plugin class and hook registration
- `templates/popupButton.tpl`: button and modal markup
- `styles/popupButton.css`: frontend styles
- `js/popupButton.js`: popup behavior

## Enable

1. Restart the `app` container so OJS sees the new mounted plugin.
2. In OJS Admin, go to `Settings > Website > Plugins`.
3. Find `Simple Popup Button` and enable it.
