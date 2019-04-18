<?php
namespace Otoi\Mail;

use Otoi\ConditionCheck;
use Otoi\Templates\TemplateInterface;
use Psr\Http\Message\UploadedFileInterface;

/**
 * Class MailConfig
 * @package Otoi\Models
 */
class Mail
{
    /**
     * @var EmailAddress
     */
    private $from;

    /**
     * @var EmailAddress[]
     */
    private $to;

    /**
     * @var EmailAddress[]
     */
    private $bcc = [];

    /**
     * @var EmailAddress[]
     */
    private $cc = [];

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $body;

    /**
     * @var mixed
     */
    private $condition;

    /**
     * @var ConditionCheck
     */
    private $condChecker;

    /**
     * @var string
     */
    private $template;

    /**
     * @var TemplateInterface
     */
    private $templator;

    /**
     * @var PlaceholderInterface[]
     */
    private $placeholders = [];

    /**
     * @var string[]
     */
    private $data = [];

    /**
     * @var string[];
     */
    private $filenames = [];

    /**
     * @var UploadedFileInterface[]
     */
    private $files = [];

    /**
     * @var DriverInterface
     */
    private $driver;

    /**
     * Mail constructor.
     * @param DriverInterface $driver
     * @param EmailAddress[] $to
     * @param EmailAddress $from
     * @param string $subject
     * @param string[] $files
     */
    public function __construct(DriverInterface $driver, array $to, EmailAddress $from, $subject, $files = [])
    {
        $this->driver = $driver;
        $this->from = $from;
        $this->to = $to;
        $this->subject = $subject;
        $this->filenames = $files;

        $this->placeHolderCheck($from);
        foreach ($to as $email) {
            $this->placeHolderCheck($email);
        }
    }

    /**
     * @param string
     */
    public function addFileName($name)
    {
        $this->files[] = $name;
    }

    /**
     * @param EmailAddress $address
     */
    public function addCC(EmailAddress $address)
    {
        $this->cc[] = $address;
        $this->placeHolderCheck($address);
    }

    /**
     * @param EmailAddress $address
     */
    public function addBCC(EmailAddress $address)
    {
        $this->bcc[] = $address;
        $this->placeHolderCheck($address);
    }

    /**
     * @param EmailAddress $address
     */
    public function addTo(EmailAddress $address)
    {
        $this->to[] = $address;
        $this->placeHolderCheck($address);
    }

    /**
     * @param $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @param $condition
     * @param ConditionCheck $checker
     */
    public function isConditional($condition, ConditionCheck $checker)
    {
        $this->condition = $condition;
        $this->condChecker = $checker;
    }

    /**
     * @param $template
     * @param TemplateInterface $templator
     */
    public function isTemplated($template, TemplateInterface $templator)
    {
        $this->template = $template;
        $this->templator = $templator;
    }

    /**
     * @param string[] $data
     * @param UploadedFileInterface[] $files
     * @return bool
     * @throws Exceptions\MailException
     */
    public function send($data, $files)
    {
        $this->data = $data;

        foreach ($files as $name => $file) {
            if (in_array($name, $this->filenames)) {
                $this->files[$name] = $file;
                $this->data[$name] = $file->getClientFilename();
            }
        }

        $this->fillPlaceholders();

        if (!$this->shouldSend()) {
            return false;
        }

        return $this->driver->send(
            $this->to,
            $this->from,
            $this->subject,
            $this->getBody(),
            $this->cc,
            $this->bcc,
            $this->files
        );
    }

    private function getBody()
    {
        if (!is_null($this->template)) {
            $this->body = $this->templator->render($this->template, ["data" => $this->data]);
        }
        return $this->body;
    }

    private function shouldSend()
    {
        return is_null($this->condition)
            ? true
            : $this->condChecker->check($this->condition, $this->data);
    }

    private function fillPlaceholders()
    {

        foreach ($this->placeholders as $placeholder) {
            $placeholder->resolve($this->data);
        }
    }

    private function placeHolderCheck($item)
    {
        if ($item instanceof PlaceholderInterface) {
            $this->placeholders[] = $item;
        }
    }

}