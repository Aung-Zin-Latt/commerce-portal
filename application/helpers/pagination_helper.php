<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Normalize page/per_page and compute offset + total_pages.
 *
 * @param int $total
 * @param int $page
 * @param int $perPage
 * @param int $maxPerPage
 * @return array{total:int,page:int,per_page:int,total_pages:int,offset:int}
 */
function pagination_prepare($total, $page = 1, $perPage = 10, $maxPerPage = 100)
{
    $total = max(0, (int) $total);
    $perPage = max(1, min((int) $maxPerPage, (int) $perPage));
    $totalPages = $total > 0 ? (int) ceil($total / $perPage) : 1;
    $page = max(1, (int) $page);

    if ($page > $totalPages) {
        $page = $totalPages;
    }

    return array(
        'total' => $total,
        'page' => $page,
        'per_page' => $perPage,
        'total_pages' => $totalPages,
        'offset' => ($page - 1) * $perPage,
    );
}

/**
 * Build a standard paginated list payload.
 *
 * @param string $itemsKey
 * @param array $items
 * @param array $meta from pagination_prepare()
 * @return array
 */
function pagination_result($itemsKey, array $items, array $meta)
{
    return array(
        $itemsKey => $items,
        'total' => $meta['total'],
        'page' => $meta['page'],
        'per_page' => $meta['per_page'],
        'total_pages' => $meta['total_pages'],
    );
}