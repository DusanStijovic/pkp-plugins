<?php

namespace APP\plugins\generic\faqPopup;

use APP\core\Application;
use PKP\plugins\GenericPlugin;
use PKP\plugins\Hook;

class FAQPopupPlugin extends GenericPlugin
{
    /** @var bool Prevent duplicate output filter registration in one request */
    protected $outputFilterRegistered = false;

    public function getDisplayName()
    {
        return __('plugins.generic.faqPopup.displayName');
    }

    public function getDescription()
    {
        return __('plugins.generic.faqPopup.description');
    }

    public function register($category, $path, $mainContextId = null)
    {
        if (!parent::register($category, $path, $mainContextId)) {
            return false;
        }

        if (Application::isUnderMaintenance()) {
            return true;
        }

        Hook::add('TemplateManager::display', [$this, 'handleTemplateDisplay']);

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

        $context = method_exists($request, 'getContext') ? $request->getContext() : null;
        if (is_object($context) && method_exists($context, 'getId')) {
            if (!$this->getEnabled($context->getId())) {
                return false;
            }
        } elseif (!$this->getEnabled()) {
            return false;
        }

        $pluginBaseUrl = $request->getBaseUrl() . '/' . $this->getPluginPath();

        $templateMgr->addStyleSheet(
            'faqPopupStyles',
            $pluginBaseUrl . '/styles/faqPopup.css',
            [
                'contexts' => ['frontend'],
                'inline' => false,
            ]
        );

        $templateMgr->addJavaScript(
            'faqPopupScript',
            $pluginBaseUrl . '/js/faqPopup.js',
            [
                'contexts' => ['frontend'],
                'inline' => false,
            ]
        );

        $questions = [];
        foreach ($this->getQuestionIndexes() as $i) {
            $questionKey = "plugins.generic.faqPopup.faq.question.$i";
            $answerKey = "plugins.generic.faqPopup.faq.answer.$i";

            $questionText = __($questionKey);
            $answerText = __($answerKey);

            if ($questionText === $questionKey || $answerText === $answerKey) {
                continue;
            }

            $questions[] = [
                'question' => $questionText,
                'answer' => $answerText,
            ];
        }

        $templateMgr->assign([
            'faqPopupLabel' => __('plugins.generic.faqPopup.quickQuestions'),
            'faqPopupModalTitle' => __('plugins.generic.faqPopup.faqTitle'),
            'faqPopupModalBody' => __('plugins.generic.faqPopup.faqBody'),
            'faqPopupDefaultAnswer' => __('plugins.generic.faqPopup.defaultAnswer'),
            'faqPopupQuestions' => $questions,
        ]);

        if (!$this->outputFilterRegistered) {
            $templateMgr->registerFilter('output', [$this, 'injectPopupMarkup']);
            $this->outputFilterRegistered = true;
        }

        return false;
    }

    protected function getQuestionIndexes()
    {
        $configured = $this->getConfiguredQuestionIndexes();
        if (!empty($configured)) {
            return $configured;
        }

        $questionIndexes = [];
        $answerIndexes = [];
        $localeFiles = glob(dirname(__FILE__) . '/locale/*/locale.po');

        if (is_array($localeFiles)) {
            foreach ($localeFiles as $localeFile) {
                if (!is_readable($localeFile)) {
                    continue;
                }

                $content = file_get_contents($localeFile);
                if ($content === false) {
                    continue;
                }

                if (preg_match_all('/msgid\\s+"plugins\\.generic\\.faqPopup\\.faq\\.question\\.(\\d+)"/', $content, $questionMatches)) {
                    foreach ($questionMatches[1] as $index) {
                        $questionIndexes[(int) $index] = true;
                    }
                }

                if (preg_match_all('/msgid\\s+"plugins\\.generic\\.faqPopup\\.faq\\.answer\\.(\\d+)"/', $content, $answerMatches)) {
                    foreach ($answerMatches[1] as $index) {
                        $answerIndexes[(int) $index] = true;
                    }
                }
            }
        }

        if (empty($questionIndexes) || empty($answerIndexes)) {
            return [];
        }

        $indexes = [];
        foreach (array_keys($questionIndexes) as $index) {
            if (isset($answerIndexes[$index])) {
                $indexes[] = $index;
            }
        }

        sort($indexes, SORT_NUMERIC);
        return $indexes;
    }

    protected function getConfiguredQuestionIndexes()
    {
        $configFile = dirname(__FILE__) . '/config.ini';
        if (!file_exists($configFile)) {
            return [];
        }

        $config = parse_ini_file($configFile);
        if (!is_array($config) || empty($config['questionIndexes'])) {
            return [];
        }

        $rawIndexes = array_map('trim', explode(',', (string) $config['questionIndexes']));
        $indexes = [];
        $seen = [];
        foreach ($rawIndexes as $index) {
            if ($index !== '' && ctype_digit($index) && (int) $index > 0) {
                $numericIndex = (int) $index;
                if (!isset($seen[$numericIndex])) {
                    $seen[$numericIndex] = true;
                    $indexes[] = $numericIndex;
                }
            }
        }

        return $indexes;
    }

    public function injectPopupMarkup($output, $templateMgr)
    {
        static $alreadyInjected = false;
        if ($alreadyInjected) {
            return $output;
        }

        if (strpos($output, 'id="faqPopup"') !== false) {
            $alreadyInjected = true;
            return $output;
        }

        $popupMarkup = $templateMgr->fetch($this->getTemplateResource('faqPopup.tpl'));
        $alreadyInjected = true;

        if (strpos($output, '</body>') !== false) {
            return str_replace('</body>', $popupMarkup . "\n</body>", $output);
        }

        return $output . $popupMarkup;
    }
}

if (!PKP_STRICT_MODE) {
    class_alias('\APP\plugins\generic\faqPopup\FAQPopupPlugin', '\FAQPopupPlugin');
}
