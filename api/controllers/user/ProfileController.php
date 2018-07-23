<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace api\controllers\user;

use api\controllers\BearerController;
use box\entities\user\User;
use box\helpers\UserHelper;
use box\services\UserService;
use box\forms\user\UserEditForm;
use Yii;

class ProfileController extends BearerController
{

    protected $service;

    public function __construct($id, $module, UserService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }
    /**
     * @SWG\Get(
     *     path="/profile",
     *     tags={"Profile"},
     *     description="Returns profile info",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/Profile")
     *     ),
     *     security={{"Bearer": {}}}
     * )
     */

    public function actionIndex()
    {
        return $this->serialize($this->findUser(Yii::$app->user->id));
    }

    /**
     * @SWG\Post(
     *     path="/profile/edit",
     *     tags={"Profile edit"},
     *     description="Returns profile info. To send an image add in the header content-type: multipart/form-data",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/Profile")
     *     ),
     *     @SWG\SecurityScheme(
     *         securityDefinition="Bearer",
     *         type="apiKey",
     *         name="Authorization",
     *         in="header"
     *     ),
     *     security={{"Bearer": {}}}
     * )
     */

    public function actionEdit()
    {
        $user = $this->findUser(Yii::$app->user->id);
        $form = new UserEditForm($user);
        $form->load(Yii::$app->request->bodyParams, '');

        if($form->validate())
        {
            try {
                $user = $this->service->edit($user->id, $form);
                return $this->serialize($user);
            } catch (\DomainException $e) {
                return [
                    'field' => 'signup',
                    'message' => $e->getMessage(),
                ];
            }
        }
        return $form;
    }


    protected function verbs()
    {
        return [
            'index' => ['get'],
            'edit' => ['post'],
        ];
    }

    protected function findUser($id)
    {
        return User::findOne($id);
    }


    protected function serialize(User $user)
    {
        return[
            'id' => $user->id,
            'email' => $user->email,
            'phone' => $user->phone,
            'createdAt' => $user->created_at,
            'status' => UserHelper::getStatus($user->status),
            'name' => $user->profile->name,
            'lastName' => $user->profile->last_name,
            'birthDate' => $user->profile->date_of_birth,
            'photo' => $user->profile->getPhoto()
        ];
    }


}
/**
 *  @SWG\Definition(
 *     definition="Profile",
 *     type="object",
 *     @SWG\Property(property="id", type="integer"),
 *     @SWG\Property(property="createdAt", type="integer"),
 *     @SWG\Property(property="status", type="string"),
 *     @SWG\Property(property="name", type="string"),
 *     @SWG\Property(property="lastName", type="string"),
 *     @SWG\Property(property="birthDate", type="string"),
 *     @SWG\Property(property="photo", type="string"),
 * )
 */

