<?php

namespace Czechgroup\EshopApiExposer\Data;

use Nette\Database\Explorer;

final class EshopDataProvider implements DataProvider
{
    public function __construct(
        private Explorer $db,
    ) {}
    private function normalize(iterable $rows): array
    {
        $out = [];
        foreach ($rows as $row) {
            $item = [];
            foreach ($row->toArray() as $k => $v) {
                if ($v instanceof \DateTimeInterface) {
                    $item[$k] = $v->format('c');
                } else {
                    $item[$k] = $v;
                }
            }
            $out[] = $item;
        }
        return $out;
    }
    public function getProducts(?string $locale = null): array
    {
        $products = $this->db->table('product')
            ->select('id, lang, name, price, stock, text');

        if ($locale) {
            $langRow = $this->db->table('settings_lang')->where('name', $locale)->fetch();
            if (!$langRow) return [];
            $products->where('lang', (int) $langRow->id);
        }

        return $this->normalize($products);
    }



}
