<?php
declare(strict_types=1);
/**
 * Created by Apigility.
 * Date: 10/13/2017
 * (c) 2017
 */
namespace API\V1\Rpc\Logout;

use EyeChart\VO\AuthenticationVO;
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

    /** @var \stdClass */
    private $inputData;

    /** @var AuthenticationVO */
    private $authenticationVO;

    /** @var mixed[] */
    private $jsonReturn;

    /** @var string[] */
    private $messages;

    /**
     * LogoutController constructor.
     * @param AuthenticateService $authenticateService
     */
    public function __construct(AuthenticateService $authenticateService)
    {
        $this->authenticateService = $authenticateService;
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
        // If no token is passed, we are still going to log out of the application
        $token = $this->inputData->token ?? '';

        $this->authenticationVO = AuthenticationVO::build()->setToken($token);
    }

    private function executeService(): void
    {
        $this->messages = $this->authenticateService->logout($this->authenticationVO);
    }

    private function prepareReturnData(): void
    {
        $this->jsonReturn = [
            'messages' => $this->messages
        ];
    }
}
