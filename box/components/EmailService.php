<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\components;

use box\events\user\UserRegisterEvent;
use yii\base\Component;

/**
 * Class EmailService
 * @package box\components
 */
class EmailService extends Component
{
    private $mailer;

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->mailer = \Yii::$app->mailer;
    }

    /**
     * @param $event
     * @return bool
     */
    public function sendActivateToken(UserRegisterEvent $event)
    {
        return $this->mailer->compose(
            [
                'html' => 'activateToken-html',
                'text' => 'activateToken-text'
            ],
            [
                'user' => $event->user,
                'subject' => $event->subject
            ]
        )
            ->setFrom(['noreply@api.watchvaultapp.com' => \Yii::$app->name])
            ->setTo($event->user->email)
            ->setSubject($event->subject)
            ->send();
    }

    /**
     * @param $event
     * @return bool
     */
    public function sendResetPasswordToken(UserRegisterEvent $event)
    {
        return $this->mailer->compose(
            [
                'html' => 'passwordResetToken-html',
                'text' => 'passwordResetToken-text'
            ],
            [
                'user' => $event->user,
                'subject' => $event->subject
            ]
        )
            ->setFrom(['noreply@api.watchvaultapp.com' => \Yii::$app->name])
            ->setTo($event->user->email)
            ->setSubject($event->subject)
            ->send();
    }
}