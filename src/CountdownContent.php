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
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

/**
 * @ContentElement(CountdownContent::TYPE, category="includes")
 */
class CountdownContent extends AbstractContentElementController
{
    public const TYPE = 'countdown';
    private RequestStack $requestStack;
    private ScopeMatcher $scopeMatcher;

    public function __construct(RequestStack $requestStack, ScopeMatcher $scopeMatcher)
    {
        $this->requestStack = $requestStack;
        $this->scopeMatcher = $scopeMatcher;
    }

    protected function getResponse(Template $template, ContentModel $model, Request $request): ?Response
    {
        if ($this->scopeMatcher->isBackendRequest($this->requestStack->getCurrentRequest())) {
            return $this->getWildcard($model);
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
