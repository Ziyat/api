<?php
/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 */

namespace box\repositories;


use box\entities\user\Token;

class TokenRepository
{
    public function save(Token $token)
    {
        if (!$token->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }
}