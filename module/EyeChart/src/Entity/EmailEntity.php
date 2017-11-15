<?php
declare (strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/12/2017
 * (c) Eye Chart
 */

namespace EyeChart\Entity\Email;

use Assert\Assertion;
use EyeChart\Entity\AbstractEntity;

/**
 * Class EmailEntity
 * @package EyeChart\Entity\Email
 */
final class EmailEntity extends AbstractEntity
{
    /** @var string */
    protected $from = '';

    /** @var string[]  */
    protected $recipients = [];

    /** @var string */
    protected $recipientName = '';

    /** @var string[]  */
    protected $cc = [];

    /** @var string[]  */
    protected $bcc = [];

    /** @var string */
    protected $replyTo = '';

    /** @var string */
    protected $subject = '';

    /** @var string */
    protected $body = '';

    /** @var string */
    protected $attachmentName = '';

    /** @var string */
    protected $attachmentPath = '';

    /** @var string */
    protected $attachmentType = '';

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @param string $from
     */
    public function setFrom(string $from): void
    {
        Assertion::email($from);

        $this->from = $from;
    }

    /**
     * @return string[]
     */
    public function getRecipients(): array
    {
        return $this->recipients;
    }

    /**
     * @param string[] $recipients
     */
    public function setRecipients(array $recipients): void
    {
        Assertion::notEmpty($recipients);

        $this->assertValidEmailAddresses($recipients);

        $this->recipients = $recipients;
    }

    /**
     * @return string
     */
    public function getRecipientName(): string
    {
        return $this->recipientName;
    }

    /**
     * @param string $recipientName
     */
    public function setRecipientNames(string $recipientName): void
    {
        Assertion::notEmpty($recipientName);

        $this->recipientName = $recipientName;
    }

    /**
     * @return string[]
     */
    public function getCc(): array
    {
        return $this->cc;
    }

    /**
     * @param string[] $cc
     */
    public function setCc(array $cc): void
    {
        $this->assertValidEmailAddresses($cc);

        $this->cc = $cc;
    }

    /**
     * @return string[]
     */
    public function getBcc(): array
    {
        return $this->bcc;
    }

    /**
     * @param string[] $bcc
     */
    public function setBcc(array $bcc): void
    {
        $this->assertValidEmailAddresses($bcc);

        $this->bcc = $bcc;
    }

    /**
     * @return string
     */
    public function getReplyTo(): string
    {
        return $this->replyTo;
    }

    /**
     * @param string $replyTo
     */
    public function setReplyTo(string $replyTo): void
    {
        Assertion::email($replyTo);

        $this->replyTo = $replyTo;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getAttachmentName(): string
    {
        return $this->attachmentName;
    }

    /**
     * @param string $attachmentName
     */
    public function setAttachmentName(string $attachmentName): void
    {
        $this->attachmentName = $attachmentName;
    }

    /**
     * @return string
     */
    public function getAttachmentPath(): string
    {
        return $this->attachmentPath;
    }

    /**
     * @param string $attachmentPath
     */
    public function setAttachmentPath(string $attachmentPath): void
    {
        $this->attachmentPath = $attachmentPath;
    }

    /**
     * @return string
     */
    public function getAttachmentType(): string
    {
        return $this->attachmentType;
    }

    /**
     * @param string $attachmentType
     */
    public function setAttachmentType(string $attachmentType): void
    {
        $this->attachmentType = $attachmentType;
    }

    /** Helpers *******************************************************************************************************/
    /**
     * @return bool
     */
    public function hasAttachment(): bool
    {
        return ! empty($this->attachmentPath);
    }

    /**
     * @param string $recipient
     */
    public function addRecipient(string $recipient): void
    {
        Assertion::email($recipient);

        array_push($this->recipients, $recipient);
    }

    /**
     * @param string $cc
     */
    public function addCc(string $cc): void
    {
        array_push($this->cc, $cc);
    }

    /**
     * @param string $bcc
     */
    public function addBcc(string $bcc): void
    {
        array_push($this->bcc, $bcc);
    }

    /**
     * @param array $recipients
     */
    private function assertValidEmailAddresses(array $recipients): void
    {
        foreach ($recipients as $recipient) {
            Assertion::email($recipient);
        }
    }
}
