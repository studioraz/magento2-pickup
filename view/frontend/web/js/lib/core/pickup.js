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
    'uiComponent',
    'uiRegistry',
    'ko',
    'jquery'
], function (Component, registry, ko, $) {
    'use strict';
    return Component.extend({
        defaults: {
            template: 'SR_UpsShip/checkout/shipping/ups-ship-block'
        },
        hasPickerInitialized: false,
        hasInitialized: false,
        carrierCode: 'upsship',
        location: null,
        initialize: function (options) {
            this.registry = registry;

            this._super(options);

            this.observe('isVisible locationHTML isInfoVisible');

            this._initializePicker();

            return this;
        },

        _initializeSDK: function () {
            (function ()
            {
                var pkp = document.createElement('script');
                pkp.type = 'text/javascript'; pkp.async = true;
                pkp.src = window.checkoutConfig.upship.location_js_url;
                document.getElementsByTagName('head')[0].appendChild(pkp);
            })();
        },

        showPickerPopup: function (data, event) {
            event.preventDefault();
            window.PickupsSDK.show();
            return false;
        },

        _onShippingMethodChanged: function (carrierCode) {
            var isActive = carrierCode == this.carrierCode;

            this.isVisible(isActive);

            if (isActive) {
                var location = this.registry.get('ups_location');
                if (location) {
                    this.location = JSON.parse(location);
                    this._update();
                }
            } else {
                this._clear();
            }
        },

        _initializePicker: function () {
            this._initializeSDK();
            $(document.body).on('pickups-before-open', {component: this}, function (event) {

                // prepare customer location
                var self = event.data.component;

                var address =  self._getShippingAddress();

                var o = {
                    location: {
                        city: address.city,
                        street: address.street
                    }
                };
                var json = JSON.stringify(o);
                window.PickupsSDK.setDefaults(json);
            });

            $(document.body).on('pickups-after-choosen', {component: this}, function (event, data) {
                var self = event.data.component;
                self.location = event.originalEvent.detail;
                self._update();
            });
            this.hasPickerInitialized = true;
        },

        _update: function () {
            var compiled = _.template("<strong><%= title %> (<%= iid %>)</strong><br/><%= street %>,<%= city %><br/><%= zip %>");
            var html = compiled(this.location);
            this.locationHTML(html).isInfoVisible(true);

            var upsLocation = JSON.stringify(this.location);
            this.registry.set('ups_location', upsLocation);
            this._sendRequest({
                'is_active': true,
                'shipping_ups_pickup_id': this.location.iid,
                'shipping_additional_information': upsLocation
            });
        },

        _clear: function () {
            this.locationHTML('');
            this._sendRequest({'is_active': false});
        },

        _sendRequest: function (data) {
            $.ajax({
                method: 'post',
                url: '/upsship/checkout/saveAdditional',
                showLoader: true,
                data: data
            });
        }
    });
});
