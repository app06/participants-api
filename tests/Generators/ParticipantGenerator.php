<?php
/**
 * Description of ParticipantGenerator.php
 * @copyright Copyright (c) MISTER.AM, LLC
 */

namespace Tests\Generators;

use App\Models\Participant;

class ParticipantGenerator
{
    public static function createParticipant()
    {
        return factory(Participant::class)->create();
    }

    public static function makeParticipant()
    {
        return factory(Participant::class)->make();
    }
}
