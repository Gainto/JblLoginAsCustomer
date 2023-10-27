<?php declare(strict_types=1);

namespace JblLoginAsCustomer\Storefront\Controller;

use Shopware\Core\Checkout\Customer\SalesChannel\AccountService;
use Shopware\Core\System\SalesChannel\Context\SalesChannelContextPersister;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminLoginController
 * @package JblLoginAsCustomer\Storefront\Controller
 * @author Jeffry Block <hello@jeffblock.de>
 */
#[Route(defaults: ['_routeScope' => ['storefront']], methods:["GET"])]
class AdminLoginController extends StorefrontController
{
    /**
     * @param SalesChannelContextPersister $salesChannelContextPersister
     * @param AccountService $accountService
     */
    public function __construct(
        protected SalesChannelContextPersister $salesChannelContextPersister,
        protected AccountService $accountService
    ) {
    }

    /**
     * @param Request $request
     * @param SalesChannelContext $context
     * @return Response
     * @author Jeffry Block <hello@jeffblock.de>
     */
    #[Route('/admin-login/{token}', name:'frontend.admin.login', requirements: ['token' => '[0-9a-zA-Z]{32}'])]
    public function index(Request $request, SalesChannelContext $context): Response
    {

        try {
            /** @var array $data */
            $data = $this->salesChannelContextPersister->load($request->get("token"), $context->getSalesChannelId());

            if(!$data || $data["expired"]) {
                throw new \Exception("Something went wrong");
            }

            $this->accountService->loginById($data["customerId"], $context);

            $this->addFlash(self::SUCCESS, $this->trans('admin-login.success'));
        } catch(\Throwable) {
            $this->addFlash(self::DANGER, $this->trans('error.message-default'));
        }

        return $this->redirectToRoute('frontend.home.page');
    }

}
