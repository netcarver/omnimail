<?php

namespace Omnimail;

class Email implements EmailInterface
{
    /**
     * @var array
     */
    protected $to = [];

    /**
     * @var array
     */
    protected $cc = [];

    /**
     * @var array
     */
    protected $bcc = [];

    /**
     * @var array
     */
    protected $replyTo = [];

    /**
     * @var array
     */
    protected $attachments = [];

    /**
     * @var array
     */
    protected $from;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $textBody;

    /**
     * @var string
     */
    protected $htmlBody;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $tag;

    /**
     * @var bool
     */
    protected $trackOpens = false;
    
    /**
     * @var array
     */
    protected $metas = [];


    /**
     * Generate your own $id and pass it in to override the class' own id generation.
     *
     * The ID is also added as an entry to the meta data array.
     *
     * @param string $id
     */
    public function __construct($id = null) {
        $prefix = gethostname();
        $this->id = $id ?? uniqid("$prefix-", true);
        $this->resetMetas();
    }


    /**
     * @return $this
     */
    public function resetMetas() {
        $this->metas = ['id' => $this->getID() ];
        return $this;
    }


    /**
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function addMeta(string $key, string $value) {
        $this->metas[$key] = $value;
        return $this;
    }


    /**
     * @return array
     */
    public function getMetas() {
        return $this->metas;
    }


    /**
     * @return string
     */
    public function getID() {
        return $this->id;
    }


    /**
     * @param string $tag
     * @return $this
     */
    public function setTag(string $tag) {
        $this->tag = $tag;
        return $this;
    }


    /**
     * @return string
     */
    public function getTag() {
        return $this->tag;
    }


    /**
     * @param bool $track
     * @return $this
     */
    public function setTrackOpens(bool $track)
    {
        $this->trackOpens = $track;
        return $this;
    }


    /**
     * @return bool
     */
    public function getTrackOpens()
    {
        return $this->trackOpens;
    }


    /**
     * @return string
     */
    public function getTextBody()
    {
        return $this->textBody;
    }

    /**
     * @param string $textBody
     * @return $this
     */
    public function setTextBody($textBody)
    {
        $this->textBody = $textBody;
        return $this;
    }

    /**
     * @return string
     */
    public function getHtmlBody()
    {
        return $this->htmlBody;
    }

    /**
     * @param string $htmlBody
     * @return $this
     */
    public function setHtmlBody($htmlBody)
    {
        $this->htmlBody = $htmlBody;
        return $this;
    }

    /**
     * @return array
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param string $email
     * @param string|null $name
     * @return $this
     */
    public function setFrom($email, $name = null)
    {
        $this->from = ['email' => $email, 'name' => $name];
        return $this;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return array
     */
    public function getTos()
    {
        return $this->to;
    }

    /**
     * @param string $email
     * @param string|null $name
     * @return $this
     */
    public function addTo($email, $name = null)
    {
        $this->to[] = [
            'email' => $email,
            'name' => $name
        ];
        return $this;
    }

    /**
     * @return array
     */
    public function getCcs()
    {
        return $this->cc;
    }

    /**
     * @param string $email
     * @param string|null $name
     * @return $this
     */
    public function addCc($email, $name = null)
    {
        $this->cc[] = [
            'email' => $email,
            'name' => $name
        ];
        return $this;
    }

    /**
     * @return array
     */
    public function getBccs()
    {
        return $this->bcc;
    }

    /**
     * @param string $email
     * @param string|null $name
     * @return $this
     */
    public function addBcc($email, $name = null)
    {
        $this->bcc[] = [
            'email' => $email,
            'name' => $name
        ];
        return $this;
    }

    /**
     * @return array
     */
    public function getReplyTos()
    {
        return $this->replyTo;
    }

    /**
     * @return array
     */
    public function getReplyTo()
    {
        return count($this->replyTo) > 0 ? $this->replyTo[0] : [];
    }

    /**
     * @param string $email
     * @param string|null $name
     * @return $this
     */
    public function addReplyTo($email, $name = null)
    {
        $this->replyTo[] = [
            'email' => $email,
            'name' => $name
        ];
        return $this;
    }

    /**
     * @param string $email
     * @param string|null $name
     * @return $this
     */
    public function setReplyTo($email, $name = null)
    {
        $this->replyTo = [[
            'email' => $email,
            'name' => $name
        ]];
        return $this;
    }

    /**
     * @return array
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param AttachmentInterface $attachment
     * @return $this
     */
    public function addAttachment(AttachmentInterface $attachment)
    {
        $this->attachments[] = $attachment;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $attachments = $this->getAttachments();
        if (count($attachments)) {
            /**
             * @var int $key
             * @var AttachmentInterface $attachment
             */
            foreach ($attachments as $key => $attachment) {
                $attachments[$key] = $attachment->toArray();
            }
        }

        return [
            'textBody' => $this->getTextBody(),
            'htmlBody' => $this->getHtmlBody(),
            'from' => $this->getFrom(),
            'subject' => $this->getSubject(),
            'attachments' => $attachments,
            'tos' => $this->getTos(),
            'replyTos' => $this->getReplyTos(),
            'ccs' => $this->getCcs(),
            'bccs' => $this->getBccs(),
            'id' => $this->getID(),
            'metas' => $this->getMetas(),
            'tag' => $this->getTag(),
            'trackOpens' => $this->getTrackOpens(),
        ];
    }
}
