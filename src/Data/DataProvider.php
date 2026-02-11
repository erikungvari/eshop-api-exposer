<?php

namespace Czechgroup\EshopApiExposer\Data;

interface DataProvider
{
    public function getProducts(?string $locale = null): array;
    public function getProduct(int $productId, ?string $locale = null): ?array;
}


