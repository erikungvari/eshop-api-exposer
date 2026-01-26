<?php

namespace Czechgroup\EshopApiExposer\Data;

interface DataProvider
{
    public function getProducts(): array;
}
