<?php

/**
 * @author Andrey Vinichenko <andrey.vinichenko@gmail.com>
 */

namespace Ameotoko\Countdown;

use Contao\Config;
use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\ServiceAnnotation\FrontendModule;
use Contao\Date;
use Contao\ModuleModel;
use Contao\StringUtil;
use Contao\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @FrontendModule(CountdownModule::TYPE, category="miscellaneous")
 */
class CountdownModule extends AbstractFrontendModuleController
{
    public const TYPE = 'countdown';

    protected function getResponse(Template $template, ModuleModel $model, Request $request): ?Response
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

    protected function getBackendWildcard(ModuleModel $module): Response
    {
        $data = StringUtil::deserialize($module->headline);

        $requestToken = $this->container->get('contao.csrf.token_manager')->getDefaultTokenValue();

        $href = $this->container->get('router')->generate(
            'contao_backend',
            ['do' => 'themes', 'table' => 'tl_module', 'act' => 'edit', 'id' => $module->id, 'rt' => $requestToken]
        );

        return $this->render('@AmeotokoCountdown/be_countdown.html.twig', [
            'headline' => \is_array($data) ? $data['value'] : $data,
            'tstamp' => Date::parse(Config::get('datimFormat'), $module->endDate),
            'expired' => $module->endDate < time(),
            'href' => $href,
            'id' => $module->id
        ]);
    }
}
