<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/12/2017
 * (c) Eye Chart
 */

namespace EyeChart\Controller\Index;

use EyeChart\Controller\AbstractController;
use EyeChart\Mappers\AuthenticateMapper;
use EyeChart\Mappers\MenuMapper;
use Zend\View\Model\ViewModel;

/**
 * Class IndexController
 * @package EyeChart\Controller\Index
 */
final class IndexController extends AbstractController
{
    /**
     * @return ViewModel
     */
    public function indexAction(): ViewModel
    {
        $this->authenticate();

        $this->layout()->setVariable(AuthenticateMapper::TOKEN, parent::getAuthenticateEntity()->getToken());
        $this->layout()->setVariable(MenuMapper::MENU_ROUTE, $this->getMatchedRouteName());

        return new ViewModel();
    }
}
