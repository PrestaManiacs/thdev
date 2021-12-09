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

if (!defined('_PS_VERSION_')) {
    exit;
}

class Thdev extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'thdev';
        $this->tab = 'administration';
        $this->version = '1.2.0';
        $this->author = 'Presta Maniacs';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Dev Shop Information');
        $this->description = $this->l('Show a panel with useful page information.');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        if (!parent::install() || !$this->registerHooks() || !$this->installDemo()) {
            return false;
        }

        return true;
    }

    public function registerHooks()
    {
        if (!$this->registerHook('actionFrontControllerSetMedia') ||
            !$this->registerHook('header') ||
            !$this->registerHook('actionAdminControllerSetMedia')) {
            return false;
        }

        if ($this->getPsVersion() == '7') {
            if (!$this->registerHook('displayBeforeBodyClosingTag')) {
                return false;
            }
        } else {
            if (!$this->registerHook('displayFooter')) {
                return false;
            }
        }

        return true;
    }

    private function installDemo()
    {
        Configuration::updateValue('THDEV_LIVE_MODE', false);
        Configuration::updateValue('THDEV_ADMIN_ONLY', false);
        Configuration::updateValue('THDEV_ICON', 'material_icons');
        Configuration::updateValue('THDEV_BACK_COLOR', '#FFFFFF');
        Configuration::updateValue('THDEV_TEXT_COLOR', '#000000');
        Configuration::updateValue('THDEV_BORDER_COLOR', '#2fb5d2');
        Configuration::updateValue('THDEV_BACK_TITLE_COLOR', '#e2e2e2');
        Configuration::updateValue('THDEV_ACTIVATE_IP', true);
        Configuration::updateValue('THDEV_ACTIVATE_CONTROLLER', true);
        Configuration::updateValue('THDEV_ACTIVATE_LANGUAGE', false);
        Configuration::updateValue('THDEV_ACTIVATE_CURRENCY', false);
        Configuration::updateValue('THDEV_ACTIVATE_ID_SHOP', false);
        Configuration::updateValue('THDEV_ACTIVATE_ID_CART', false);
        Configuration::updateValue('THDEV_ACTIVATE_ID_CUSTOMER', false);
        Configuration::updateValue('THDEV_ACTIVATE_ID_GUEST', false);
        Configuration::updateValue('THDEV_ACTIVATE_ADDRESS', false);
        Configuration::updateValue('THDEV_ACTIVATE_GO_TO_MAIN_PAGE', false);

        return true;
    }

    public function uninstall()
    {
        $form_values = $this->getConfigFormValues();
        foreach (array_keys($form_values) as $key) {
            Configuration::deleteByName($key);
        }

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        $message = '';
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitThdevModule')) == true) {
            $this->postProcess();
            if (count($this->_errors)) {
                $message = $this->displayError($this->_errors);
            } else {
                $message = $this->displayConfirmation($this->l('Successfully saved!'));
            }
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $maniacs = $this->context->smarty->fetch($this->local_path.'views/templates/admin/maniacs.tpl');

        return $message.$maniacs.$this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitThdevModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Use this module:'),
                        'desc' => $this->l('Check here to switch the module to live mode'),
                        'name' => 'THDEV_LIVE_MODE',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Use this module for admin only:'),
                        'name' => 'THDEV_ADMIN_ONLY',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'th_title',
                        'label' => '',
                        'name' => $this->l('Display Informations'),
                    ),
                    array(
                        'type' => 'th_sub_title',
                        'label' => '',
                        'name' => $this->l('Connection'),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('IPv4:'),
                        'name' => 'THDEV_ACTIVATE_IP',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Controller:'),
                        'name' => 'THDEV_ACTIVATE_CONTROLLER',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'th_sub_title',
                        'label' => '',
                        'name' => $this->l('Shop'),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Language:'),
                        'name' => 'THDEV_ACTIVATE_LANGUAGE',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Currency:'),
                        'name' => 'THDEV_ACTIVATE_CURRENCY',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Shop Id:'),
                        'name' => 'THDEV_ACTIVATE_ID_SHOP',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'th_sub_title',
                        'label' => '',
                        'name' => $this->l('Customer:'),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Cart Id:'),
                        'name' => 'THDEV_ACTIVATE_ID_CART',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Customer Id:'),
                        'name' => 'THDEV_ACTIVATE_ID_CUSTOMER',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Guest Id:'),
                        'name' => 'THDEV_ACTIVATE_ID_GUEST',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Address Id:'),
                        'name' => 'THDEV_ACTIVATE_ID_ADDRESS',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Full Address:'),
                        'name' => 'THDEV_ACTIVATE_ADDRESS',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'th_title',
                        'label' => '',
                        'name' => $this->l('Module Design'),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Icon:'),
                        'name' => 'THDEV_ICON',
                        'options' => array(
                            'query' => array(
                                array(
                                    'option_value' => 'material_icons',
                                    'option_title' => $this->l('Material icons')
                                ),
                                array(
                                    'option_value' => 'font_awesome',
                                    'option_title' => $this->l('Font awesome')
                                ),
                                array(
                                    'option_value' => 'font_tello',
                                    'option_title' => $this->l('Fonttello')
                                )
                            ),
                            'id' => 'option_value',
                            'name' => 'option_title'
                        ),
                    ),
                    array(
                        'type' => 'th_sub_title',
                        'label' => '',
                        'name' => $this->l('Colors'),
                    ),
                    array(
                        'type' => 'color',
                        'label' => $this->l('Box Background:'),
                        'name' => 'THDEV_BACK_COLOR',
                    ),
                    array(
                        'type' => 'color',
                        'label' => $this->l('Box Border:'),
                        'name' => 'THDEV_BORDER_COLOR',
                    ),
                    array(
                        'type' => 'color',
                        'label' => $this->l('Box Text Color:'),
                        'name' => 'THDEV_TEXT_COLOR',
                    ),
                    array(
                        'type' => 'color',
                        'label' => $this->l('Box Subtitle Background:'),
                        'name' => 'THDEV_BACK_TITLE_COLOR',
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'THDEV_LIVE_MODE' => Tools::getValue('THCUSTOMREF_LIVE_MODE', Configuration::get('THDEV_LIVE_MODE')),
            'THDEV_ADMIN_ONLY' => Tools::getValue('THDEV_ADMIN_ONLY', Configuration::get('THDEV_ADMIN_ONLY')),
            'THDEV_ICON' => Tools::getValue('THDEV_ICON', Configuration::get('THDEV_ICON')),
            'THDEV_BACK_COLOR' => Tools::getValue('THDEV_BACK_COLOR', Configuration::get('THDEV_BACK_COLOR')),
            'THDEV_TEXT_COLOR' => Tools::getValue('THDEV_TEXT_COLOR', Configuration::get('THDEV_TEXT_COLOR')),
            'THDEV_BORDER_COLOR' => Tools::getValue('THDEV_BORDER_COLOR', Configuration::get('THDEV_BORDER_COLOR')),
            'THDEV_BACK_TITLE_COLOR' => Tools::getValue('THDEV_BACK_TITLE_COLOR', Configuration::get('THDEV_BACK_TITLE_COLOR')),
            'THDEV_ACTIVATE_IP' => Tools::getValue('THDEV_ACTIVATE_IP', Configuration::get('THDEV_ACTIVATE_IP')),
            'THDEV_ACTIVATE_CONTROLLER' => Tools::getValue('THDEV_ACTIVATE_CONTROLLER', Configuration::get('THDEV_ACTIVATE_CONTROLLER')),
            'THDEV_ACTIVATE_LANGUAGE' => Tools::getValue('THDEV_ACTIVATE_LANGUAGE', Configuration::get('THDEV_ACTIVATE_LANGUAGE')),
            'THDEV_ACTIVATE_CURRENCY' => Tools::getValue('THDEV_ACTIVATE_CURRENCY', Configuration::get('THDEV_ACTIVATE_CURRENCY')),
            'THDEV_ACTIVATE_ID_SHOP' => Tools::getValue('THDEV_ACTIVATE_ID_SHOP', Configuration::get('THDEV_ACTIVATE_ID_SHOP')),
            'THDEV_ACTIVATE_ID_CART' => Tools::getValue('THDEV_ACTIVATE_ID_CART', Configuration::get('THDEV_ACTIVATE_ID_CART')),
            'THDEV_ACTIVATE_ID_CUSTOMER' => Tools::getValue('THDEV_ACTIVATE_ID_CUSTOMER', Configuration::get('THDEV_ACTIVATE_ID_CUSTOMER')),
            'THDEV_ACTIVATE_ID_GUEST' => Tools::getValue('THDEV_ACTIVATE_ID_GUEST', Configuration::get('THDEV_ACTIVATE_ID_GUEST')),
            'THDEV_ACTIVATE_ID_ADDRESS' => Tools::getValue('THDEV_ACTIVATE_ID_ADDRESS', Configuration::get('THDEV_ACTIVATE_ID_ADDRESS')),
            'THDEV_ACTIVATE_ADDRESS' => Tools::getValue('THDEV_ACTIVATE_ADDRESS', Configuration::get('THDEV_ACTIVATE_ADDRESS')),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    public function getPageName()
    {
        $module_name = '';
        if (Validate::isModuleName(Tools::getValue('module'))) {
            $module_name = Tools::getValue('module');
        }

        if (!empty($this->page_name)) {
            $page_name = $this->page_name;
        } elseif (!empty($this->php_self)) {
            $page_name = $this->php_self;
        } elseif (Tools::getValue('fc') == 'module' && $module_name != '' && (Module::getInstanceByName($module_name) instanceof PaymentModule)) {
            $page_name = 'module-payment-submit';
        } elseif (preg_match('#^' . preg_quote($this->context->shop->physical_uri, '#') . 'modules/([a-zA-Z0-9_-]+?)/(.*)$#', $_SERVER['REQUEST_URI'], $m)) {
            /** @retrocompatibility Are we in a module ? */
            $page_name = 'module-' . $m[1] . '-' . str_replace(['.php', '/'], ['', '-'], $m[2]);
        } else {
            $page_name = Dispatcher::getInstance()->getController();
            $page_name = (preg_match('/^[0-9]/', $page_name) ? 'page_' . $page_name : $page_name);
        }

        return $page_name;
    }

    public function assignFooterVariables()
    {
        $THDEV_DEBUG_MODE = false;
        if (_PS_MODE_DEV_) {
            $THDEV_DEBUG_MODE = true;
        }

        $THDEV_COOKIE = false;
        if ((isset($_COOKIE['PAGE_INFO']) && $_COOKIE['PAGE_INFO'])) {
            $THDEV_COOKIE = true;
        }

        //ip
        $ip = false;
        if (Configuration::get('THDEV_ACTIVATE_IP')) {
            $ip = Tools::getRemoteAddr();
        }

        //controller
        $controller = false;
        if (Configuration::get('THDEV_ACTIVATE_CONTROLLER')) {
            $controller = $this->getPageName();
        }

        //lang
        $id_lang = false;
        $name_lang = false;
        if (Configuration::get('THDEV_ACTIVATE_LANGUAGE')) {
            $id_lang = $this->context->language->id;
            $name_lang = $this->context->language->name;
        }

        //currency
        $id_currency = false;
        $name_currency = false;
        if (Configuration::get('THDEV_ACTIVATE_CURRENCY')) {
            $id_currency = $this->context->currency->id;
            $name_currency = $this->context->currency->name;
        }

        //id_shop
        $id_shop = false;
        if (Configuration::get('THDEV_ACTIVATE_ID_SHOP')) {
            $id_shop = $this->context->shop->id;
        }

        //id_cart
        $id_cart = false;
        if (Configuration::get('THDEV_ACTIVATE_ID_CART')) {
            $id_cart = $this->context->cart->id;
        }

        //id_customer
        $id_customer = false;
        if (Configuration::get('THDEV_ACTIVATE_ID_CUSTOMER')) {
            $id_customer = $this->context->customer->id;
        }

        //id_guest
        $id_guest = false;
        if (Configuration::get('THDEV_ACTIVATE_ID_GUEST')) {
            $id_guest = $this->context->customer->id_guest;
        }

        //id_address
        $id_address = false;
        if (Configuration::get('THDEV_ACTIVATE_ID_ADDRESS')) {
            $id_address = $this->context->cart->id_address_delivery;
        }

        //address_details
        $address = false;
        if (Configuration::get('THDEV_ACTIVATE_ADDRESS') && $address_id = $this->context->cart->id_address_delivery) {
            $delivery_address = new Address($address_id);
            $id_state = $delivery_address->id_state;
            $state = new State($id_state);
            $address = array(
                'THDEV_ADDRESS_COUNTRY' => $delivery_address->country,
                'THDEV_ADDRESS_COUNTRY_ID' => $delivery_address->id_country,
                'THDEV_ADDRESS_STATE' => $state->name,
                'THDEV_ADDRESS_STATE_ID' => $id_state,
                'THDEV_ADDRESS_POST_CODE' => $delivery_address->postcode,
                'THDEV_ADDRESS_VAT_NUMBER' => $delivery_address->vat_number,
                'THDEV_ADDRESS_CITY' => $delivery_address->city,
            );
        }

        $data = array(
            'THDEV_ICON' => Configuration::get('THDEV_ICON'),
            'THDEV_IP' => $ip,
            'THDEV_CONTROLLER' => $controller,
            'THDEV_ID_LANG' => $id_lang,
            'THDEV_NAME_LANG' => $name_lang,
            'THDEV_ID_CURRENCY' => $id_currency,
            'THDEV_NAME_CURRENCY' => $name_currency,
            'THDEV_ID_SHOP' => $id_shop,
            'THDEV_ID_CART' => $id_cart,
            'THDEV_ID_CUSTOMER' => $id_customer,
            'THDEV_ID_GUEST' => $id_guest,
            'THDEV_ID_ADDRESS' => $id_address,
            'THDEV_ADDRESS' => $address,
            'THDEV_DEBUG_MODE' => $THDEV_DEBUG_MODE,
            'THDEV_COOKIE' => $THDEV_COOKIE,
            'THDEV_VERSION' => $this->getPsVersion(),
            'THDEV_ROOT_MODULE_DIR' => _PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name
        );

        $this->context->smarty->assign($data);
    }

    public function assignHeaderVariables()
    {
        $data = array(
            'THDEV_BACK_COLOR' => Configuration::get('THDEV_BACK_COLOR'),
            'THDEV_TEXT_COLOR' => Configuration::get('THDEV_TEXT_COLOR'),
            'THDEV_BORDER_COLOR' => Configuration::get('THDEV_BORDER_COLOR'),
            'THDEV_BACK_TITLE_COLOR' => Configuration::get('THDEV_BACK_TITLE_COLOR')
        );

        $this->context->smarty->assign($data);
    }

    public function updateDebugModeValueInMainFile($value)
    {
        $filename = _PS_ROOT_DIR_ . '/config/defines.inc.php';
        $cleanedFileContent = php_strip_whitespace($filename);
        $fileContent = Tools::file_get_contents($filename);

        if (!preg_match('/define\(\'_PS_MODE_DEV_\', ([a-zA-Z]+)\);/Ui', $cleanedFileContent)) {
            return false;
        }

        $fileContent = preg_replace('/define\(\'_PS_MODE_DEV_\', ([a-zA-Z]+)\);/Ui', 'define(\'_PS_MODE_DEV_\', ' . $value . ');', $fileContent);
        if (!@file_put_contents($filename, $fileContent)) {
            return false;
        }

        if (function_exists('opcache_invalidate')) {
            opcache_invalidate($filename);
        }

        return true;
    }

    public function hookHeader()
    {
        if (!$this->isVisible()) {
            return false;
        }

        $this->assignHeaderVariables();

        return $this->context->smarty->fetch($this->local_path.'views/templates/hook/header.tpl');
    }

    public function hookActionAdminControllerSetMedia()
    {
        if (Tools::getValue('configure') == $this->name) {
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    public function hookActionFrontControllerSetMedia($params)
    {
        if (!$this->isVisible()) {
            return false;
        }

        $this->context->controller->addCSS($this->_path.'views/css/front.css');
        $this->context->controller->addJS($this->_path.'views/js/front.js');
        Media::addJsDef(array(
            'THDEV_AJAX' => $this->context->link->getModuleLink($this->name, 'ajax', array('token' => Tools::getToken(false)))
        ));

        return true;
    }

    public function hookDisplayFooter($params)
    {
        if (!$this->isVisible() || $this->getPsVersion() == '7') {
            return false;
        }

        $this->assignFooterVariables();

        return $this->context->smarty->fetch($this->local_path.'views/templates/hook/display.tpl');
    }

    public function hookDisplayBeforeBodyClosingTag($params)
    {
        if (!$this->isVisible() || $this->getPsVersion() == '6') {
            return false;
        }

        $this->assignFooterVariables();

        return $this->context->smarty->fetch($this->local_path.'views/templates/hook/display.tpl');
    }

    private function isVisible()
    {
        if (!Configuration::get('THDEV_LIVE_MODE')) {
            return false;
        }

        if (Configuration::get('THDEV_ADMIN_ONLY')) {
            $cookies = new Cookie('psAdmin');
            if (!$cookies->id_employee) {
                return false;
            }
        }

        return true;
    }

    public function getPsVersion()
    {
        $full_version = _PS_VERSION_;
        return explode(".", $full_version)[1];
    }
}
