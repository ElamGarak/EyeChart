<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 9/12/2017
 * (c) 2017
 */

namespace EyeChart\Exception;

use stdClass;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

/**
 * Class FailedResponseException
 * @package EyeChart\Exception
 */
final class FailedResponseException extends \Exception
{

    /** @var Logger */
    private $logger;

    /** @var Request */
    private $request;

    /** @var Response */
    private $response;

    /** @var stdClass */
    private $validationMessages;

    /** @var string */
    protected $message = 'An error occurred during the update process';

    /**
     * FailedResponseException constructor.
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request  = $request;
        $this->response = $response;

        $this->initialize();
    }

    /**
     * @return stdClass|null
     */
    public function getValidationMessages():? stdClass
    {
        return $this->validationMessages;
    }

    private function initialize(): void
    {
        $this->setLogger();
        $this->setValidationMessages();
        $this->parseToLog();
    }

    private function setValidationMessages(): void
    {
        $results = json_decode($this->response->getBody());

        if (property_exists($results, 'validation_messages')) {
            $this->validationMessages = $results->validation_messages;
        }
    }

    private function parseToLog(): void
    {
        $uri         = $this->request->getUri()->getPath();
        $sentPayload = json_decode($this->request->getContent());

        $this->logger->err("Call to {$uri} Failed");
        $this->logger->info('Payload Sent: ' . var_export($sentPayload, true));
        $this->logger->info('Return Response: ' . print_r($this->validationMessages, true));
    }

    private function setLogger(): void
    {
        $this->logger = new Logger();

        $writer = new Stream(getcwd() . '/data/logs/rpc_errors_' . date('Y-m-d'));

        $this->logger->addWriter($writer);
    }
}
