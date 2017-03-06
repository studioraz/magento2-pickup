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
    'jquery'
], function (uiPickup, $) {
    'use strict';
    return uiPickup.extend({

        initialize: function (options) {

            var shippingSelector = jQuery('#order-review-form').data('mageOrderReview').options.shippingSelector;

            this.shippginElement = $(shippingSelector).on('change', $.proxy(this._onShippingMethodChanged, this));

            this._super(options);

            this._onShippingMethodChanged();

        },

        _onShippingMethodChanged : function() {

            this._super($.trim(this.shippginElement.val().split('_')[0]));

        },

        _getShippingAddress : function () {
            return {
                'city' : this.options.shippingAddress.city,
                'street' : this.options.shippingAddress.street
            }
        }

    });
});
