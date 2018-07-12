<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\services;
use Yii;
use box\entities\user\User;
use box\forms\auth\SignupForm;
use box\forms\user\UserEditForm;
use box\repositories\UserRepository;

class UserService
{
    private $users;

    public function __construct(UserRepository $repository)
    {
        $this->users = $repository;
    }

    public function signup(SignupForm $form)
    {
        $form->setParams();


        $user = User::signup($form);

        $this->users->save($user);

        $auth = Yii::$app->getAuthManager();

        $auth->assign($auth->getRole('user'), $user->id);

        if ($user->email) $this->sendEmail($user);

        if ($user->phone) $this->sendSms($user);

        return $user;
    }

    public function edit($id, UserEditForm $form)
    {
        // Profile Edit
        $profile = $this->users->findProfile($id);
        $profile->edit(
            $form->name,
            $form->lastName,
            $form->birthDate,
            $form->photo
        );

        // User Edit
        $user = $this->users->find($id);
        $user->edit(
            $form->email,
            $form->phone,
            $form->password,
            $profile
        );
        $this->users->save($user);

        return $user;
    }

    private function sendEmail(User $user)
    {

        $templateHtml = 'activateToken-html';
        $templateText = 'activateToken-text';

        $sent = Yii::$app
            ->mailer
            ->compose(
                ['html' => $templateHtml, 'text' => $templateText],
                ['user' => $user]
            )
            ->setFrom(['noreply@api.watchvaultapp.com' => \Yii::$app->name])
            ->setTo($user->email)
            ->setSubject('Activation Code')
            ->send();
        if (!$sent) {
            throw new \DomainException('send email error', 405);
        }
    }

    private function sendSms(User $user)
    {
        $sent = true;

        if (!$sent) {
            throw new \DomainException('send sms error', 405);
        }
    }
}