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

namespace Dss\SeoCore\Plugin\Model;

use Magento\Framework\Registry as CoreRegistry;
use Magento\Framework\App\Request\Http;
use Magento\Cms\Model\Page;
use Dss\SeoCore\Helper\Data;
use Magento\Theme\Block\Html\Title;

class Product
{
    /**
     * Product constructor.
     *
     * @param CoreRegistry $registry
     * @param Http $request
     * @param Page $page
     * @param Data $dataHelper
     */
    public function __construct(
        protected CoreRegistry $registry,
        protected Http $request,
        protected Page $page,
        protected Data $dataHelper
    ) {
    }

    /**
     * After Get page heading
     *
     * @param Title $subject
     * @param string $result
     * @return string
     */
    public function afterGetPageHeading(Title $subject, $result)
    {
        $statusEnable = $this->dataHelper->isEnableH1Title();
        if (!$statusEnable) {
            return $result;
        }
        $fullActionName = $this->getFullActionName();
        if ($fullActionName === 'catalog_product_view') {
            //Get Product from Registry
            $product = $this->getProduct();
            $productH1Tag = $product->getData('seo_h1_title');
            if ($productH1Tag) {
                return $productH1Tag;
            }
        }

        if ($fullActionName === 'catalog_category_view') {
            $category = $this->getCategory();
            $categoryH1Tag = $category->getData('seo_h1_title');
            if ($categoryH1Tag) {
                return $categoryH1Tag;
            }
        }

        if ($fullActionName === 'cms_page_view') {
            $cmsPageH1Tag = $this->page->getData('seo_h1_title');
            if ($cmsPageH1Tag) {
                return $cmsPageH1Tag;
            }
        }
        return $result;
    }

    /**
     * Get current product
     *
     * @return mixed
     */
    public function getProduct(): mixed
    {
        return $this->coreRegistry->registry('current_product');
    }

    /**
     * Get current category
     *
     * @return mixed
     */
    public function getCategory(): mixed
    {
        return $this->coreRegistry->registry('current_category');
    }

    /**
     * Get Cms page
     *
     * @return mixed
     */
    public function getCmsPage(): mixed
    {
        return $this->coreRegistry->registry('cms_page');
    }

    /**
     * Get full action name
     *
     * @return string
     */
    public function getFullActionName(): string
    {
        return $this->request->getFullActionName();
    }
}
