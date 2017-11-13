<?php
declare (strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/12/2017
 * (c) Eye Chart
 */

namespace EyeChart\Model\Email;

use EyeChart\Entity\Email\EmailEntity;
use EyeChart\VO\EmailVO;
use Zend\Mail\Message as Mail;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Part;
use Zend\Mime\Mime;
use Zend\Mime\Message as MimeMessage;
use Zend\Config\Config;

/**
 * Class EmailModel
 * @package EyeChart\Model\Email
 */
final class EmailModel
{

    /** @var EmailEntity */
    private $emailEntity;

    /** @var Mail */
    private $mail;

    /** @var Smtp */
    private $transport;

    /** @var SmtpOptions */
    private $smtpOptions;

    /** @var Part */
    private $part;

    /** @var MimeMessage */
    private $bodyParts;

    /** @var Part */
    private $attachments;

    /** @var Config */
    private $environments;

    /** @var array */
    private $emailSettings = [];

    /** @var bool */
    private $overrideEnabled = false;

    /**  @var array */
    private $systemEnvironment = [];

    /**
     * EmailModel constructor.
     *
     * @param EmailEntity $emailEntity
     * @param Mail        $mail
     * @param SmtpOptions $smtpOptions
     * @param Config      $environments
     * @param array       $emailSettings
     */
    public function __construct(
        EmailEntity $emailEntity,
        Mail        $mail,
        SmtpOptions $smtpOptions,
        Config      $environments,
        array       $emailSettings
    ) {
        $this->emailEntity   = $emailEntity;
        $this->mail          = $mail;
        $this->smtpOptions   = $smtpOptions;
        $this->environments  = $environments;
        $this->emailSettings = $emailSettings;

        $this->setSmtpOptions($this->emailSettings['options']);
        $this->setEmailOverride();
    }

    public function send(EmailVO $emailVO): void
    {
        $this->emailEntity->setReplyTo($this->emailSettings['emails']['noReply']);
        $this->emailEntity->setFrom($this->emailSettings['emails']['from']);

        $this->emailEntity->addRecipient($emailVO->recipient());
        $this->emailEntity->setSubject($emailVO->subject());
        $this->emailEntity->setBody($emailVO->body());

        $this->prepareSend();

        $this->transport->send($this->mail);
    }

    private function prepareSend(): void
    {
        $this->prepareHeader();
        $this->prepareBody();
        $this->prepareAttachments();
        $this->prepareTransport();
    }

    private function prepareHeader(): void
    {
        $this->mail->setFrom($this->emailEntity->getFrom());
        $this->mail->setReplyTo($this->emailEntity->getReplyTo());

        $this->setRecipients($this->emailEntity->getRecipients(), $this->emailEntity->getRecipientName());
        $this->setCc($this->emailEntity->getCc());
        $this->setBcc($this->emailEntity->getBcc());

        $this->mail->setSubject($this->emailEntity->getSubject());
    }

    private function prepareBody(): void
    {
        $this->part          = new Part($this->emailEntity->getBody());
        $this->part->type    = Mime::TYPE_HTML;
        $this->part->charset = "UTF-8";

        $this->bodyParts = new MimeMessage();
        $this->bodyParts->addPart($this->part);
        $this->mail->setBody($this->bodyParts);
    }


    private function prepareAttachments(): void
    {
        if ($this->emailEntity->hasAttachment() === false) {
            return;
        }

        $contents = fopen($this->emailEntity->getAttachmentPath(), 'r');

        $attachment = new Part($contents);

        $attachment->setType($this->emailEntity->getAttachmentType());
        $attachment->setFileName($this->emailEntity->getAttachmentName());
        $attachment->setEncoding(Mime::ENCODING_BASE64);
        $attachment->setDisposition(Mime::DISPOSITION_ATTACHMENT);

        $this->bodyParts->addPart($this->attachments);
    }

    private function prepareTransport(): void
    {
        $this->transport = new Smtp();
        $this->transport->setOptions($this->smtpOptions);
    }

    /**
     * @param string[] $recipients
     * @param string|null $name
     */
    public function setRecipients(array $recipients, string $name): void
    {
        if ($this->overrideEnabled) {
            $name       = "Developer";
            $recipients = $this->getEmailOverride();
        }

        $this->mail->addTo($recipients, $name);
    }

    /**
     * @param string[] $cc
     */
    public function setCc(array $cc): void
    {
        if ($this->overrideEnabled === true) {
            return;
        }

        $this->mail->addCc($cc);
    }

    /**
     * @param string[] $bcc
     */
    public function setBcc($bcc): void
    {
        if ($this->overrideEnabled === true) {
            return;
        }

        $this->mail->addBcc($bcc);
    }

    private function setEmailOverride(): void
    {
        $system  = gethostname();
        $systems = $this->environments->get('systems')->toArray();

        if (array_key_exists($system, $systems)) {
            $environment = $systems[$system];

            $this->systemEnvironment = $this->environments->toArray()[$environment];

            $this->overrideEnabled = $this->systemEnvironment['emailOverride']['enabled'];
        }
    }

    /**
     * @return string[]
     */
    private function getEmailOverride(): array
    {
        return $this->systemEnvironment['emailOverride']['recipients'];
    }

    /**
     * @param string[] $options
     */
    private function setSmtpOptions(array $options): void
    {
        $this->smtpOptions->setName($options['name']);
        $this->smtpOptions->setHost($options['host']);
        $this->smtpOptions->setPort($options['port']);
    }
}
