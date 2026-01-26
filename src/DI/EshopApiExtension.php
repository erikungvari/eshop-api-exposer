<?php

namespace Czechgroup\EshopApiExposer\DI;

use Czechgroup\EshopApiExposer\Data\DataProvider;
use Czechgroup\EshopApiExposer\Data\DefaultDataProvider;
use Czechgroup\EshopApiExposer\Data\EshopDataProvider;
use Czechgroup\EshopApiExposer\Security\RequestAuthenticator;
use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;

final class EshopApiExtension extends CompilerExtension
{
    public function getConfigSchema(): \Nette\Schema\Schema
    {
        return Expect::structure([
            'apiKey' => Expect::string()->required(),
            'apiSecret' => Expect::string()->required(),
        ]);
    }

    public function loadConfiguration(): void
    {
        $builder = $this->getContainerBuilder();
        $config = $this->getConfig();

        $builder->addDefinition($this->prefix('authenticator'))
            ->setFactory(RequestAuthenticator::class, [$config->apiKey, $config->apiSecret]);

        $builder->addDefinition($this->prefix('dataProvider'))
            ->setType(DataProvider::class)
            ->setFactory(EshopDataProvider::class);
    }

    public function beforeCompile(): void
    {
        $this->getContainerBuilder()
            ->getDefinition('application.presenterFactory')
            ->addSetup('setMapping', [['EshopApi' => 'Czechgroup\\EshopApiExposer\\Presenters\\*Presenter']]);
    }
}
