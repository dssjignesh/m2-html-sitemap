<?php

declare(strict_types=1);

/**
 * Digit Software Solutions..
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 *
 * @category   Dss
 * @package    Dss_HtmlSiteMap
 * @author     Extension Team
 * @copyright Copyright (c) 2024 Digit Software Solutions. ( https://digitsoftsol.com )
 */

namespace Dss\HtmlSiteMap\Block\Adminhtml\System\Config;

use Magento\Framework\Data\Form\Element\AbstractElement;

class TextArea extends \Magento\Config\Block\System\Config\Form\Field
{

    /**
     * Get Element html
     *
     * @param AbstractElement $element
     * @return string
     */
    public function _getElementHtml(AbstractElement $element): string
    {
        $html = $element->getElementHtml();
        $html .= '<div class="additionUrl"></div>';
        $html .= '
        <script type="text/javascript">
            require(["jquery", "jquery/ui","Magento_Ui/js/modal/modal"], function($,modal){
                var check = 2;
                $("#dss_htmlsitemap_addition_addition_link").keyup(function (e) {
                    var additionUrl = $(this).val();
                    var additionArray = [];
                    for(var i = 0; i<=additionUrl.length; i++){
                        if(additionUrl[i] == "[" || additionUrl[i] == "]"){
                            additionArray.push(additionUrl[i]);
                        }
                    }
                    var count = 0;
                    for(var j = 0; j<=additionArray.length; j++){
                        if(additionArray[j] == "]" && j%2 == 1){
                            count++;
                        }
                    }
                    var count2 = additionArray.length;
                    if(count2/count == 2 && count2%4 == 0 || count2 == 0) {
                        check = 1;
                        $(".additionUrl").html("<div class=\'pass\'>You have filled in the correct format</div>");
                    }
                    else {
                        check = 0;
                        $(\'.additionUrl\').html("<div class=\'error\'>Please fill in the correct text format</div>");
                    }
                });

                $("#config-edit-form").bind(\'submit\', function (e) {
                    if(check == 0){
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();
                        alert("Please fill in the correct text format");
                        return false;
                    }
                });
            });
        </script>
        <style type="text/css">
            .additionUrl{
                padding: 0px;
            }
            .additionUrl .pass{
                padding: 5px;
                color: green;
                background-color: #9BFF87;
            }
            .additionUrl .error{
                padding: 5px;
                background-color: #FFD89E;
                color: red;
            }
        </style>';
        return $html;
    }
}
