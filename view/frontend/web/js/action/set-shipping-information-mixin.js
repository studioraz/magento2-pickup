/*jshint browser:true jquery:true*/
/*global alert*/
define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote',
    'mage/translate'
], function ($, wrapper, quote, $t) {
    'use strict';

    return function (setShippingInformationAction) {

        return wrapper.wrap(setShippingInformationAction, function (originalAction) {
            var shippingAddress = quote.shippingAddress();
            if (shippingAddress['extension_attributes'] === undefined) {
                shippingAddress['extension_attributes'] = {};
            }

            if (quote.shippingMethod().carrier_code == 'upsship' &&
                (!shippingAddress.customAttributes['ups_iid'] || !shippingAddress.customAttributes['ups_location'])) {
                return alert($t('Please choose a pickup location'));
            }
            shippingAddress['extension_attributes']['ups_iid'] = shippingAddress.customAttributes['ups_iid'];
            shippingAddress['extension_attributes']['ups_location'] = shippingAddress.customAttributes['ups_location'];

            return originalAction();
        });
    };
});