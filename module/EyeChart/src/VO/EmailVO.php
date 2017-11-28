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
    protected $recipient;

    /** @var string */
    protected $subject;

    /** @var string */
    protected $body;

    /**
     * @return VOInterface|EmailVO
     */
    public static function build(): VOInterface
    {
        return new self;
    }

    /**
     * @return string
     */
    public function getRecipient(): string
    {
        return $this->recipient;
    }

    /**
     * @return string
     */
    public function getSbject(): string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $recipient
     */
    public function setRecipient(string $recipient): void
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
