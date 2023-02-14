<?php

/**
 * @author Andrey Vinichenko <andrey.vinichenko@gmail.com>
 */

namespace Ameotoko\Countdown;

use Contao\Config;
use Contao\ContentModel;
use Contao\Date;
use Contao\ModuleModel;
use Contao\Template;
use Symfony\Component\HttpFoundation\Response;

trait CountdownTrait
{
    /**
     * @param ContentModel|ModuleModel $model
     */
    protected function getCountdownResponse(Template $template, $model): Response
    {
        // Don't show the countdown, if the date is in the past
        if ((int) $model->endDate < time() && $model->expire) {
            return new Response();
        }

        $GLOBALS['TL_CSS'][] = $template->asset('flip.min.css', 'ameotoko_countdown');
        $GLOBALS['TL_JAVASCRIPT'][] = $template->asset('flip.min.js', 'ameotoko_countdown');

        $format = 'Y-m-d\\TH:i:s.000\\Z';

        try {
            $template->endDate = (new \DateTime('@' . $model->endDate, new \DateTimeZone(Config::get('timeZone'))))
                ->format($format);
        } catch (\Exception $e) {
            $template->endDate = Date::parse($format);
        }

        return $template->getResponse();
    }
}
