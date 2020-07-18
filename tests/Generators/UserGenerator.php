<?php
/**
 * Description of UserGenerator.php
 * @copyright Copyright (c) MISTER.AM, LLC
 */

namespace Tests\Generators;

use App\Models\User;

class UserGenerator
{
    public static function createUser()
    {
        return factory(User::class)->create();
    }

}
