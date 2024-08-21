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

class MaximumProduct extends \Magento\Config\Block\System\Config\Form\Field
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
        $html .= '<div class="maxProduct"></div>';
        $html .= '
        <script type="text/javascript">
            require(["jquery", "jquery/ui","Magento_Ui/js/modal/modal"], function($,modal){
                var checkMaxProduct = 2;
                $("#dss_htmlsitemap_product_max_products").keyup(function (e) {
                    var maxProduct = $(this).val();
                    if(maxProduct >= 0) {
                        checkMaxProduct = 1;
                        $(\'.maxProduct .error\').remove();
                    }
                    else {
                        checkMaxProduct = 0;
                        $(\'.maxProduct\').html("<div class=\'error\'>Please fill in the correct number format</div>");
                    }
                });

                $("#config-edit-form").bind(\'submit\', function (e) {
                    if(checkMaxProduct == 0){
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();
                        alert("Please fill in the correct number format");
                        return false;
                    }
                });
            });
        </script>
        <style type="text/css">
            .maxProduct{
                padding: 0px;
            }
            .maxProduct .error{
                padding: 5px;
                background-color: #FFD89E;
                color: red;
                margin: 5px 0 0 0;
            }
        </style>';
        return $html;
    }
}
