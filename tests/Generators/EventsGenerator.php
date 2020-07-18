<?php
/**
 * Description of EventGenerator.php
 * @copyright Copyright (c) MISTER.AM, LLC
 */

namespace Tests\Generators;

use App\Models\Event;

class EventsGenerator
{
    public static function createEvents(int $amount = 5)
    {
        return factory(Event::class, $amount)->create();
    }
}
