/*jshint browser:true jquery:true*/
/*global alert*/
define([
    'jquery',
    'uiComponent',
    'uiRegistry',
    'SR_UpsShip/js/view/checkout/shipping/paypal-ups-ship-block'
], function ($, Component, registry, upsShipBlock) {
    'use strict';
    return function () {
        $.widget("mage.orderReview", $.extend({}, $.mage.orderReview.prototype, {
            _submitUpdateOrder: function (url, resultId) {
                if (this.element.find(this.options.waitLoadingContainer).is(":visible")) {
                    return false;
                }
                var isChecked = $(this.options.billingAsShippingSelector).is(':checked'),
                    formData = null,
                    callBackResponseHandler = null,
                    shippingMethod = $.trim($(this.options.shippingSelector).val());
                this._shippingTobilling();

                if (url && resultId && shippingMethod) {
                    this._updateOrderSubmit(true);
                    this._toggleButton(this.options.updateOrderSelector, true);

                    // form data and callBack updated based on the shippping Form element
                    if (this.isShippingSubmitForm) {
                        formData = $(this.options.shippingSubmitFormSelector).serialize() + "&isAjax=true";
                        callBackResponseHandler = function (response) {
                            $(resultId).html(response);
                            this._updateOrderSubmit(false);
                            this._ajaxComplete();
                            window.shippingMethod({carrier_code: shippingMethod.split('_')[0]});
                        };
                    } else {
                        formData = this.element.serialize() + "&isAjax=true";
                        callBackResponseHandler = function (response) {
                            $(resultId).html(response);
                            this._ajaxShippingUpdate(shippingMethod);
                            window.shippingMethod({carrier_code: shippingMethod.split('_')[0]});
                        };
                    }
                    if (isChecked) {
                        $(this.options.shippingSelect).prop('disabled', true);
                    }
                    $.ajax({
                        url: url,
                        type: 'post',
                        context: this,
                        beforeSend: this._ajaxBeforeSend,
                        data: formData,
                        success: callBackResponseHandler
                    });
                }
            },

            /**
             * Validate Order form
             *
             * @todo process UPS validation
             */
            _validateForm: function () {
                this.element.find(this.options.agreementSelector).off('change').on('change', $.proxy(function (e) {
                    var isValid = this._validateForm();
                    this._updateOrderSubmit(!isValid);
                }, this));

                if (this.element.data('mageValidation')) {
                    return this.element.validation().valid();
                }
                return true;
            }
        }));
    }

});
