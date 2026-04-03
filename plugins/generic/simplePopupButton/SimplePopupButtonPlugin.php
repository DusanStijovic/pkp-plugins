<?php

namespace APP\plugins\generic\simplePopupButton;

use APP\core\Application;
use PKP\plugins\GenericPlugin;
use PKP\plugins\Hook;

class SimplePopupButtonPlugin extends GenericPlugin
{
    public function getDisplayName()
    {
        return 'Simple Popup Button';
    }

    public function getDescription()
    {
        return 'Adds a frontend button that opens a popup modal.';
    }

    public function register($category, $path, $mainContextId = null)
    {
        if (!parent::register($category, $path, $mainContextId)) {
            return false;
        }

        if (Application::isUnderMaintenance()) {
            return true;
        }

        if ($this->getEnabled($mainContextId)) {
            Hook::add('TemplateManager::display', [$this, 'handleTemplateDisplay']);
        }

        return true;
    }

    public function handleTemplateDisplay($hookName, $args)
    {
        $templateMgr = $args[0];
        $template = $args[1];
        $request = Application::get()->getRequest();

        if (!is_string($template) || strpos($template, 'frontend/') !== 0) {
            return false;
        }

        $pluginBaseUrl = $request->getBaseUrl() . '/' . $this->getPluginPath();

        $templateMgr->addStyleSheet(
            'simplePopupButtonStyles',
            $pluginBaseUrl . '/styles/popupButton.css',
            [
                'contexts' => ['frontend'],
                'inline' => false,
            ]
        );

        $templateMgr->addJavaScript(
            'simplePopupButtonScript',
            $pluginBaseUrl . '/js/popupButton.js',
            [
                'contexts' => ['frontend'],
                'inline' => false,
            ]
        );

        $templateMgr->assign([
            'simplePopupButtonLabel' => 'Open Popup',
            'simplePopupModalTitle' => 'Simple Popup',
            'simplePopupModalBody' => 'This popup comes from a custom generic OJS plugin.',
        ]);

        $templateMgr->registerFilter('output', [$this, 'injectPopupMarkup']);

        return false;
    }

    public function injectPopupMarkup($output, $templateMgr)
    {
        if (strpos($output, 'simplePopupButton') !== false) {
            return $output;
        }

        $popupMarkup = $templateMgr->fetch($this->getTemplateResource('popupButton.tpl'));

        if (strpos($output, '</body>') !== false) {
            return str_replace('</body>', $popupMarkup . "\n</body>", $output);
        }

        return $output . $popupMarkup;
    }
}

if (!PKP_STRICT_MODE) {
    class_alias('\APP\plugins\generic\simplePopupButton\SimplePopupButtonPlugin', '\SimplePopupButtonPlugin');
}
