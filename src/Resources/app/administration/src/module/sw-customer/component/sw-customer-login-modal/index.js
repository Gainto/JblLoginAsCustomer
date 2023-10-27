import template from './sw-customer-login-modal.html.twig';

const { Context, Component, Mixin } = Shopware;
const { Criteria } = Shopware.Data;

Component.register('sw-customer-login-modal', {
    template,

    inject: ['repositoryFactory','adminLoginApiService'],

    mixins: [
        Mixin.getByName('notification'),
    ],
    props: {
        customerId: {
            type: String,
            required: true,
        },
    },

    data() {
        return {
            isLoading: true,
            loginLoading: null,
            salesChannels: []
        };
    },

    computed: {
        defaultCriteria() {
            const criteria = new Criteria();

            criteria.addAssociation('domains');
            criteria.addAssociation('type');
            criteria.addFilter(Criteria.equals('active', true));
            criteria.addFilter(Criteria.equals('type.iconName', "regular-storefront"));

            return criteria;
        },

        salesChannelRepository() {
            return this.repositoryFactory.create('sales_channel');
        },

        salesChannelGridColumns(){
           return [{
                property: 'name',
                label: 'jbl-login-as-customer.gridTitleSalesChannel',
                useCustomSort: false,
                allowResize: false,
            },{
               property: 'domains.first().url',
               label: 'jbl-login-as-customer.gridTitleDomain',
               useCustomSort: false,
               allowResize: false,
           },{
               property: 'id',
               label: 'jbl-login-as-customer.gridTitleAction',
               useCustomSort: false,
               allowResize: false,
           }];

        }
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.fetchSalesChannels();
        },

        fetchSalesChannels() {
            this.isLoading=true;

            this.salesChannelRepository
                .search(this.defaultCriteria, Context.api)
                .then((salesChannels) => {
                    this.salesChannels = salesChannels;
                    this.isLoading = false;
                });
        },

        onLogin(salesChannelId, url){

            this.loginLoading = salesChannelId;

            url = url.replace(/^\//, '');
            const loginRoute = "/admin-login/";

            this.adminLoginApiService
                .getToken(this.customerId, salesChannelId)
                .then((response) => {
                    window.open(url + loginRoute + response.token);
                }).catch((res) => {
                    this.createNotificationError({
                        title: res.message,
                        message: res.response.data.message
                    });
                }).finally(() => {
                   this.loginLoading = null;
                });
        },

        onClose() {
            this.$emit('close');
        },
    },
});