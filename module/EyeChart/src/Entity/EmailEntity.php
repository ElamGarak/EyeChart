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
     * @return EmailEntity
     */
    public function setFrom(string $from): EmailEntity
    {
        Assertion::email($from);

        $this->from = $from;

        return $this;
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
     * @return EmailEntity
     */
    public function setRecipients(array $recipients): EmailEntity
    {
        Assertion::notEmpty($recipients);

        $this->assertValidEmailAddresses($recipients);

        $this->recipients = $recipients;

        return $this;
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
     * @return EmailEntity
     */
    public function setRecipientNames(string $recipientName): EmailEntity
    {
        Assertion::notEmpty($recipientName);

        $this->recipientName = $recipientName;

        return $this;
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
     * @return EmailEntity
     */
    public function setCc(array $cc): EmailEntity
    {
        $this->assertValidEmailAddresses($cc);

        $this->cc = $cc;

        return $this;
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
     * @return EmailEntity
     */
    public function setBcc(array $bcc): EmailEntity
    {
        $this->assertValidEmailAddresses($bcc);

        $this->bcc = $bcc;

        return $this;
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
     * @return EmailEntity
     */
    public function setReplyTo(string $replyTo): EmailEntity
    {
        Assertion::email($replyTo);

        $this->replyTo = $replyTo;

        return $this;
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
     * @return EmailEntity
     */
    public function setSubject(string $subject): EmailEntity
    {
        $this->subject = $subject;

        return $this;
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
     * @return EmailEntity
     */
    public function setBody(string $body): EmailEntity
    {
        $this->body = $body;

        return $this;
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
     * @return EmailEntity
     */
    public function setAttachmentName(string $attachmentName): EmailEntity
    {
        $this->attachmentName = $attachmentName;

        return $this;
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
     * @return EmailEntity
     */
    public function setAttachmentPath(string $attachmentPath): EmailEntity
    {
        $this->attachmentPath = $attachmentPath;

        return $this;
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
     * @return EmailEntity
     */
    public function setAttachmentType(string $attachmentType): EmailEntity
    {
        $this->attachmentType = $attachmentType;

        return $this;
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
     * @return EmailEntity
     */
    public function addRecipient(string $recipient): EmailEntity
    {
        Assertion::email($recipient);

        array_push($this->recipients, $recipient);

        return $this;
    }

    /**
     * @param string $cc
     * @return EmailEntity
     */
    public function addCc(string $cc): EmailEntity
    {
        array_push($this->cc, $cc);

        return $this;
    }

    /**
     * @param string $bcc
     * @return EmailEntity
     */
    public function addBcc(string $bcc): EmailEntity
    {
        array_push($this->bcc, $bcc);

        return $this;
    }

    /**
     * @param array $recipients
     * @return EmailEntity
     */
    private function assertValidEmailAddresses(array $recipients): EmailEntity
    {
        foreach ($recipients as $recipient) {
            Assertion::email($recipient);
        }

        return $this;
    }
}
