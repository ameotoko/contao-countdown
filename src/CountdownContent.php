<?php

/**
 * @author Andrey Vinichenko <andrey.vinichenko@gmail.com>
 */

namespace Ameotoko\Countdown;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\ServiceAnnotation\ContentElement;
use Contao\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @ContentElement(CountdownContent::TYPE, category="includes")
 */
class CountdownContent extends AbstractContentElementController
{
    public const TYPE = 'countdown';

    protected function getResponse(Template $template, ContentModel $model, Request $request): ?Response
    {
        return $template->getResponse();
    }
}
