<?php declare(strict_types=1);

namespace JblLoginAsCustomer\Api;

use JblLoginAsCustomer\Api\Exception\AdminLoginActionException;
use Shopware\Core\Checkout\Customer\SalesChannel\AccountService;
use Shopware\Core\Framework\Api\Context\AdminApiSource;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\System\SalesChannel\Context\AbstractSalesChannelContextFactory;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminLoginActionController
 * @package JblLoginAsCustomer\Api
 * @author Jeffry Block <hello@jeffblock.de>
 */
#[Route(defaults: ['XmlHttpRequest' => true, '_routeScope' => ['administration']])]
class AdminLoginActionController extends AbstractController
{
    /**
     * @param AccountService $accountService
     * @param AbstractSalesChannelContextFactory $salesChannelContextFactory
     * @param EntityRepository $customerRepository
     */
    public function __construct(
        protected AccountService $accountService,
        protected AbstractSalesChannelContextFactory $salesChannelContextFactory,
        protected EntityRepository $customerRepository
    ) {
    }

    /**
     * @param RequestDataBag $request
     * @param Context $context
     * @return JsonResponse
     * @author Jeffry Block <hello@jeffblock.de>
     */
    #[Route(path: '/api/_action/admin-login/token', name: 'api.action.admin-login.login', methods: ['POST'])]
    public function getToken(RequestDataBag $request, Context $context): JsonResponse
    {
        try {
            /** @var bool $isAdmin */
            $isAdmin = $context->getSource() instanceof AdminApiSource && $context->getSource()->isAdmin();

            /** @var string $customerId */
            $customerId = $request->getAlnum("customerId");

            /** @var string $customerId */
            $salesChannelId = $request->getAlnum("salesChannelId");

            if(!$isAdmin) {
                throw new AdminLoginActionException("Not allowed");
            }

            if(!$customerId || !$salesChannelId) {
                throw new AdminLoginActionException("Not all required parameters were given: customerId, salesChannelId");
            }

            /** @var bool $customerExists */
            $customerExists = $this->customerRepository->searchIds(
                (new Criteria([$customerId]))->addFilter(new EqualsFilter("active", true)),
                $context
            )->getTotal() === 1;

            if(!$customerExists) {
                throw new AdminLoginActionException(sprintf("Customer with identifier %s not found or not active", $customerId));
            }

            /** @var SalesChannelContext $salesChannel */
            $salesChannelContext = $this->salesChannelContextFactory->create(Uuid::randomHex(), $salesChannelId, [
                "customerId" => $customerId,
            ]);

            /** @var string|null $url */
            $url = $salesChannelContext->getSalesChannel()->getDomains()?->first()->getUrl();
            if($url === null || !str_starts_with($url, 'http')) {
                throw new AdminLoginActionException(sprintf("No URL for Sales-Channel %s found", $salesChannelContext->getSalesChannel()->getName()));
            }

            $token = $this->accountService->loginById($customerId, $salesChannelContext);

            return new JsonResponse([
                "success" => true,
                "token" => $token,
            ], 200);

        } catch(AdminLoginActionException|\Throwable $ex) {
            return new JsonResponse([
                "success" => false,
                "message" => $ex->getMessage(),
            ], $ex->getStatusCode());
        }
    }
}
