/**
 * Digit Software Solutions..
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 *
 * @category   Dss
 * @package    Dss_SeoCore
 * @author     Extension Team
 * @copyright Copyright (c) 2024 Digit Software Solutions. ( https://digitsoftsol.com )
 */
var config = {
    config: {
        mixins: {
            'Magento_AsynchronousOperations/js/grid/listing': {
                'Dss_SeoCore/js/grid/listing-mixin' :true
            },
            'Magento_Ui/js/grid/editing/editor': {
                'Dss_SeoCore/js/grid/editing/editor-mixin' :true
            }
        }
    }
};
