import template from './sw-customer-detail.html.twig';
import './sw-customer-detail.scss';

const { Component } = Shopware;

Component.override('sw-customer-detail', {
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
