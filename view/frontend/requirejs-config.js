var config = {
    config: {
        mixins: {
            'Magento_Paypal/order-review': {
                'SR_UpsShip/js/view/paypal/order-review': true
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
