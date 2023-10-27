import './module/sw-customer';

import AdminLoginApiService from './core/service/api/admin-login.api.service';

Shopware.Service().register('adminLoginApiService', (container) => {
    const initContainer = Shopware.Application.getContainer('init');
    return new AdminLoginApiService(
        initContainer.httpClient,
        container.loginService
    );
});
