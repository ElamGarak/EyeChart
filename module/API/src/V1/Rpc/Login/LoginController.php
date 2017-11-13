<?php
declare(strict_types=1);
/**
 * Created by Apigility.
 * Date: 10/13/2017
 * (c) 2017
 */

namespace API\V1\Rpc\Login;

use EyeChart\VO\LoginVO;
use EyeChart\VO\VOInterface;
use Zend\Mvc\Controller\AbstractActionController;
use EyeChart\Service\Authenticate\AuthenticateService;
use ZF\ApiProblem\ApiProblemResponse;
use ZF\ApiProblem\ApiProblem;
use ZF\ContentNegotiation\ViewModel;

/**
 * Class LoginController
 * @package API\V1\Rpc\Login
 */
final class LoginController extends AbstractActionController
{
    /** @var AuthenticateService  */
    private $authenticateService;

    /** @var \stdClass */
    private $inputData;

    /** @var VOInterface */
    private $loginVO;

    /** @var string */
    private $token = '';

    /** @var mixed */
    private $jsonReturn = [];

    /**
     * LoginController constructor.
     * @param AuthenticateService $authenticateService
     */
    public function __construct(AuthenticateService $authenticateService)
    {
        $this->authenticateService = $authenticateService;
    }

    /**
     * @return ApiProblemResponse|ViewModel
     */
    public function loginAction()
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
        $this->loginVO = new LoginVO($this->inputData->username, $this->inputData->password);
    }

    private function executeService(): void
    {
        $this->token = $this->authenticateService->login($this->loginVO);
    }

    private function prepareReturnData(): void
    {
        $this->jsonReturn = [
            'token'    => $this->token,
            'messages' => 'Login Successful'
        ];
    }
}
