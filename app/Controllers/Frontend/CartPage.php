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
        self::setPrice();
        self::updateQuantity();
    }

    /**
     * Set free product price at zero
     * @return void
     */
    public function setPrice() {
        $items = WC()->cart->get_cart();
        foreach($items as $item) {
            if (isset($item['free_product'])) {
                $item[ 'data' ]->set_price( 0 );
            }
        }
    }


    /**
     * Set free product quantity
     * @return void
     */
    public function updateQuantity() {
        $items = WC()->cart->get_cart();
        foreach($items as $item) {
            $item_id = $item['product_id'];
            $product_data = get_post_meta($item_id, "is_buy_get_enabled", true );
            if ($product_data == "yes" ) {
                if(!isset($item['free_product'])) {
                    $item_quantity = $item['quantity'];
                    $current_product_key = $item['key'];
                }
                if(isset($item['free_product'])) {
                    foreach ($items as $get_item) {
                        $free_item_quantity = $get_item['quantity'];
                        if (isset($get_item['parent_key']) && $get_item['parent_key'] == $current_product_key) {
                            if($item_quantity != $free_item_quantity){
                                WC()->cart->set_quantity( $item['key'], $item_quantity, true);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * After Calculate Totals
     * @return void
     */
    public function afterCalculateTotals() {
        self::addProduct();
        self::removeProduct();
    }

    /**
     * Add free product to cart
     * @return void
     * @throws Exception
     */
    public function addProduct() {
        $items = WC()->cart->get_cart();
        foreach($items as $item) {
            if (isset($item['free_product'])) {
                continue;
            }
            $item_id = $item['product_id'];
            $product_data = get_post_meta($item_id, "is_buy_get_enabled", true );
            if ($product_data == "yes") {
                $current_product_key = $item['key'];
                foreach($items as $get_item) {
                    if(isset($get_item['parent_key']) && $get_item['parent_key'] == $current_product_key) {
                        continue 2;
                    }
                }
                $item_quantity=$item['quantity'];
                WC()->cart->add_to_cart(
                    $item_id,
                    $item_quantity,
                    0,
                    array(),
                    array(
                        'free_product'  => true,
                        'parent_key'    => $current_product_key
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
        $items = WC()->cart->get_cart();
        foreach($items as $item) {
            if (isset($item['free_product']) && $item['free_product'] == true) {
                if(isset($item['parent_key'])) {
                    $parent_key = $item['parent_key'];
                    $child_key = $item['key'];
                    $item_id = $item['product_id'];
                    $product_data = get_post_meta($item_id, "is_buy_get_enabled", true );
                    if ($product_data == "yes") {
                        foreach($items as $remove_item) {
                            if($remove_item['key'] == $parent_key) {
                                continue 2;
                            }
                        }
                    }
                    WC()->cart->remove_cart_item($child_key);
                }
            }
        }
    }
}
?>