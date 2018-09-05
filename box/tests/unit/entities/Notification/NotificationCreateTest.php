<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\tests\unit\entities\Notification;

use box\entities\Meta;
use box\entities\notification\Notification;
use box\entities\shop\Brand;
use Codeception\Test\Unit;

class NotificationCreateTest extends Unit
{
    public function testSuccess()
    {
        $notification = Notification::create(
            Notification::TYPE_NEW_FOLLOWER,
            1,
            2
        );

        $this->assertEquals($notification->type_id,1);
        $this->assertEquals($notification->type,Notification::TYPE_NEW_FOLLOWER);
        $this->assertEquals($notification->from_id,2);
    }
}