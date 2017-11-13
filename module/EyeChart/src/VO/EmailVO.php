<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/12/2017
 * (c) Eye Chart
 */
namespace EyeChart\VO;

use Assert\Assertion;

/**
 * Class EmailVO
 * @package EyeChart\VO
 */
final class EmailVO extends AbstractVO
{

    /** @var string */
    private $recipient;

    /** @var string */
    private $subject;

    /** @var string */
    private $body;

    /**
     * EmailVO constructor.
     *
     * @param string $recipient
     * @param string $subject
     * @param string $body
     */
    public function __construct($recipient, $subject, $body)
    {
        $this->setRecipient($recipient);
        $this->setSubject($subject);
        $this->setBody($body);
    }

    /**
     * @return string
     */
    public function recipient(): string
    {
        return $this->recipient;
    }

    /**
     * @return string
     */
    public function subject(): string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function body(): string
    {
        return $this->body;
    }

    /**
     * @param string $recipient
     */
    private function setRecipient(string $recipient): void
    {
        Assertion::email($recipient);

        $this->recipient = $recipient;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject): void
    {
        Assertion::minLength($subject, 1);

        $this->subject = $subject;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body): void
    {
        Assertion::minLength($body, 10);

        $this->body = $body;
    }
}
