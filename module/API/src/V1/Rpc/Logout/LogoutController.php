<?php
declare(strict_types=1);
/**
 * Created by Apigility.
 * Date: 10/13/2017
 * (c) 2017
 */
namespace API\V1\Rpc\Logout;

use EyeChart\Entity\AuthenticateEntity;
use Zend\Mvc\Controller\AbstractActionController;
use EyeChart\Service\Authenticate\AuthenticateService;
use ZF\ApiProblem\ApiProblemResponse;
use ZF\ApiProblem\ApiProblem;
use ZF\ContentNegotiation\ViewModel;

/**
 * Class LogoutController
 * @package API\V1\Rpc\Logout
 */
class LogoutController extends AbstractActionController
{
    /** @var AuthenticateService */
    private $authenticateService;

    /** @var AuthenticateEntity */
    private $authenticateEntity;

    /** @var \stdClass */
    private $inputData;

    /** @var mixed[] */
    private $jsonReturn;

    /**
     * LogoutController constructor.
     * @param AuthenticateService $authenticateService
     * @param AuthenticateEntity $authenticateEntity
     */
    public function __construct(AuthenticateService $authenticateService, AuthenticateEntity $authenticateEntity)
    {
        $this->authenticateService = $authenticateService;
        $this->authenticateEntity  = $authenticateEntity;
    }

    /**
     * @return ApiProblemResponse|ViewModel
     */
    public function logoutAction()
    {
        try {
            $this->extractAPIData();
            $this->prepareServiceData();
            $this->executeService();
            $this->prepareReturnData();
        } catch (\Exception $exception) {
            return new ApiProblemResponse(
                new ApiProblem(
                    $exception->getCode(),
                    'error',
                    null,
                    $exception->getMessage()
                )
            );
        }

        return new ViewModel($this->jsonReturn);
    }

    private function extractAPIData(): void
    {
        $this->inputData = json_decode($this->request->getContent());
    }

    private function prepareServiceData(): void
    {
        $this->authenticateEntity->setToken($this->inputData->token);
    }

    private function executeService(): void
    {
        $this->authenticateService->logout();
    }

    private function prepareReturnData(): void
    {
        $this->jsonReturn = [
            'messages' => $this->authenticateEntity->getMessages(),
            'logout'   => true
        ];
    }
}
