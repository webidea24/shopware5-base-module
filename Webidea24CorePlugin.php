<?php

declare(strict_types=1);


namespace Webidea24CorePlugin;


use Enlight_Event_EventArgs;
use Shopware\Components\Plugin;
use Webidea24CorePlugin\Bootstrap\AbstractBootstrap;

class Webidea24CorePlugin extends Plugin
{
    public function install(Plugin\Context\InstallContext $context)
    {
        $instances = $this->getBootstrapInstances($context);
        foreach ($instances as $bootstrap) {
            $bootstrap->preInstall();
        }
        foreach ($instances as $bootstrap) {
            $bootstrap->install();
        }
        foreach ($instances as $bootstrap) {
            $bootstrap->postInstall();
        }
        parent::install($context);
    }

    public function update(Plugin\Context\UpdateContext $context)
    {
        $instances = $this->getBootstrapInstances($context);
        foreach ($instances as $bootstrap) {
            $bootstrap->preUpdate();
        }
        foreach ($instances as $bootstrap) {
            $bootstrap->update();
        }
        foreach ($instances as $bootstrap) {
            $bootstrap->postUpdate();
        }
        parent::update($context);
        $this->clearCache($context);
    }

    public function uninstall(Plugin\Context\UninstallContext $context)
    {
        $instances = $this->getBootstrapInstances($context);
        foreach ($instances as $bootstrap) {
            $bootstrap->preUninstall();
        }
        foreach ($instances as $bootstrap) {
            $bootstrap->uninstall($context->keepUserData());
        }
        foreach ($instances as $bootstrap) {
            $bootstrap->postUninstall();
        }
        parent::uninstall($context);
        $this->clearCache($context);
    }

    public function deactivate(Plugin\Context\DeactivateContext $context)
    {
        $instances = $this->getBootstrapInstances($context);
        foreach ($instances as $bootstrap) {
            $bootstrap->preDeactivate();
        }
        foreach ($instances as $bootstrap) {
            $bootstrap->deactivate();
        }
        foreach ($instances as $bootstrap) {
            $bootstrap->postDeactivate();
        }
        parent::deactivate($context);
        $this->clearCache($context);
    }

    public function activate(Plugin\Context\ActivateContext $context)
    {
        $instances = $this->getBootstrapInstances($context);
        foreach ($instances as $bootstrap) {
            $bootstrap->preActivate();
        }
        foreach ($instances as $bootstrap) {
            $bootstrap->activate();
        }
        foreach ($instances as $bootstrap) {
            $bootstrap->postActivate();
        }
        parent::activate($context);
        $this->clearCache($context);
    }

    protected function clearCache(Plugin\Context\InstallContext $context)
    {
        $context->scheduleClearCache($context::CACHE_LIST_ALL);
    }

    /**
     * @param Plugin\Context\InstallContext $context
     * @return AbstractBootstrap[]
     */
    private function getBootstrapInstances(Plugin\Context\InstallContext $context)
    {
        $instances = [];
        $classes = $this->getBootstrapClasses($context);
        foreach ($classes as $bootstrap) {
            /** @var AbstractBootstrap $bootstrap */
            $bootstrap = new $bootstrap;
            $bootstrap->setContext($context);
            $bootstrap->setContainer($this->container);
            $bootstrap->setPluginDir($this->getPath());
            $instances[] = $bootstrap;
        }
        return $instances;
    }

    /**
     * @param Plugin\Context\InstallContext $context
     * @return string[]
     */
    protected function getBootstrapClasses(Plugin\Context\InstallContext $context)
    {
        return [];
    }

    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PreDispatch' => 'registerTemplates'
        ];
    }

    public function registerTemplates(Enlight_Event_EventArgs $args)
    {
        Shopware()->Template()->addTemplateDir($this->getPath() . '/Resources/views');
    }
}
