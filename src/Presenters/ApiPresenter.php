<?php

namespace Czechgroup\EshopApiExposer\Presenters;

use Czechgroup\EshopApiExposer\Data\DataProvider;
use Czechgroup\EshopApiExposer\Security\RequestAuthenticator;
use JetBrains\PhpStorm\NoReturn;
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

    #[NoReturn]
    public function actionProducts(?int $productId = null): void
    {
        $locale = $this->getParameter('locale');

        if ($productId !== null) {
            $product = $this->dataProvider->getProduct($productId, $locale);
            if (!$product) {
                $this->error('Product not found', 404);
            }
            $this->sendResponse(new JsonResponse($product));
        }

        $this->sendResponse(new JsonResponse(
            $this->dataProvider->getProducts($locale)
        ));
    }


}
