<?php

/**
 * @author Andrey Vinichenko <andrey.vinichenko@gmail.com>
 */

namespace Ameotoko\Countdown;

use Contao\Config;
use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\CoreBundle\ServiceAnnotation\ContentElement;
use Contao\Date;
use Contao\StringUtil;
use Contao\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @ContentElement(CountdownContent::TYPE, category="includes")
 */
class CountdownContent extends AbstractContentElementController
{
    public const TYPE = 'countdown';
    private ScopeMatcher $scopeMatcher;

    public function __construct(ScopeMatcher $scopeMatcher)
    {
        $this->scopeMatcher = $scopeMatcher;
    }

    protected function getResponse(Template $template, ContentModel $model, Request $request): ?Response
    {
        if ($this->scopeMatcher->isBackendRequest($request)) {
            return $this->getWildcard($model);
        }

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

    protected function getWildcard(ContentModel $model): Response
    {
        $data = StringUtil::deserialize($model->headline);

        return $this->render('@AmeotokoCountdown/be_countdown.html.twig', [
            'headline' => \is_array($data) ? $data['value'] : $data,
            'tstamp' => Date::parse(Config::get('datimFormat'), $model->endDate),
            'expired' => $model->endDate < time()
        ]);
    }
}
