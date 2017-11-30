var config = {
    config: {
        mixins: {

            'Magento_Checkout/js/action/set-shipping-information': {
                'SR_UpsShip/js/action/set-shipping-information-mixin': true
            }
        }
    },
    map: {
        '*': {
            'uiPickup' : 'SR_UpsShip/js/lib/core/pickup'
        }
    },
    shim: {
        'pickups': {
            deps: ['jquery']
        }
    }
}
