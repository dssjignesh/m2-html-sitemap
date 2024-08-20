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

namespace Dss\HtmlSiteMap\Block;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Dss\HtmlSiteMap\Helper\Data;
use \Magento\Backend\Block\Template\Context;

class ProductCollection extends \Magento\Framework\View\Element\Template
{
    /**
     * ItemsCollection constructor.
     *
     * @param Context $context
     * @param CollectionFactory $productCollectionFactory
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
        protected Context $context,
        public CollectionFactory $productCollectionFactory,
        public Data $helper,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Get Helper value
     *
     * @return Data
     */
    public function getHelper(): Data
    {
        return $this->helper;
    }

    /**
     * Get product collection
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getProductCollection(): \Magento\Catalog\Model\ResourceModel\Product\Collection
    {
        $maxProducts = $this->helper->getMaxProduct();
        $maxProducts = (int)$maxProducts;
        if ($maxProducts >= 0 && $maxProducts != null) {
            if ($maxProducts > 50000) {
                $maxProducts = 50000;
            }
        } else {
            $maxProducts = 50000;
        }

        $sortProduct = $this->helper->getSortProduct();
        $orderProduct = $this->helper->getOrderProduct();

        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect('*');

        $collection->addAttributeToFilter('visibility', \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH);
        $rulerStatus = \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED;

        $collection
            ->addAttributeToFilter('status', $rulerStatus)
            ->addFieldToFilter('excluded_html_sitemap', [
                ['null' => true],
                ['eq' => ''],
                ['eq' => 'NO FIELD'],
                ['eq' => '0'],
            ]);

        $collection->addWebsiteFilter();
        $collection->addUrlRewrite();
        $collection->addAttributeToSort($sortProduct, $orderProduct);
        $collection->setPageSize($maxProducts);
        return $collection;
    }
}
