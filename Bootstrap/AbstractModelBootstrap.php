<?php

namespace Webidea24CorePlugin\Bootstrap;

use Doctrine\ORM\Tools\SchemaTool;

abstract class AbstractModelBootstrap extends AbstractBootstrap
{

    /**
     * @var SchemaTool
     */
    private $schemaTool;

    public function setContainer($container)
    {
        parent::setContainer($container);
        $this->schemaTool = new SchemaTool($this->modelManager);
    }

    /**
     * @return array
     */
    abstract protected function getModelClasses();

    public function install()
    {
        $this->schemaTool->updateSchema($this->getClassesMetaData(), true);
    }

    public function uninstall($keepUserData = false)
    {
        if (!$keepUserData) {
            $this->schemaTool->dropSchema($this->getClassesMetaData());
        }
    }

    private function getClassesMetaData()
    {
        $modelManager = $this->modelManager;
        return array_map(static function ($modelClass) use ($modelManager) {
            return $modelManager->getClassMetadata($modelClass);
        }, $this->getModelClasses());
    }

    public function update()
    {
        $this->install();
    }

    public function activate()
    {
    }

    public function deactivate()
    {
    }
}
