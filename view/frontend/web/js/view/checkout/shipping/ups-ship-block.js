/**
 *
 * UPS location JSON sample:
 * {
     "lat": 32.0876359,
     "lng": 34.786194,
     "title": "פיקאפ בית מרקחת ברק",
     "street": "רמז 15",
     "city": "תל אביב יפו",
     "zip": "שעות פתיחה א-ה 09:00-19:00 יום ו 09:00-14:00",
     "iid": "PKPS639854",
     "dist": "0.22"
 * }
 *
 */

define([
    'uiPickup',
    'uiRegistry',
    'Magento_Checkout/js/model/quote'
], function (uiPickup, registry, quote) {
    'use strict';
    return uiPickup.extend({

        initialize: function (options) {

            this.quote = quote;

            this.quote.shippingMethod.subscribe(this._onShippingMethodChanged, this);

            var self = this;

            registry.async('checkoutProvider')(function (checkoutProvider) {
                checkoutProvider.on('shippingAddress', function (shippingAddressData) {
                    self.shippingAddressData = shippingAddressData;
                });
            });



            return this._super(options);

        },


        _onShippingMethodChanged : function (data) {
            this._super(data.carrier_code);
        },


        _getShippingAddress : function () {

            // try to get address daa from quote object - recent updated.
            if (this.quote.shippingAddress().city && this.quote.shippingAddress().street) {
                return {
                    'city' : this.quote.shippingAddress().city,
                    'street' : this.quote.shippingAddress().street[0]
                }
            }

            // fallback to checkout provider address

            var checkoutAddress = this.shippingAddressData;

            return {
                'city' : checkoutAddress.city,
                'street' : checkoutAddress.street['0']
            }

        }

    });
});
