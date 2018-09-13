<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\services;

use box\entities\user\Follower;
use box\entities\user\User;
use box\events\user\UserRegisterEvent;
use box\forms\auth\PasswordResetRequestForm;
use box\forms\auth\SignupForm;
use box\forms\user\AddressForm;
use box\forms\user\UserEditForm;
use box\repositories\CountryRepository;
use box\repositories\UserRepository;
use Yii;

class UserService
{
    private $users;
    private $event;
    private $transaction;
    private $countries;

    public function __construct(
        UserRepository $repository,
        UserRegisterEvent $event,
        TransactionManager $transaction,
        CountryRepository $countryRepository
    )
    {
        $this->users = $repository;
        $this->event = $event;
        $this->transaction = $transaction;
        $this->countries = $countryRepository;
    }

    public function signup(SignupForm $form)
    {
        $form->setParams();

        $user = User::signup($form);

        $this->users->save($user);

        $auth = Yii::$app->getAuthManager();

        $auth->assign($auth->getRole('user'), $user->id);

        if ($user->email) {
            $this->event->user = $user;
            $this->event->subject = 'Activation Code';
            $user->trigger($user::ACTIVATE_TOKEN, $this->event);
        };

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

    public function passwordReset(PasswordResetRequestForm $form): void
    {
        $user = $this->users->findByEmail($form->email);
        $user->generatePasswordResetToken();
        $this->users->save($user);

        $this->event->user = $user;
        $this->event->subject = 'Password reset Code';
        $user->trigger($user::PASSWORD_TOKEN, $this->event);
    }



    public function setFollow($follow_id, $follower_id): void
    {
        $follow = $this->users->find($follow_id);
        $follower = $this->users->find($follower_id);
        if ($follow->id === $follower->id) {
            throw new \LogicException('You can not follow yourself');
        }
        $follower->setFollow(
            $follow->id,
            $follow->private == User::PRIVATE ? Follower::NOT_APPROVE : Follower::APPROVE
        );
        $this->users->save($follower);
    }

    public function unFollow($follow_id, $follower_id): void
    {
        $follow = $this->users->find($follow_id);
        $follower = $this->users->find($follower_id);
        if ($follow->id === $follower->id) {
            throw new \LogicException('You can not unfollow yourself');
        }

        try {
            $this->transaction->wrap(function () use ($follower, $follow_id) {
                $following = $follower->followingAssignments;
                $follower->unFollow();
                $this->users->save($follower);
                foreach ($following as $i => $follow) {
                    if ($follow->user_id == $follow_id) {
                        unset($following[$i]);
                    }
                }
                $follower->followingAssignments = $following;
                $this->users->save($follower);
            });
        } catch (\Exception $e) {
            throw new \DomainException($e->getMessage());
        }
    }


    public function changePrivate($id)
    {
        $user = $this->users->find($id);
        $user->changePrivate();
        $this->users->save($user);
        return $user;
    }


    public function approve($follower_id, $user_id)
    {
        $user = $this->users->find($user_id);
        $follower = $this->users->find($follower_id);

        try {
            $followersAssignments = $user->followersAssignments;
            $user->followersAssignments = [];
            $this->users->save($user);
            foreach ($followersAssignments as $i => $followerAssignment) {
                if ($followerAssignment->follower_id == $follower->id && $followerAssignment->user_id == $user->id) {
                    $followerAssignment->status = $followerAssignment::APPROVE;
                    $followersAssignments[$i] = $followerAssignment;
                }
            }
            $user->followersAssignments = $followersAssignments;
            $this->users->save($user);
        } catch (\Exception $e) {
            throw new \DomainException($e->getMessage());
        }

    }

    public function disApprove($follower_id, $user_id)
    {
        $user = $this->users->find($user_id);
        $follower = $this->users->find($follower_id);

        try {
            $followersAssignments = $user->followersAssignments;
            $user->followersAssignments = [];
            $this->users->save($user);
            foreach ($followersAssignments as $i => $followerAssignment) {
                if ($followerAssignment->follower_id == $follower->id && $followerAssignment->user_id == $user->id) {
                    $followerAssignment->status = $followerAssignment::NOT_APPROVE;
                    $followersAssignments[$i] = $followerAssignment;
                }
            }
            $user->followersAssignments = $followersAssignments;
            $this->users->save($user);
        } catch (\Exception $e) {
            throw new \DomainException($e->getMessage());
        }

    }


    private function sendSms(User $user)
    {
        $sent = true;
        if (!$sent) {
            throw new \DomainException('send sms error');
        }
    }


    public function addressAdd($id, AddressForm $form)
    {
        $user = $this->users->find($id);
        $country = $this->countries->get($form->country_id);

        $user->setAddress(
            $form->name,
            $form->phone,
            $country->id,
            $form->address_line_1,
            $form->address_line_2,
            $form->city,
            $form->state,
            $form->index,
            $form->default
        );

        $this->users->save($user);
        return $user;
    }

    public function addressEdit($id, AddressForm $form)
    {
        $user = $this->users->find($id);
        $country = $this->countries->get($form->country_id);
        $user->changeAddress(
            $id,
            $form->name,
            $form->phone,
            $country->id,
            $form->address_line_1,
            $form->address_line_2,
            $form->city,
            $form->state,
            $form->index,
            $form->default
        );
        $this->users->save($user);
        return $user;
    }

    public function addressRemove($user_id, $address_id)
    {
        $user = $this->users->find($user_id);
        $addresses = $user->addresses;
        foreach ($addresses as $k => $address)
        {
            if($address->id == $address_id)
            {
                unset($addresses[$k]);
            }
        }
        $user->addresses = $addresses;
        $this->users->save($user);
    }

}