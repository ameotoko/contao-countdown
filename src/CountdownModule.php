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
    use CountdownTrait;

    public const TYPE = 'countdown';

    protected function getResponse(Template $template, ModuleModel $model, Request $request): ?Response
    {
        return $this->getCountdownResponse($template, $model);
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
