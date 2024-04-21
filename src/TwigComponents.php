<?php

namespace juni\twigcomponents;

use Craft;
use craft\base\Event;
use craft\base\Model;
use craft\base\Plugin;
use craft\events\CreateTwigEvent;
use craft\web\View;
use juni\twigcomponents\models\Settings;
use Performing\TwigComponents as PerformingTwigComponents;

/**
 * TwigComponents plugin
 *
 * @method static TwigComponents getInstance()
 * @method Settings getSettings()
 * @author Jurjen Nieuwenhuis <jurjennieuwenhuis@gmail.com>
 * @copyright Jurjen Nieuwenhuis
 * @license MIT
 */
class TwigComponents extends Plugin
{
    public string $schemaVersion = '1.0.0';
    public bool $hasCpSettings = true;

    public function init(): void
    {
        parent::init();

        // Defer most setup tasks until Craft is fully initialized
        Craft::$app->onInit(function() {
            $this->attachEventHandlers();
        });
    }

    protected function createSettingsModel(): ?Model
    {
        return Craft::createObject(Settings::class);
    }

    protected function settingsHtml(): ?string
    {
        return Craft::$app->view->renderTemplate('twig-components/_settings.twig', [
            'plugin' => $this,
            'settings' => $this->getSettings(),
        ]);
    }

    private function attachEventHandlers(): void
    {
        Event::on(
            View::class,
            View::EVENT_AFTER_CREATE_TWIG,
            function (CreateTwigEvent $event) {

                // Only use on front end
                if (!Craft::$app->getRequest()->getIsSiteRequest()) {
                    return;
                }

                $componentPath = Craft::getAlias($this->getSettings()->getAttributes()['componentDir']);

                PerformingTwigComponents\Configuration::make($event->twig)
                    ->setTemplatesPath($componentPath)
                    ->useCustomTags()
                    ->setup();
            }
        );
    }
}
