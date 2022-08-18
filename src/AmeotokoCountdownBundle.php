<?php

/**
 * @author Andrey Vinichenko <andrey.vinichenko@gmail.com>
 */

namespace Ameotoko\Countdown;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AmeotokoCountdownBundle extends Bundle
{
    public function getPath(): string
    {
        return dirname(__DIR__);
    }
}
