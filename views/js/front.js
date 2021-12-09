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

$(document).ready(function() {
    $('body')
        .on('click', '#th_ip', function(e) {
            thCopyToClipboard('th_ip');
        })
        .on('click', '#th_btn_minimize', function(e) {
            $("#th_activate_infos").removeClass("minimized");
            $(".thpanel").addClass("minimized");
            document.cookie = "PAGE_INFO" + " = " + ("Disable");
        })
        .on('click', '#th_activate_infos', function(e) {
            $(".thpanel").removeClass("minimized");
            $("#th_activate_infos").addClass("minimized");
            document.cookie = "PAGE_INFO" + " = " + ("Disable") + '; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        })
        .on('click', '#th_btn_close', function(e) {
            $.ajax({
                url: THDEV_AJAX,
                method: "POST",
                dataType: "json",
                data: {
                    action: 'closePanel',
                    ajax: true
                },
                success: function(data) {
                    if(!data.error) {
                        $(".thpanel").addClass("minimized");
                    }
                }
            })
        })
        .on('click', '#th_switch', function(e) {
            $.ajax({
                url: THDEV_AJAX,
                method: "POST",
                dataType: "json",
                data: {
                    action: 'updateDebugMode',
                    ajax: true
                }
            })
        });


    function thCopyToClipboard(id)
    {
        var r = document.createRange();
        r.selectNode(document.getElementById(id));
        window.getSelection().removeAllRanges();
        window.getSelection().addRange(r);
        document.execCommand('copy');
        window.getSelection().removeAllRanges();
    }
});
