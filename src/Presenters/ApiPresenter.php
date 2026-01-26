<?php

namespace Czechgroup\EshopApiExposer\Presenters;

use Czechgroup\EshopApiExposer\Data\DataProvider;
use Czechgroup\EshopApiExposer\Security\RequestAuthenticator;
use Nette\Application\Responses\JsonResponse;
use Nette\Application\UI\Presenter;

final class ApiPresenter extends Presenter
{
    public function __construct(
        private RequestAuthenticator $authenticator,
        private DataProvider $dataProvider,
    ) {}

    protected function startup(): void
    {
        parent::startup();
        $this->authenticator->authenticate($this->getHttpRequest());
    }

    public function actionProducts(): void
    {
        $this->sendResponse(new JsonResponse($this->dataProvider->getProducts()));
    }

}
