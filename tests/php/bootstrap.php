<?php

namespace {
    define('PKP_STRICT_MODE', false);
}

namespace APP\core {
    class Application
    {
        public static bool $underMaintenance = false;
        public static ?object $appInstance = null;

        public static function isUnderMaintenance(): bool
        {
            return self::$underMaintenance;
        }

        public static function get(): object
        {
            return self::$appInstance ?? new class {
                public function getRequest(): object
                {
                    return new class {
                        public function getBaseUrl(): string
                        {
                            return 'http://localhost:8080';
                        }
                    };
                }
            };
        }
    }
}

namespace PKP\plugins {
    class GenericPlugin
    {
        public bool $enabled = true;
        public string $pluginPath = 'plugins/generic/simplePopupButton';

        public function register($category, $path, $mainContextId = null)
        {
            return true;
        }

        public function getEnabled($mainContextId = null): bool
        {
            return $this->enabled;
        }

        public function getPluginPath(): string
        {
            return $this->pluginPath;
        }

        public function getTemplateResource(string $template): string
        {
            return $template;
        }
    }

    class Hook
    {
        public static array $calls = [];

        public static function add(string $hookName, $callback): void
        {
            self::$calls[] = [$hookName, $callback];
        }
    }
}

namespace {
    require_once __DIR__ . '/../../plugins/generic/simplePopupButton/SimplePopupButtonPlugin.php';
}
