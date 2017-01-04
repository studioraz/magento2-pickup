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
            'pickups': 'SR_UpsShip/js/lib/ups-pickup'
        }
    },
    shim: {
        'pickups': {
            deps: ['jquery']
        }
    }
}