<?php
/**
 * Copyright (c) 2020 WEBiDEA
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webidea24CorePlugin\Bootstrap;


use Shopware\Bundle\AttributeBundle\Service\CrudService;

abstract class AbstractAttributeBootstrap extends AbstractBootstrap
{

    /**
     * @var CrudService
     */
    protected $crudService;

    /**
     * @return array
     */
    abstract protected function getTables();

    final public function install()
    {
        $this->installAttributes();
        $this->cleanUp();
    }

    public function update()
    {
        $this->installAttributes();
        $this->cleanUp();
    }

    final public function uninstall($keepUserData = false)
    {
        if ($keepUserData === false) {
            $this->uninstallAttributes();
            $this->cleanUp();
        }
    }

    public function setContainer($container)
    {
        parent::setContainer($container);
        $this->crudService = $this->container->get('shopware_attribute.crud_service');
    }

    private function cleanUp()
    {
        $metaDataCache = $this->modelManager->getConfiguration()->getMetadataCacheImpl();
        $metaDataCache->deleteAll();
        $this->modelManager->generateAttributeModels($this->getTables());
    }

    abstract protected function installAttributes();

    abstract protected function uninstallAttributes();

    public function activate()
    {
        // do nothing
    }

    public function deactivate()
    {
        // do nothing
    }
}
