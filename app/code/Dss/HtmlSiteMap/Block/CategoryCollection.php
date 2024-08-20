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

use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Backend\Block\Template\Context;
use Magento\Catalog\Helper\Category;
use Dss\HtmlSiteMap\Helper\Data;
use Magento\Cms\Model\PageFactory;
use Magento\Catalog\Model\Indexer\Category\Flat\State;
use Magento\Catalog\Model\ResourceModel\Category\Collection;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class CategoryCollection extends \Magento\Framework\View\Element\Template
{
    /**
     * ItemsCollection constructor.
     *
     * @param Context $context
     * @param CollectionFactory $categoryCollectionFactory
     * @param Category $categoryHelper
     * @param Data $helper
     * @param State $categoryFlatState
     * @param PageFactory $pageFactory
     * @param array $data
     */
    public function __construct(
        protected Context $context,
        protected CollectionFactory $categoryCollectionFactory,
        protected Category $categoryHelper,
        protected Data $helper,
        protected State $categoryFlatState,
        protected PageFactory $pageFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Get Store Id
     *
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStoreId(): string
    {
        return $this->_storeManager->getStore()->getId();
    }

    /**
     * Get Helper Data
     *
     * @return Data
     */
    public function getHelper(): Data
    {
        return $this->helper;
    }

    /**
     * Get Category collection
     *
     * @param bool $isActive
     * @return Collection
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCategoryCollection($isActive = true): Collection
    {
        $rootCategoryIds = [];
        $currentWebsiteId = $this->_storeManager->getStore()->getWebsiteId();
        $stores = $this->_storeManager->getStores(false);
        foreach ($stores as $store) {
            $websiteId = $store->getWebsiteId();
            if ($currentWebsiteId == $websiteId) {
                $rootCategoryIds[] = $store->getRootCategoryId();
            }
        }
        $collection = $this->categoryCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        // select only active categories
        if ($isActive) {
            $collection->addIsActiveFilter();
        }
        if (!empty($rootCategoryIds)) {
            $collection->addFieldToFilter('entity_id', ['in' => $rootCategoryIds]);
        }
        return $collection;
    }

    /**
     * Get Category helper
     *
     * @return Category
     */
    public function getCategoryHelper(): Category
    {
        return $this->categoryHelper;
    }

    /**
     * Get Cms page
     *
     * @return AbstractCollection
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCmsPages(): AbstractCollection
    {
        $storeId = $this->getStoreId();
        $this->getStoreId();
        $collection = $this->pageFactory->create()->getCollection();
        $collection->addFieldToSelect("*");
        $collection->addStoreFilter($storeId);
        return $collection;
    }

    /**
     * Get All category
     *
     * @param object $category
     * @param bool $categoryDisable
     * @return string
     */
    public function getAllCategories($category, $categoryDisable): string
    {
        $categoryHelper = $this->getCategoryHelper();
        $categoryHtmlEnd = null;
        if ($childrenCategories = $category->getChildrenCategories()) {
            foreach ($childrenCategories as $category) {
                if (!$category->getIsActive()) {
                    continue;
                }
                $categoryString = (string)$category->getId();
                $categoryString = "," . $categoryString . ",";
                $categoryValidate = strpos($categoryDisable, $categoryString);
                if ($categoryValidate == false) {
                    $categoryUrl = $categoryHelper->getCategoryUrl($category);
                    $categoryHtml = '<li><a href="' . $categoryUrl . '">' . $category->getName() . '</a></li>';
                    $categoryReturn = $this->getAllCategories($category, $categoryDisable);
                    $categoryHtml = $categoryHtml . $categoryReturn;
                } else {
                    $categoryHtml = null;
                }
                $categoryHtmlEnd = $categoryHtmlEnd . $categoryHtml;
            }
            return '<ul>' . $categoryHtmlEnd . '</ul>';
        }
        return '';
    }
}
