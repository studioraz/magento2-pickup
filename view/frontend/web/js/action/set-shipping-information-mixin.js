/*jshint browser:true jquery:true*/
/*global alert*/
define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote',
    'uiRegistry',
    'mage/translate'
], function ($, wrapper, quote, registry, $t) {
    'use strict';

    return function (setShippingInformationAction) {

        return wrapper.wrap(setShippingInformationAction, function (originalAction) {

            // validate Pickup shipping required information

            if (quote.shippingMethod().carrier_code == 'upsship' && !registry.get('ups_location')) {
                return alert($t('Please choose a pickup location'));
            }


            return originalAction();
        });
    };
});