<?php
declare(strict_types=1);
/**
 * Created by Apigility.
 * Date: 10/13/2017
 * (c) 2017
 */
namespace API\V1\Rpc\CheckSessionStatus;

use EyeChart\Service\Authenticate\AuthenticateStorageService;
use EyeChart\VO\TokenVO;
use Zend\Mvc\Controller\AbstractActionController;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;
use ZF\ContentNegotiation\ViewModel;

/**
 * Class CheckSessionStatusController
 * @package API\V1\Rpc\CheckSessionStatus
 */
class CheckSessionStatusController extends AbstractActionController
{

    /** @var AuthenticateStorageService */
    private $authenticateStorageService;

    /** @var \stdClass */
    private $inputData;

    /** @var TokenVO */
    private $tokenVO;

    /** @var mixed[]  */
    private $userTokenSession = [];

    /** @var mixed[] */
    private $jsonReturn = [];

    /**
     * CheckSessionStatusController constructor.
     *
     * @param AuthenticateStorageService $authenticateStorageService
     */
    public function __construct(AuthenticateStorageService $authenticateStorageService)
    {
        $this->authenticateStorageService = $authenticateStorageService;
    }

    /**
     * @return ApiProblemResponse|ViewModel
     */
    public function checkSessionStatusAction()
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
        $this->tokenVO = new TokenVO($this->inputData->token);
    }

    private function executeService(): void
    {
        $this->userTokenSession = $this->authenticateStorageService->getTokenSession($this->tokenVO);
    }

    private function prepareReturnData(): void
    {
        $this->jsonReturn = $this->userTokenSession;
    }
}
