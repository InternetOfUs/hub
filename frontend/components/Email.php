<?php

namespace frontend\components;

use Yii;

/**
 * Wrapper for ElasticMail library.
 * Allows to create a simple by assigning from, to, subject and body information.
 * It requires the app param 'elasticmail.api.key'.
 */
class Email {

    private $subject;
    private $fromEmail;
    private $fromName;
    private $toEmail;
    private $toName;
    private $textBody;
    private $htmlBody;

    public static function build() {
        return new self();
    }

    public function setSubject($subject) {
        $this->subject = $subject;
        return $this;
    }

    public function setFrom($fromEmail, $fromName=null) {
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
        return $this;
    }

    public function setTo($toEmail, $toName=null) {
        $this->toEmail = $toEmail;
        $this->toName = $toName;
        return $this;
    }

    public function setTextBody($textBody) {
        $this->textBody = $textBody;
        $this->htmlBody = null;
        return $this;
    }

    public function setHtmlBody($htmlBody) {
        $this->textBody = null;
        $this->htmlBody = $htmlBody;
        return $this;
    }

    public function send() {

        if (!isset(Yii::$app->params['elasticmail.api.key']) || !Yii::$app->params['elasticmail.api.key']) {
            Yii::warning('Can not send email: required env variable [elasticmail.api.key] is missing', 'uhopper.email');
            return false;
        }

        try {
            $configuration = new \ElasticEmailClient\ApiConfiguration([
                'apiUrl' => 'https://api.elasticemail.com/v2/',
                'apiKey' => Yii::$app->params['elasticmail.api.key']
            ]);

            $client = new \ElasticEmailClient\ElasticClient($configuration);

            $client->Email->Send(
                $this->subject,
                $this->fromEmail,
                $this->fromName,
                $this->fromEmail,
                $this->fromName,
                $this->fromEmail,
                $this->fromName,
                $this->fromEmail,
                $this->fromName,
                [$this->toEmail],
                [],
                [],
                [],
                [],
                [],
                null,
                null,
                null,
                $this->htmlBody,
                $this->textBody
            );
            return 'OK';
        } catch (\Exception $e) {
            Yii::error('Can not send email: something went wrong', 'uhopper.email');
            return false;
        }
    }

}
