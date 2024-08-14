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

namespace Dss\HtmlSiteMap\Observer;

use Magento\Framework\Event\ObserverInterface;
use Dss\HtmlSiteMap\Block\ItemsCollection;
use Dss\HtmlSiteMap\Helper\Data;

class AddSitemap implements ObserverInterface
{
    /**
     * AddSiteMap constructor.
     *
     * @param Data $dataHelper
     * @param ItemsCollection $siteMapBlock
     */
    public function __construct(
        private Data $dataHelper,
        private ItemsCollection $siteMapBlock
    ) {
        $this->siteMapBlock = $siteMapBlock;
        $this->dataHelper = $dataHelper;
    }

    /**
     * Add New Layout handle
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return self
     */
    public function execute(\Magento\Framework\Event\Observer $observer): self
    {
        $layout = $observer->getData('layout');
        $helper = $this->dataHelper;
        $orderTemplates = $helper->orderTemplates();

        $block = $this->siteMapBlock;

        if ($orderTemplates == '' || $orderTemplates == null) {
            $orderTemplates = $block::PRODUCT_LIST_NUMBER . ',' .
                $block::STORE_VIEW_LIST_NUMBER . ',' .
                $block::ADDITIONAL_LIST_NUMBER . ',' .
                $block::CATE_AND_CMS_NUMBER;
        }

        $orderTemplates = "," . $orderTemplates . ",";
        $orderTemplates = explode(',', $orderTemplates);

        $fullActionName = $observer->getData('full_action_name');

        if ($fullActionName != 'custom_route_index_index') {
            return $this;
        }

        foreach ($orderTemplates as $key) {
            if ($key == $block::PRODUCT_LIST_NUMBER) {
                $layout->getUpdate()->addHandle('sitemap_product_list');
            }

            if ($key == $block::STORE_VIEW_LIST_NUMBER) {
                $layout->getUpdate()->addHandle('sitemap_store_list');
            }

            if ($key == $block::ADDITIONAL_LIST_NUMBER) {
                $layout->getUpdate()->addHandle('sitemap_additional_list');
            }

            if ($key == $block::CATE_AND_CMS_NUMBER) {
                $layout->getUpdate()->addHandle('sitemap_category_cms_list');
            }
        }

        return $this;
    }
}
