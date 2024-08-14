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

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Cache\TypeListInterface as CacheTypeListInterface;
use Magento\Config\Model\ResourceModel\Config;
use Dss\HtmlSiteMap\Helper\Data;
use Dss\SeoCore\Helper\Data as SeoCoreHelper;

class AfterCategorySave implements ObserverInterface
{

    /**
     * AfterCategorySave constructor.
     *
     * @param Config $resourceConfig
     * @param CacheTypeListInterface $cache
     * @param Data $dataHelper
     * @param SeoCoreHelper $seoCoreHelper
     */
    public function __construct(
        private Config $resourceConfig,
        private CacheTypeListInterface $cache,
        private Data $dataHelper,
        protected SeoCoreHelper $seoCoreHelper
    ) {
    }

    /**
     * Execute
     *
     * @param EventObserver $observer
     * @return $this|void
     */
    public function execute(EventObserver $observer)
    {
        $categoryObject = $observer->getEvent()->getCategory();
        $storeId = $categoryObject->getStoreId();
        $categoryId = (string)$categoryObject->getId();
        $statusModule = $this->dataHelper->isEnable();

        $categoryDisableArray = $this->getCategoryDisableArray($storeId);

        $excludedHtmlSiteMap = $categoryObject->getExcludedHtmlSitemap();
        if ($statusModule && (int)$excludedHtmlSiteMap === 1) {
            if (!in_array($categoryId, $categoryDisableArray)) {
                $categoryDisableArray[] = $categoryId;
                $this->cache->invalidate('full_page');
            }
        }
        if ($statusModule && (int)$excludedHtmlSiteMap === 0 && in_array($categoryId, $categoryDisableArray)) {
            $arrayKey = array_search($categoryId, $categoryDisableArray);
            if ($arrayKey !== false) {
                unset($categoryDisableArray[$arrayKey]);
                $this->cache->invalidate('full_page');
            }
        }
        $finalCategoriesDisable = $this->seoCoreHelper->implode(',', $categoryDisableArray);
        $scopeToAdd = ScopeInterface::SCOPE_STORES;
        if ((int)$storeId === 0) {
            $scopeToAdd = 'default';
        }
        $this->saveNewConfig(
            'dss_htmlsitemap/category/id_category',
            $finalCategoriesDisable,
            $scopeToAdd,
            $storeId
        );
        return $this;
    }

    /**
     * Get Category DisableArray
     *
     * @param int $storeId
     * @return array
     */
    public function getCategoryDisableArray($storeId):array
    {
        $categoryDisable = $this->dataHelper->getConfigWithoutCache($storeId, 'dss_htmlsitemap/category/id_category');
        if ($categoryDisable) {
            $categoryDisableArray = explode(',', $categoryDisable);
        } else {
            $categoryDisableArray = [];
        }
        return $categoryDisableArray;
    }

    /**
     * SaveNewConfig
     *
     * @param string $path
     * @param string $value
     * @param string $scope
     * @param string $scopeId
     * @return \Magento\Config\Model\ResourceModel\Config
     */
    protected function saveNewConfig($path, $value, $scope = 'default', $scopeId = ''):
        \Magento\Config\Model\ResourceModel\Config
    {
        return $this->resourceConfig->saveConfig($path, $value, $scope, $scopeId);
    }
}
