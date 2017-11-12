<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/12/2017
 * (c) Eye Chart
 */

namespace EyeChart\Command\Commands;

use Zend\Mvc\MvcEvent;

/**
 * Class AuthenticateCommand
 * @package EyeChart\Command\Commands
 */
class AuthenticateCommand extends AbstractCommand
{
    /** @var MvcEvent */
    private $event;

    /**
     * AuthenticateCommand constructor.
     * @param MvcEvent $event
     */
    public function __construct(MvcEvent $event)
    {
        $this->setEvent($event);
    }

    /**
     * @return MvcEvent
     */
    public function getEvent(): MvcEvent
    {
        return $this->event;
    }

    /**
     * @param MvcEvent $event
     */
    private function setEvent(MvcEvent $event): void
    {
        $this->event = $event;
    }
}
