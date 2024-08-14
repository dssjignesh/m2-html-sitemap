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
 * @package    Dss_SeoCore
 * @author     Extension Team
 * @copyright Copyright (c) 2024 Digit Software Solutions. ( https://digitsoftsol.com )
 */

namespace Dss\SeoCore\Plugin\Model\Category;

class DataProvider extends \Magento\Catalog\Model\Category\DataProvider
{
    /**
     * Get Seo Fields
     *
     * @return array
     */
    public function getSeoFields(): array
    {
        return [
            'custom_attribute',
            'seo_h1_title',
            'excluded_meta_template',
            'excluded_html_sitemap',
            'excluded_xml_sitemap'
        ];
    }

    /**
     * Get field map
     *
     * @return array
     */
    protected function getFieldsMap(): array
    {
        $fieldsMap = parent::getFieldsMap();
        if (isset($fieldsMap['search_engine_optimization'])) {
            $seoFields = $this->getSeoFields();
            foreach ($seoFields as $field) {
                $fieldsMap['search_engine_optimization'][] = $field;
            }
        }
        return $fieldsMap;
    }
}
