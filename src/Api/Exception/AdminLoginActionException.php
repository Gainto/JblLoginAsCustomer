<?php declare(strict_types=1);

namespace JblLoginAsCustomer\Api\Exception;

use Shopware\Core\Framework\ShopwareHttpException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AdminLoginActionException
 * @package JblLoginAsCustomer\Api\Exception
 * @author Jeffry Block <hello@jeffblock.de>
 */
class AdminLoginActionException extends ShopwareHttpException
{
    /**
     * @param string $message
     */
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    /**
     * @return string
     * @author Jeffry Block <hello@jeffblock.de>
     */
    public function getErrorCode(): string
    {
        return 'LOGIN_AS_CUSTOMER_ERROR';
    }

    /**
     * @return int
     * @author Jeffry Block <hello@jeffblock.de>
     */
    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
