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
    window.shippingMethod = ko.observable({carrier_code: ''});
    return Component.extend({
        defaults: {
            template: 'SR_UpsShip/checkout/shipping/ups-ship-block'
        },

        initialize: function (options) {
            this.registry = registry;
            this._super(options);

            window.shippingMethod.subscribe(this._onShippingMethodChanged, this);

            this.observe('isVisible locationHTML isInfoVisible');

            return this;
        },

        _isLocationSelected: function () {
            return registry.get('ups_location');
        },

        showPickerPopup: function (data, event) {
            event.preventDefault();
            window.PickupsSDK.show();
            return false;
        },

        _onShippingMethodChanged: function (data) {
            var isActive = data.carrier_code == this.carrierCode;
            this.isVisible(isActive);

            if (isActive) {
                if (!this.hasPickerInitialized) {
                    this._initializePicker();
                }
                var location = this.registry.get('ups_location');
                if (location) {
                    this.location = JSON.parse(location);
                    this._update();
                }
            } else {
                this._clear();
            }
        },

        _getAdressValue: function (fieldName) {
            var component = this.registry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.' + fieldName);
            return component ? component.value() : null;
        },

        _setAdressValue: function (fieldName, value) {
            var component = this.registry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.' + fieldName);
            if (component) {
                component.value(value)
            }
            return this;
        },

        _initializePicker: function () {

            // include UPS JS library
            require(['pickups']);

            $(document.body).on('pickups-before-open', {component: this}, function (event) {

                // prepare customer location

                var self = event.data.component;

                var o = {
                    location: {
                        city: self._getAdressValue('city'),
                        street: self._getAdressValue('street.0')
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
