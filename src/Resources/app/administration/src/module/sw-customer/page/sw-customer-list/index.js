import template from './sw-customer-list.html.twig';

const { Component } = Shopware;

Component.override('sw-customer-list', {
    template,

    data() {
        return {
            loginCustomerId: null
        }
    },

    methods: {
        onShowLoginModal(customerId){
            this.loginCustomerId = customerId;
        },
        onCloseLoginModal(){
            this.loginCustomerId = null;
        }
    }
});
