<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/12/2017
 * (c) Eye Chart
 */

namespace EyeChart\Controller\Index;

use EyeChart\Command\Commands\AuthenticateCommand;
use EyeChart\Entity\AuthenticateEntity;
use EyeChart\Mappers\AuthenticateMapper;
use EyeChart\Mappers\MenuMapper;
use League\Tactician\CommandBus;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class IndexController
 * @package EyeChart\Controller\Index
 */
final class IndexController extends AbstractActionController
{
    /** @var AuthenticateEntity */
    private $authenticateEntity;

    /** @var CommandBus */
    private $commandBus;

    /**
     * Constructor
     *
     * @param AuthenticateEntity $authenticateEntity
     * @param CommandBus         $commandBus
     */
    public function __construct(AuthenticateEntity $authenticateEntity, CommandBus $commandBus)
    {
        $this->authenticateEntity = $authenticateEntity;
        $this->commandBus         = $commandBus;
    }

    /**
     * @return ViewModel
     */
    public function indexAction(): ViewModel
    {
        $this->authenticate();

        $this->layout()->setVariable(AuthenticateMapper::TOKEN, $this->authenticateEntity->getToken());
        $this->layout()->setVariable(MenuMapper::MENU_ROUTE, $this->getEvent()->getRouteMatch()->getMatchedRouteName());

        return new ViewModel();
    }

    public function authenticate(): void
    {
        try {
            $this->commandBus->handle(
                new AuthenticateCommand($this->getEvent())
            );
        } catch (\Exception $e) {
            $this->redirect()->toRoute('login');
        }
    }
}
