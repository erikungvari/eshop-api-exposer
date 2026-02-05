<?php

namespace Czechgroup\EshopApiExposer\Data;

interface DataProvider
{
    public function getProducts(?string $locale = null): array;
}


