{*
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
*}

<div id="th_activate_infos" {if !$THDEV_COOKIE}class="minimized"{/if}>
    {if $THDEV_ICON eq 'material_icons'}
        <a id="th_icon"><i class="material-icons">visibility</i></a>
    {elseif $THDEV_ICON eq 'font_awesome'}
        {if $THDEV_VERSION eq '7'}
            <a id="th_icon"><i class="fa fa-eye" aria-hidden="true"></i></a>
        {else}
            <a id="th_icon"><i class="icon-eye" aria-hidden="true"></i></a>
        {/if}
    {else}
        <a id="th_icon"><i class="fto-eye"></i></a>
    {/if}
</div>

<div class="panel thpanel {if $THDEV_COOKIE}minimized{/if}">
    <div class="thcaption">
        <div class="thtitle">
            {l s='PAGE INFO' mod='thdev'}
        </div>
        <div class="th-actions">
            {if $THDEV_ICON eq 'material_icons'}
                <a id="th_btn_minimize" title="Minimize Info Box" class="thicon"><i class="material-icons">expand_more</i></a>
                <a id="th_btn_close" title="Close Info Box" class="thicon"><i class="material-icons">close</i></a>
            {elseif $THDEV_ICON eq 'font_awesome'}
                {if $THDEV_VERSION eq '7'}
                    <a id="th_btn_minimize" title="Minimize Info Box" class="thicon"><i class="fa fa-expand_more" aria-hidden="true"></i></a>
                    <a id="th_btn_close" title="Close Info Box" class="thicon"><i class="fa fa-close" aria-hidden="true"></i></a>
                {else}
                    <a id="th_btn_minimize" title="Minimize Info Box" class="thicon"><i class="icon-chevron-down" aria-hidden="true"></i></a>
                    <a id="th_btn_close" title="Close Info Box" class="thicon"><i class="icon-close" aria-hidden="true"></i></a>
                {/if}
            {else}
                <a id="th_btn_minimize" title="Minimize Info Box" class="thicon"><i class="fto-down-open-2"></i></a>
                <a id="th_btn_close" title="Close Info Box" class="thicon"><i class="fto-cancel"></i></a>
            {/if}
        </div>
    </div>
    <table class="thpaddingtable">
        <tbody>
        <tr>
            <td>{l s='Debug Mode' mod='thdev'}</td>
            <td>
                {if $THDEV_VERSION eq '7'}
                    <label class="thswitch">
                        <input type="checkbox" id="th_switch" name="thdebug" {if $THDEV_DEBUG_MODE}checked{/if}>
                        <span class="thslider round"></span>
                    </label>
                {else}
                    <label>
                        <input type="checkbox" id="th_switch" name="thdebug" {if $THDEV_DEBUG_MODE}checked{/if}>
                    </label>
                {/if}
            </td>
        </tr>

        {if $THDEV_IP OR $THDEV_ACTIVATE_CONTROLLER}
            <tr>
                <td class="thsubtitle" colspan="2">{l s='Connection' mod='thdev'}</td>
            </tr>
        {/if}

        {if $THDEV_IP}
            <tr>
                <td>IP: </td>
                <td>
                    <div id="th_ip" title="Click to copy your ip">
                        {$THDEV_IP|escape:'html':'UTF-8'}
                    </div>
                </td>
            </tr>
        {/if}

        {if $THDEV_CONTROLLER}
            <tr>
                <td>Controller:</td>
                <td>{$THDEV_CONTROLLER|escape:'html':'UTF-8'}</td>
            </tr>
        {/if}

        {if $THDEV_ID_LANG OR $THDEV_ID_CURRENCY OR $THDEV_ID_SHOP}
            <tr>
                <td class="thsubtitle" colspan="2">{l s='Shop' mod='thdev'}</td>
            </tr>
        {/if}

        {if $THDEV_ID_LANG !== false}
            <tr>
                <td>{l s='Language:' mod='thdev'}</td>
                <td>{$THDEV_NAME_LANG|escape:'html':'UTF-8'} ({$THDEV_ID_LANG|escape:'html':'UTF-8'})</td>
            </tr>
        {/if}

        {if $THDEV_ID_CURRENCY !== false}
            <tr>
                <td>{l s='Currency:' mod='thdev'}</td>
                <td>{$THDEV_NAME_CURRENCY|escape:'html':'UTF-8'} ({$THDEV_ID_CURRENCY|escape:'html':'UTF-8'})</td>
            </tr>
        {/if}

        {if $THDEV_ID_SHOP !== false}
            <tr>
                <td>{l s='Shop Id:' mod='thdev'}</td>
                <td>{$THDEV_ID_SHOP|escape:'html':'UTF-8'}</td>
            </tr>
        {/if}

        {if $THDEV_ID_CART OR $THDEV_ID_CUSTOMER OR $THDEV_ID_GUEST OR $THDEV_ADDRESS}
            <tr>
                <td class="thsubtitle" colspan="2">{l s='Customer' mod='thdev'}</td>
            </tr>
        {/if}

        {if $THDEV_ID_CART !== false}
            <tr>
                <td>{l s='Cart Id:' mod='thdev'}</td>
                <td>{$THDEV_ID_CART|escape:'html':'UTF-8'}</td>
            </tr>
        {/if}

        {if $THDEV_ID_CUSTOMER !== false}
            <tr>
                <td>{l s='Customer Id:' mod='thdev'}</td>
                <td>{$THDEV_ID_CUSTOMER|escape:'html':'UTF-8'}</td>
            </tr>
        {/if}

        {if $THDEV_ID_GUEST !== false}
            <tr>
                <td>{l s='Guest Id:' mod='thdev'}</td>
                <td>{$THDEV_ID_GUEST|escape:'html':'UTF-8'}</td>
            </tr>
        {/if}

        {if $THDEV_ID_ADDRESS !== false}
            <tr>
                <td>{l s='Address Id:' mod='thdev'}</td>
                <td>{$THDEV_ID_ADDRESS|escape:'html':'UTF-8'}</td>
            </tr>
        {/if}

        {if $THDEV_ADDRESS}
            <tr>
                <td>{l s='Country:' mod='thdev'}</td>
                <td>{$THDEV_ADDRESS.THDEV_ADDRESS_COUNTRY|escape:'html':'UTF-8'} ({$THDEV_ADDRESS.THDEV_ADDRESS_COUNTRY_ID|escape:'html':'UTF-8'})</td>
            </tr>

            <tr>
                <td>{l s='State:' mod='thdev'}</td>
                <td>{$THDEV_ADDRESS.THDEV_ADDRESS_STATE|escape:'html':'UTF-8'} ({$THDEV_ADDRESS.THDEV_ADDRESS_STATE_ID|escape:'html':'UTF-8'})</td>
            </tr>

            <tr>
                <td>{l s='Postcode:' mod='thdev'}</td>
                <td>{$THDEV_ADDRESS.THDEV_ADDRESS_POST_CODE|escape:'html':'UTF-8'}</td>
            </tr>

            <tr>
                <td>{l s='VAT Number:' mod='thdev'}</td>
                <td>{$THDEV_ADDRESS.THDEV_ADDRESS_VAT_NUMBER|escape:'html':'UTF-8'}</td>
            </tr>

            <tr>
                <td>{l s='City:' mod='thdev'}</td>
                <td>{$THDEV_ADDRESS.THDEV_ADDRESS_CITY|escape:'html':'UTF-8'}</td>
            </tr>
        {/if}
        </tbody>
    </table>
</div>
