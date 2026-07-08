<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Permanent fallback when a product has no uploaded image.
 * This file is not temporary — it stays as the default "no photo" asset.
 */
define('PRODUCT_PLACEHOLDER_PATH', 'assets/images/product-placeholder.svg');

/**
 * URL for the product placeholder image.
 */
function product_placeholder_url()
{
    return base_url(PRODUCT_PLACEHOLDER_PATH);
}

/**
 * Resolve the display image URL for a product.
 *
 * Priority:
 * 1. Product image from the database (e.g. products.image or a product_images table)
 * 2. Permanent placeholder SVG when no image is stored
 *
 * Views should always call this helper — never hardcode image paths.
 *
 * @param object $product
 * @return string
 */
function product_image_url($product)
{
    if (!empty($product->image)) {
        return base_url('uploads/products/' . ltrim($product->image, '/'));
    }

    return product_placeholder_url();
}