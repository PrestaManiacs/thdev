<?php
/**
 * 2006-2021 THECON SRL
 *
 * NOTICE OF LICENSE
 *
 * DISCLAIMER
 *
 * YOU ARE NOT ALLOWED TO REDISTRIBUTE OR RESELL THIS FILE OR ANY OTHER FILE
 * USED BY THIS MODULE.
 *
 * @author    THECON SRL <contact@thecon.ro>
 * @copyright 2006-2021 THECON SRL
 * @license   Commercial
 */

class ThdevajaxModuleFrontController extends ModuleFrontController
{
    public $ajax = true;

    public function init()
    {
        if (!$this->isTokenValid()
            || !Module::isInstalled($this->module->name)
        ) {
            die('Bad token');
        }

        parent::init();

        if (Tools::getValue('action') == 'updateDebugMode') {
            $result = array(
                'error' => false
            );

            if (_PS_MODE_DEV_) {
                $this->module->updateDebugModeValueInMainFile('false');
            } else {
                $this->module->updateDebugModeValueInMainFile('true');
            }

            $this->ajaxDie(Tools::jsonEncode($result));
            exit;
        }

        if (Tools::getValue('action') == 'closePanel') {
            $result = array(
                'error' => false
            );
            if (!Configuration::updateValue('THDEV_LIVE_MODE', false)) {
                $result['error'] = true;
            }

            $this->ajaxDie(Tools::jsonEncode($result));
            exit;
        }
        exit;
    }
}
