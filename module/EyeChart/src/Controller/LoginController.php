<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/12/2017
 * (c) Eye Chart
 */
namespace EyeChart\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class LoginController
 * @package EyeChart\Controller
 */
final class LoginController extends AbstractActionController
{
    /**
     * @return ViewModel
     */
    public function indexAction(): ViewModel
    {
        $messages = $this->params()->fromPost('messages');

        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        $viewModel->setVariable('messages', json_encode($messages));

        return $viewModel;
    }

    /**
     * redirect back to login page
     */
    public function logoutAction(): void
    {
        $this->redirect()->toRoute('login');
    }
}
