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
     * Change free products quantity
     * @param $product_quantity
     * @param $cart_item_key
     * @param $cart_item
     * @return mixed|string
     */
    public function removeQuantityOption( $product_quantity, $cart_item_key, $cart_item ) {
        if(isset($cart_item['free_product']) && ($cart_item['free_product'] == true)) {
            $product_quantity = sprintf('%2$s <input type="hidden" name="cart[%1$s][qty]" value="%2$s" />', $cart_item_key, $cart_item['quantity']);
        }
        return $product_quantity;
    }

    /**
     * Remove free products remove btn(link)
     * @param $sprintf
     * @param $cart_item_key
     * @return mixed|string
     */
    public function removeDeleteOption( $sprintf, $cart_item_key ) {
        $cart_item = WC()->cart->get_cart_item( $cart_item_key );
        if (isset($cart_item['free_product'])) {
            $sprintf = '';
        }
        return $sprintf;
    }

    /**
     * Before Calculate Totals
     * @return void
     */
    public function beforeCalculateTotals() {
        $this->setPrice();
        $this->updateQuantity();
    }

    /**
     * Set free product price at zero
     * @return void
     */
    public function setPrice() {
        foreach(WC()->cart->get_cart() as $free_item) {
            if (!isset($free_item['free_product'])) continue;
            $free_item[ 'data' ]->set_price( 0 );  
        }
    }


    /**
     * Set free product quantity
     * @return void
     */
    public function updateQuantity() {
        foreach(WC()->cart->get_cart() as $free_item) {
            if(!isset($free_item['free_product'])) continue;
            foreach (WC()->cart->get_cart() as $parent_item) {
                if ($free_item['parent_key'] == $parent_item['key'] && $free_item['quantity'] != $parent_item['quantity']) {
                    WC()->cart->set_quantity($free_item['key'], $parent_item['quantity'], true);
                }
            }
        }
    }

    /**
     * After Calculate Totals
     * @return void
     */
    public function afterCalculateTotals() {
        $this->addProduct();
        $this->removeProduct();
    }

    /**
     * Add free product to cart
     * @return void
     * @throws Exception
     */
    public function addProduct() {
        foreach(WC()->cart->get_cart() as $item) {
            if (isset($item['free_product'])) continue;
            $product_data = get_post_meta($item['product_id'], "is_buy_get_enabled", true );
            if ($product_data == "yes") {
                foreach(WC()->cart->get_cart() as $get_item) {
                    if(isset($get_item['parent_key']) && $get_item['parent_key'] == $item['key']) continue 2;
                }
                WC()->cart->add_to_cart(
                    $item['product_id'],
                    $item['quantity'],
                    0,
                    array(),
                    array(
                        'free_product'  => true,
                        'parent_key'    => $item['key']
                    )
                );
            }
        }
    }


    /**
     * Remove free product if actual product not in cart
     * @return void
     */
    public function removeProduct() {
        foreach(WC()->cart->get_cart() as $item) {
            if (isset($item['free_product'])) {
                foreach(WC()->cart->get_cart() as $remove_item) {
                    if($remove_item['key'] == $item['parent_key']) continue 2;
                }
                WC()->cart->remove_cart_item($item['key']);
            }
        }
    }
}