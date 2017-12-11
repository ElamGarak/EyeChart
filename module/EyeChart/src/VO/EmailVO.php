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
final class EmailVO extends VO
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
    public function getSubject(): string
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
     * @return EmailVO
     */
    public function setRecipient(string $recipient): EmailVO
    {
        Assertion::email($recipient);

        $this->recipient = $recipient;

        return $this;
    }

    /**
     * @param string $subject
     * @return EmailVO
     */
    public function setSubject(string $subject): EmailVO
    {
        Assertion::minLength($subject, 1);

        $this->subject = $subject;

        return $this;
    }

    /**
     * @param string $body
     * @return EmailVO
     */
    public function setBody(string $body): EmailVO
    {
        Assertion::minLength($body, 10);

        $this->body = $body;

        return $this;
    }
}
