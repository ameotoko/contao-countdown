<?php

/**
 * @author Andrey Vinichenko <andrey.vinichenko@gmail.com>
 */

namespace Ameotoko\Countdown;

use Contao\Config;
use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\ServiceAnnotation\ContentElement;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\Date;
use Contao\StringUtil;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @ContentElement(CountdownContent::TYPE, category="includes")
 */
class CountdownContent extends AbstractContentElementController
{
    use CountdownTrait;

    public const TYPE = 'countdown';

    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        if ($this->container->get('contao.routing.scope_matcher')->isBackendRequest($request)) {
            return $this->getBackendWildcard($model);
        }

        return $this->getCountdownResponse($template, $model);
    }

    protected function getBackendWildcard(ContentModel $model): Response
    {
        $data = StringUtil::deserialize($model->headline);

        return $this->render('@AmeotokoCountdown/be_countdown.html.twig', [
            'headline' => \is_array($data) ? $data['value'] : $data,
            'tstamp' => Date::parse(Config::get('datimFormat'), $model->endDate),
            'expired' => $model->endDate < time()
        ]);
    }
}
