/**
 * JS scripts processed on front page.
 *
 *
 * ATTENTION!
 * Please remember about packing this file after you'll finish working on it.
 * Otherwise plugin won't work on production servers. Packed version of this
 * file should be placed in /assets/js/front-min.js. While packing, remember to check
 * option "Shrink variables".
 *
 *
 * @packer http://dean.edwards.name/packer/
 */

( function( $ ){
    $( document ).ready( function(){

        /**
         * Function makes an AJAX request and puts the response into cart container
         * @param ajax_data
         * @param update_cart
         * @param scroll_to
         * @param update_checkout
         */
        function ajax_request( $element, ajax_data, update_cart, scroll_to, update_checkout )
        {
	          $element.block({
			          message: null,
			          overlayCSS: {
					          background: '#fff',
					          opacity: 0.6
			          }
	          });
            $.ajax({
                type: 'post',
								url: ops_php_data.ajax_url,
                data: ajax_data,
                success: function( response ) {
	                  $element.unblock();

                    if ( update_cart ) {
                        // put the html code into container
                        $( '#one-page-shopping-cart-content' ).html( response );
                    }

                    // update checkout container if needed
                    if ( update_checkout === true ) {

                        var order_review = $( '#order_review');

                        if ( order_review.length && order_review.is( ':visible' )  ) {

                            $( 'body' ).trigger( 'update_checkout' );
                        }
                        else {

                            // Deprecated since WooCommerce 2.1, can be removed in a future;
                            // get the current value of "Ship to billing address" checkbox
                            var ship_to_billing_val = $( '#shiptobilling-checkbox' ).length ?
                                $( '#shiptobilling-checkbox' ).is( ':checked' ) :
                                ops_php_data.ship_to_billing_def === '1';

                            // Used since WooCommerce 2.1;
                            // get the current value of "Ship to billing address" checkbox
                            var ship_to_different_val = $( '#ship-to-different-address-checkbox' ).length ?
                                $( '#ship-to-different-address-checkbox' ).is( ':checked' ) :
                                ops_php_data.ship_to_different_def !== '0';


                            $( '#one-page-shopping-checkout, #one-page-shopping-cart' ).show();
                            var checkout_ajax_data = {
                                action: 'ops_update_checkout'
                            };
                            checkout_ajax_data[ops_php_data.nonce_post_id] = ops_php_data.nonce;

                            $.ajax({
                                type: 'post',
                                url: ops_php_data.ajax_url,
                                data: checkout_ajax_data,
                                success: function( checkout_response ) {
                                    $( '#one-page-shopping-checkout-content' ).html( checkout_response );

                                    // Deprecated since WooCommerce 2.1, can be removed in a future;
                                    $( '#shiptobilling-checkbox' ).attr( 'checked', ship_to_billing_val ).change();

                                    // Used since WooCommerce 2.1;
                                    $( '#ship-to-different-address-checkbox' ).attr( 'checked', ship_to_different_val ).change();
                                }
                            });
                        }
                    }

                    // scroll the screen into pointed container
                    if ( scroll_to !== false && scroll_to.length ) {
                        $( 'body' ).scrollTo( scroll_to, {
                            duration: 'slow'
                        });
                    }

                    // this part of code is copied from original WooCommerce file 'add-to-cart.js'
                    $( 'div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)' )
                            .addClass( 'buttons_added' );

                    // hide the checkout when cart is empty
                    if ( $( '#one-page-shopping-cart-content table' ).length === 0 && update_cart ) {
                        $( '#one-page-shopping-checkout, #one-page-shopping-cart' ).hide();
                        $( 'body' ).scrollTo( '#page', {
                            duration: 'slow'
                        });
                    }

                    remove_unused_markup();
                }
            });
        };

        /**
         * remove all unnecessary markup which is automatically generated by cart/checkout
         */
        function remove_unused_markup()
        {
            for( var i = 0; i < ops_php_data.remove_items.length; i++ ) {
                $( ops_php_data.remove_items[i] ).remove();
            }
        }

        // show the checkout in case when cart is not empty
        if ( $( '#one-page-shopping-cart-content table' ).length !== 0 || ops_php_data.cart_count > 0 ) {
            $( '#one-page-shopping-checkout, #one-page-shopping-cart' ).show();
        }

        // Deprecated since WooCommerce 2.1, can be removed in a future;
        // set the functionality under "Ship to billing address" checkbox
        $( '#one-page-shopping-checkout-content' ).on( 'change', '#shiptobilling input', function() {
            $( 'div.shipping_address' ).hide();
            if ( !$(this).is( ':checked' ) ) {
                $( 'div.shipping_address' ).slideDown();
            }
        });
        $( '#shiptobilling input' ).change();

        // Used since WooCommerce 2.1;
        // set the functionality under "Ship to a different address?" checkbox
        $( '#one-page-shopping-checkout-content' ).on( 'change', '#ship-to-different-address-checkbox', function() {
            $( 'div.shipping_address' ).hide();
            if ( $(this).is( ':checked' ) ) {
                $( 'div.shipping_address' ).slideDown();
            }
        });
        $( '#ship-to-different-address-checkbox' ).change();

        /**
         * Click on "Add to Cart" button, displayed on product page
         */
        $( 'form.cart' ).on( 'click', '.single_add_to_cart_button', function(event) {

            var ajax_data = {
                action: 'ops_add_to_cart',
                form: {}
            };
            var scroll_to = ops_php_data.display_cart ? '#one-page-shopping-header' : '#one-page-shopping-checkout-header',
                items = $( this ).parents( 'form.cart' ).find( 'input[type!=radio][name], input[type=radio][name]:checked, select[name], textarea[name]' );

            if ($('.group_table').length) {

                items.each(function(){

                    var itemData = getInputData($(this));

                    if (itemData.id !== undefined) {

                        if (ajax_data.form[itemData.id] === undefined) {
                            ajax_data.form[itemData.id] = {};
                        }

                        ajax_data.form[itemData.id][itemData.name] = $( this ).val();
                    }
                });
            }
            else {

                var prodId = $('input[name=add-to-cart]').val();
                items.each( function() {

                    if (ajax_data.form[prodId] === undefined) {
                        ajax_data.form[prodId] = {};
                    }

                    ajax_data.form[prodId][$( this ).attr( 'name' )] = $( this ).val();
                });
            }

            ajax_data[ops_php_data.nonce_post_id] = ops_php_data.nonce;
            ajax_request( $(event.target), ajax_data, ops_php_data.display_cart, scroll_to, true );

            return false;
        });

        /**
         * Returns id and name for given input field
         * @param $input
         * @returns {{name: *}}
         */
        function getInputData($input)
        {
            var nameParts = $input.attr('name').split('['),
                data = {
                    name: nameParts[0]
                };

            if (nameParts[1] !== undefined) {
                data.id = nameParts[1].replace(']', '');
            }

            return data;
        }

        /**
         * Click on "Add to Cart" button, displayed on product list
         */
        $( '.products' ).on( 'click', '.product_type_simple', function( event ) {

            event.preventDefault();

            var scroll_to = ops_php_data.display_cart ? '#one-page-shopping-header' : '#one-page-shopping-checkout-header',
                ajax_data = {
                action: 'ops_add_to_cart',
                form: { }
            };
            ajax_data[ops_php_data.nonce_post_id] = ops_php_data.nonce;

            var prod_id = $( this ).data( 'product_id' );
            ajax_data.form[prod_id] = {};
            ajax_data.form[prod_id]['quantity'] = $( this ).data( 'quantity' );

            ajax_request( $(event.target), ajax_data, ops_php_data.display_cart, scroll_to, true );
            return false;
        } );

        /**
         * Click on "Add to Cart" button, displayed under related product
         */
        $( 'div.related' ).on( 'click', '.add_to_cart_button_ops', function(event) {

            var ajax_data = {
                action: 'ops_add_to_cart_related',
                product_id: $( this ).attr( 'data-product_id' )
            };
            var scroll_to = ops_php_data.display_cart ? '#one-page-shopping-header' : '#one-page-shopping-checkout-header';
            ajax_data[ops_php_data.nonce_post_id] = ops_php_data.nonce;

            ajax_request( $(event.target), ajax_data, ops_php_data.display_cart, scroll_to, true );

            return false;
        });

        /**
         * Click on remove product from cart icon, on product page
         */
        $( '#one-page-shopping-cart-content' ).on( 'click', '.product-remove > a', function(event) {

            var parts = this.search.split( 'remove_item=' );
            if ( parts[1] !== undefined ) {

                parts = parts[1].split( '&' );
                if ( parts[0] !== undefined ) {

                    var ajax_data = {
                        action: 'ops_remove_from_cart',
                        cart_item: parts[0]
                    };
                    ajax_data[ops_php_data.nonce_post_id] = ops_php_data.nonce;

                    ajax_request( $(event.target).closest('form'), ajax_data, true, false, true );
                    return false;
                }
            }
        });

        /**
         * Click on "Update Cart" button
         */
        $( '#one-page-shopping-cart-content' ).on( 'click', 'input[name=update_cart]', function(event) {

            var match,
                pattern = /\[(\w*)\]\[(\w*)\]/,
                ajax_data = {
                    action: 'ops_update_cart',
                    cart: new Object()
                };
            ajax_data[ops_php_data.nonce_post_id] = ops_php_data.nonce;

            // this part here generates the array with cart items. Format fits WC standards.
            $( this ).parents( 'form' ).find( 'input[name^=cart]' ).each( function() {
                match = $( this ).attr( 'name' ).match( pattern );
                if ( match[2] !== undefined ) {
                    // create a new cart item if doesn't exist
                    if ( ajax_data.cart[match[1]] === undefined ) {
                        ajax_data.cart[match[1]] = new Object();
                    }
                    ajax_data.cart[match[1]][match[2]] = $( this ).val();
                }
            });

            ajax_request( $(event.target), ajax_data, true, false, true );
            return false;
        });

        /**
         * Click on "Proceed to checkout" button
         */
        if (ops_php_data.display_checkout) {
            $('#one-page-shopping-cart-content').on('click', 'input[name=proceed]', function () {

                $('body').scrollTo('#one-page-shopping-checkout-header', {
                    duration: 'slow'
                });
                return false;
            });
        }

        /**
         * Click on "Remove" when coupon is applied
         */
        $( '#one-page-shopping-checkout-content' ).on( 'click', '.woocommerce-remove-coupon', function(event) {

            var ajax_data = { action: 'ops_remove_coupon' };
            ajax_data[ops_php_data.nonce_post_id] = ops_php_data.nonce;

            var parts = this.search.split( 'remove_coupon=' );
            if ( parts[1] !== undefined ) {
                ajax_data.coupon = parts[1];
                ajax_request( $(event.target), ajax_data, false, false, true );
            }

            return false;
        });

        remove_unused_markup();
    });
})( jQuery );
