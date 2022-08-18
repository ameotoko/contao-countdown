<?php

/**
 * @author Andrey Vinichenko <andrey.vinichenko@gmail.com>
 */

use Ameotoko\Countdown\CountdownModule;
use Doctrine\DBAL\Types\Types;

$GLOBALS['TL_DCA']['tl_module']['palettes'][CountdownModule::TYPE] = '{title_legend},name,headline,type;{config_legend},endDate,expire,reload;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';

$GLOBALS['TL_DCA']['tl_module']['fields']['endDate'] = [
    'exclude' => true,
    'inputType' => 'text',
    'eval' => [
        'rgxp' => 'datim',
        'mandatory' => true,
        'datepicker' => true,
        'tl_class' => 'w50 wizard'
    ],
    'sql' => ['type' => Types::INTEGER, 'unsigned' => true, 'notnull' => false]
];

$GLOBALS['TL_DCA']['tl_module']['fields']['expire'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'm12 w50'],
    'sql' => ['type' => Types::STRING, 'length' => 1, 'fixed' => true, 'default' => '']
];

$GLOBALS['TL_DCA']['tl_module']['fields']['reload'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'm12 w50 clr'],
    'sql' => ['type' => Types::STRING, 'length' => 1, 'fixed' => true, 'default' => '']
];
