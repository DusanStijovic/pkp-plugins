<?php

use APP\plugins\generic\simplePopupButton\SimplePopupButtonPlugin;
use PKP\plugins\Hook;
use PHPUnit\Framework\TestCase;

final class SimplePopupButtonPluginTest extends TestCase
{
    protected function setUp(): void
    {
        Hook::$calls = [];
    }

    public function testRegisterAddsTemplateDisplayHookWhenEnabled(): void
    {
        $plugin = new SimplePopupButtonPlugin();
        $plugin->enabled = true;

        $registered = $plugin->register('generic', 'simplePopupButton');

        $this->assertTrue($registered);
        $this->assertCount(1, Hook::$calls);
        $this->assertSame('TemplateManager::display', Hook::$calls[0][0]);
    }

    public function testHandleTemplateDisplayLoadsAssetsAndTemplateVars(): void
    {
        $plugin = new SimplePopupButtonPlugin();
        $templateManager = new FakeTemplateManager();

        $result = $plugin->handleTemplateDisplay('TemplateManager::display', [$templateManager, 'frontend/pages/index.tpl']);

        $this->assertFalse($result);
        $this->assertSame('http://localhost:8080/plugins/generic/simplePopupButton/styles/popupButton.css', $templateManager->styleSheets[0]['path']);
        $this->assertSame('http://localhost:8080/plugins/generic/simplePopupButton/js/popupButton.js', $templateManager->javaScripts[0]['path']);
        $this->assertSame('Open Popup', $templateManager->assigned['simplePopupButtonLabel']);
        $this->assertCount(1, $templateManager->filters);
    }

    public function testInjectPopupMarkupInsertsBeforeClosingBody(): void
    {
        $plugin = new SimplePopupButtonPlugin();
        $templateManager = new FakeTemplateManager();

        $output = $plugin->injectPopupMarkup('<html><body><p>Page</p></body></html>', $templateManager);

        $this->assertStringContainsString('<div id="fixture-popup">Popup</div>', $output);
        $this->assertStringContainsString("<div id=\"fixture-popup\">Popup</div>\n</body>", $output);
    }

    public function testInjectPopupMarkupSkipsWhenMarkupAlreadyExists(): void
    {
        $plugin = new SimplePopupButtonPlugin();
        $templateManager = new FakeTemplateManager();

        $output = $plugin->injectPopupMarkup('<html><body>simplePopupButton</body></html>', $templateManager);

        $this->assertSame('<html><body>simplePopupButton</body></html>', $output);
    }
}

final class FakeTemplateManager
{
    public array $styleSheets = [];
    public array $javaScripts = [];
    public array $assigned = [];
    public array $filters = [];

    public function addStyleSheet(string $name, string $path, array $args = []): void
    {
        $this->styleSheets[] = ['name' => $name, 'path' => $path, 'args' => $args];
    }

    public function addJavaScript(string $name, string $path, array $args = []): void
    {
        $this->javaScripts[] = ['name' => $name, 'path' => $path, 'args' => $args];
    }

    public function assign(array $data): void
    {
        $this->assigned = array_merge($this->assigned, $data);
    }

    public function registerFilter(string $type, $callback): void
    {
        $this->filters[] = [$type, $callback];
    }

    public function fetch(string $template): string
    {
        return '<div id="fixture-popup">Popup</div>';
    }
}
