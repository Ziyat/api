<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\forms\user;


use box\forms\CompositeForm;

class UserEditForm extends CompositeForm
{

    protected function internalForms(): array
    {
        return ['profile'];
    }
}