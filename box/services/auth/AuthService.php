<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\services\auth;


use box\entities\User;
use box\forms\auth\SignupForm;
use box\repositories\UserRepository;
use Yii;

class AuthService
{
    private $users;

    public function __construct(UserRepository $repository)
    {
        $this->users = $repository;
    }

    public function signup(SignupForm $form)
    {
        $form->setParams();

        $user = User::signup($form->email, $form->phone, $form->password);

        $this->users->save($user);

        if ($user->email) $this->sendEmail($user);

        if ($user->phone) $this->sendSms($user);

        return $user;
    }

    private function sendEmail(User $user): void
    {

        $templateHtml = 'activateToken-html';
        $templateText = 'activateToken-text';

        $sent = Yii::$app
            ->mailer
            ->compose(
                ['html' => $templateHtml, 'text' => $templateText],
                ['user' => $user]
            )
            ->setFrom(['noreply@watch-valt.com' => \Yii::$app->name])
            ->setTo($user->email)
            ->setSubject('Activation Code')
            ->send();
        if (!$sent) {
            throw new \DomainException('send email error', 405);
        }
    }

    private function sendSms(User $user): void
    {
        $sent = true;

        if (!$sent) {
            throw new \DomainException('send sms error', 405);
        }
    }
}