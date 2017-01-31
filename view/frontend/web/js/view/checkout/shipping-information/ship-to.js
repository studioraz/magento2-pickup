/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*global define*/
define([
    'jquery',
    'ko',
    'mageUtils',
    'uiComponent',
    'uiLayout',
    'Magento_Checkout/js/model/quote'
], function ($, ko, utils, Component, layout, quote) {
    'use strict';
    return Component.extend({
        defaults: {
            template: 'SR_UpsShip/checkout/shipping-information/ship-to'
        },
        initialize: function () {
            this._super();

            var self = this;
            quote.shippingMethod.subscribe(function() {
               console.log('do something');
            });
            return this;
        },

        initConfig: function () {
            this._super();
            return this;
        },

        initChildren: function () {
            return this;
        }
    });
});
