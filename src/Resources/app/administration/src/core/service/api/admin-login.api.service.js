const { ApiService } = Shopware.Classes;

/**
 * Gateway for the API end point "admin-login"
 * @class
 * @extends ApiService
 */
class AdminLoginApiService extends ApiService {
    constructor(httpClient, loginService, apiEndpoint = 'admin-login') {
        super(httpClient, loginService, apiEndpoint);
        this.name = 'adminLoginApiService';
    }

    getToken(customerId, salesChannelId, additionalParams = {}, additionalHeaders = {}) {
        let route = `/_action/admin-login/token`;
        const headers = this.getBasicHeaders(additionalHeaders);

        const params = {
            customerId,
            salesChannelId
        };

        return this.httpClient
            .post(route, params, {
                additionalParams,
                headers
            }).then((response) => {
                return ApiService.handleResponse(response);
            });
    }
}

export default AdminLoginApiService;
