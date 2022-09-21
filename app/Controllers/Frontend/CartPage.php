<?php
/**
 * Buy X Get X Offer
 *
 * @package   buy-get
 * @author    Yegappan
 * @copyright 2022 Flycart
 * @license   GPL v2 or later
 * @link      https://flycart.org
 */

namespace BXGX\App\Controllers\Frontend;

defined( 'ABSPATH' ) || exit;

class CartPage
{
    /**
     *
     * Change free products quantity
     *
     * @param $product_quantity
     *
     * @param $cart_item_key
     *
     * @param $cart_item
     *
     * @return mixed|string
     *
     */
    public function removeQuantityOption( $product_quantity , $cart_item_key , $cart_item ) {
        if(isset($cart_item['free_product']) && ($cart_item['free_product'] == true)) {
            $product_quantity = sprintf('%2$s <input type="hidden" name="cart[%1$s][qty]" value="%2$s" />', $cart_item_key, $cart_item['quantity']);
        }
        return $product_quantity;
    }

    /**
     *
     * Remove free products remove btn(link)
     *
     * @param $sprintf
     *
     * @param $cart_item_key
     *
     * @return mixed|string
     *
     */
    public function removeDeleteOption( $sprintf , $cart_item_key ) {
        $cart_item=WC()->cart->get_cart_item( $cart_item_key );
        if (isset($cart_item['free_product'])) {
            $sprintf = '';
        }
        return $sprintf;
    }
}
?>