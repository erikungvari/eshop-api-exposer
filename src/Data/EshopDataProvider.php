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

    public function getProduct(int $productId, ?string $locale = null): ?array
    {
        $query = $this->db->table('product')
            ->where('id', $productId);

        $langId = null;

        if ($locale) {
            $langRow = $this->db->table('settings_lang')
                ->where('name', $locale)
                ->fetch();

            if (!$langRow) {
                return null;
            }

            $langId = (int) $langRow->id;
            $query->where('lang', $langId);
        }

        $row = $query->fetch();

        if (!$row) {
            return null;
        }

        $product = $this->normalize([$row])[0];

        $product['images'] = $this->getProductImages($productId, $langId);

        return $product;
    }


    private function getProductImages(int $productId, ?int $langId): array
    {
        $galleryIds = $this->db->table('product_x_gallery')
            ->select('DISTINCT gallery_id')
            ->where('product_id', $productId)
            ->where($langId ? ['lang' => $langId] : [])
            ->fetchPairs(null, 'gallery_id');

        if (!$galleryIds) {
            return [];
        }

        $photos = $this->db->table('gallery_photo')
            ->where('gallery_id', $galleryIds)
            ->where('hidden', 0)
            ->group('id')
            ->order('position ASC')
            ->fetchAll();

        if (!$photos) {
            return [];
        }

        $settings = $this->db->table('settings_image')
            ->where('module_id', 1)
            ->fetch();

        if (!$settings) {
            return [];
        }

        $folders = explode(';', $settings->folder);
        $mask = $settings->mask;
        $format = $settings->format;
        $moduleId = $settings->module_id;

        $sizes = [];
        foreach ($folders as $folder) {
            $parts = explode('/', $folder);
            $size = end($parts);
            $sizes[$size] = $folder;
        }

        $result = [];
        foreach ($sizes as $size => $folder) {
            $result[$size] = [];
        }

        foreach ($photos as $photo) {
            foreach ($sizes as $size => $folder) {
                $result[$size][] = sprintf(
                    '/userfiles/%s/%s-%d-%d.%s',
                    $folder,
                    $mask,
                    $photo->id,
                    $moduleId,
                    $format
                );
            }
        }

        return $result;
    }
}
