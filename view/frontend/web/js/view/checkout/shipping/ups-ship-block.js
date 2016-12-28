define([
    'uiComponent',
    'ko',
    'Magento_Checkout/js/model/quote'
], function (Component, ko, quote) {
    'use strict';
    return Component.extend({
        defaults: {
            template: 'SR_UpsShip/checkout/shipping/ups-ship-block'
        },
        isVisible: ko.observable(false),
        initialize: function (options) {
            var self = this;
            this.initConfig(options);

            quote.shippingMethod.subscribe(function (data) {
                self.isVisible(data.carrier_code == 'ups_ship');
            });

            jQuery(document.body).on('pickups-before-open', function () {
                var o = {
                    location: {
                        city: jQuery('input[name="city"]').val(),
                        street: jQuery('input[name="street[0]"]').val()
                    }
                };
                var json = JSON.stringify(o);
                window.PickupsSDK.setDefaults(json);
            });

            jQuery(document.body).on('pickups-after-choosen', function (e, data) {
                var pkps_location = e.originalEvent.detail;
                console.log("pickups-after-choosen catched event", pkps_location);
                jQuery("input[name=shipping_ups_pickup_id]").val(pkps_location.iid);
                jQuery("input[name=shipping_additional_information]").val(JSON.stringify(pkps_location));
                var html = "<br /><b>" + pkps_location.title + "</b>&nbsp;(" + pkps_location.iid + ")<br />" + pkps_location.city + ", " + pkps_location.street + "<br /><small>" + pkps_location.zip + "</small>";
                jQuery("div.ups-pickups-info").css("line-height", "1em").css("font-weight", "300").css("font-size", "0.9em").html(html);
            });

            return this;
        }
    });
});
