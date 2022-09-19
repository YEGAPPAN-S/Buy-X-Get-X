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

namespace BXGX\App\Controllers\Admin;

defined( 'ABSPATH' ) || exit;

class Controller
{
    /**
     *
     * Save meta data
     *
     * @param $post_id
     *
     * @return void
     *
     */
    public function checkPost( $post_id ) {
        if(isset($_POST['Buy_X_Get_X_enable'])) {
            update_post_meta( $post_id, 'is_enable_buy_get', sanitize_text_field($_POST['is_enable_buy_get']) );
        }
    }

    /**
     *
     * Set free product price at zero
     *
     * @return void
     *
     */
    public function updatePrice() {
        $items = WC()->cart->get_cart();
        foreach($items as $item) {
            if (isset($item['free_product'])) {
                $item[ 'data' ]->set_price( 0 );
            }
        }
    }


    /**
     *
     * Set free product quantity
     *
     * @return void
     *
     */
    public function updateQuantity() {
        $items = WC()->cart->get_cart();
        foreach($items as $item) {
            $item_id=$item['product_id'];
            $product_data=get_post_meta($item_id, "is_enable_buy_get" , true );
            if ($product_data == "yes" ) {
                if(!isset($item['free_product'])) {
                    $item_quantity=$item['quantity'];
                    $current_product_key = $item['key'];
                }
                if(isset($item['free_product'])) {
                    foreach($items as $get_item){
                        $free_item_quantity=$get_item['quantity'];
                        if(isset($get_item['parent_key']) && $get_item['parent_key'] == $current_product_key){
                            continue ;
                        }
                    }
                    if($item_quantity != $free_item_quantity){
                        WC()->cart->set_quantity( $item['key'], $item_quantity ,true);
                    }
                }
            }
        }
    }


    /**
     *
     * Add free product to cart
     *
     * @return void
     *
     * @throws Exception
     *
     */
    public function addProduct() {
        $items = WC()->cart->get_cart();
        foreach($items as $item) {
            if (isset($item['free_product'])) {
                continue;
            }
            $item_id=$item['product_id'];
            $product_data=get_post_meta($item_id, "is_enable_buy_get" , true );
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
                    $item_quantity ,
                    0,
                    array(),
                    array('free_product'  => true,
                        'parent_key'    => $current_product_key
                    )
                );
            }
        }
        //WC()->cart->empty_cart();
    }


    /**
     *
     * Remove free product if actual product not in cart
     *
     * @return void
     *
     */
    public function removeProduct() {
        $items = WC()->cart->get_cart();
        foreach($items as $item) {
            if (isset($item['free_product']) && $item['free_product'] == true) {
                if(isset($item['parent_key'])) {
                    $parent_key=$item['parent_key'];
                    $child_key=$item['key'];
                    $item_id=$item['product_id'];
                    $product_data=get_post_meta($item_id, "is_enable_buy_get" , true );
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