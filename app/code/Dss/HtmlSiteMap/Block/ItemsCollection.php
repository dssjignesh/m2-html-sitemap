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

use Magento\Framework\App\ActionInterface;
use Magento\Framework\Url\EncoderInterface;
use Magento\Framework\Url\Helper\Data as UrlHelper;
use Magento\Store\Model\Store;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Dss\HtmlSiteMap\Helper\Data;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Store\Model\ScopeInterface;

class ItemsCollection extends \Magento\Framework\View\Element\Template
{
    public const MAX_PRODUCTS = 'dss_htmlsitemap/product/max_products';
    public const SORT_PRODUCT = 'dss_htmlsitemap/product/sort_product';
    public const ORDER_PRODUCT = 'dss_htmlsitemap/product/order_product';
    public const PRODUCT_LIST_NUMBER = '1';
    public const STORE_VIEW_LIST_NUMBER = '2';
    public const ADDITIONAL_LIST_NUMBER = '3';
    public const CATE_AND_CMS_NUMBER = '4';
    public const XML_PATH_DEFAULT_LOCALE = 'general/locale/code';

    /**
     * @var bool
     */
    public $storeInUrl;

    /**
     * ItemsCollection constructor.
     *
     * @param Context $context
     * @param Data $helper
     * @param PostHelper $postDataHelper
     * @param EncoderInterface $encoder
     * @param UrlHelper $urlHelper
     * @param ScopeConfigInterface $scopeConfig
     * @param array $data
     */
    public function __construct(
        protected Context $context,
        public Data $helper,
        protected PostHelper $postDataHelper,
        protected EncoderInterface $encoder,
        protected UrlHelper $urlHelper,
        protected ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Get Store Id
     *
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStoreId(): int
    {
        return $this->_storeManager->getStore()->getId();
    }

    /**
     * Get Helper
     *
     * @return Data
     */
    public function getHelper(): Data
    {
        return $this->helper;
    }

    /**
     * Get Website Id
     *
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getWebsiteId(): int
    {
        return $this->_storeManager->getStore()->getWebsiteId();
    }

    /**
     * Get Store Code
     *
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStoreCode(): string
    {
        return $this->_storeManager->getStore()->getCode();
    }

    /**
     * Get Store Url
     *
     * @param bool $fromStore
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStoreUrl($fromStore = true): mixed
    {
        return $this->_storeManager->getStore()->getCurrentUrl($fromStore);
    }

    /**
     * Get Store Active
     *
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function isStoreActive(): mixed
    {
        return $this->_storeManager->getStore()->isActive();
    }

    /**
     * Get Current Website Id
     *
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCurrentWebsiteId(): int
    {
        return $this->_storeManager->getStore()->getWebsiteId();
    }

    /**
     * Get Current Group Id
     *
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCurrentGroupId(): mixed
    {
        return $this->_storeManager->getStore()->getGroupId();
    }

    /**
     * Get Current Store Id
     *
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCurrentStoreId(): int
    {
        return $this->_storeManager->getStore()->getId();
    }

    /**
     * Get Raw groups
     *
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getRawGroups(): mixed
    {
        if (!$this->hasData('raw_groups')) {
            $websiteGroups = $this->_storeManager->getWebsite()->getGroups();

            $groups = [];
            foreach ($websiteGroups as $group) {
                $groups[$group->getId()] = $group;
            }
            $this->setData('raw_groups', $groups);
        }
        return $this->getData('raw_groups');
    }

    /**
     * Get Raw stores
     *
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getRawStores(): mixed
    {
        if (!$this->hasData('raw_stores')) {
            $websiteStores = $this->_storeManager->getWebsite()->getStores();
            $stores = [];
            foreach ($websiteStores as $store) {
                if (!$store->isActive()) {
                    continue;
                }
                $localeCode =
                $this->scopeConfig->getValue(
                    self::XML_PATH_DEFAULT_LOCALE,
                    ScopeInterface::SCOPE_STORE,
                    $store
                );
                $store->setLocaleCode($localeCode);
                $params = ['_query' => []];
                if (!$this->isStoreInUrl()) {
                    $params['_query']['___store'] = $store->getCode();
                }
                $baseUrl = $store->getUrl('', $params);

                $store->setHomeUrl($baseUrl);
                $stores[$store->getGroupId()][$store->getId()] = $store;
            }
            $this->setData('raw_stores', $stores);
        }
        return $this->getData('raw_stores');
    }

    /**
     * Additional link
     *
     * @return array
     */
    public function getAdditionLink(): array
    {
        //Additional Link
        $additionUrl = $this->helper->getAdditionUrl();
        $count = 0;
        $additionLink = [];
        while ($count >= 0) {
            $countString = strpos((string) $additionUrl, ']');
            if ($countString == false) {
                break;
            }
            $count++;
            $additionLink[$count] = substr($additionUrl, 1, $countString - 1);
            $additionUrl = substr($additionUrl, $countString, strlen($additionUrl));
            $additionUrl = strstr($additionUrl, '[');
        }
        return $additionLink;
    }
    /**
     * Get groups
     *
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getGroups(): mixed
    {
        if (!$this->hasData('groups')) {
            $rawGroups = $this->getRawGroups();
            $rawStores = $this->getRawStores();

            $groups = [];
            $localeCode = $this->scopeConfig->getValue(
                self::XML_PATH_DEFAULT_LOCALE,
                ScopeInterface::SCOPE_STORE
            );
            foreach ($rawGroups as $group) {
                if (!isset($rawStores[$group->getId()])) {
                    continue;
                }
                if ($group->getId() == $this->getCurrentGroupId()) {
                    $groups[] = $group;
                    continue;
                }

                $store = $group->getDefaultStoreByLocale($localeCode);

                if ($store) {
                    $group->setHomeUrl($store->getHomeUrl());
                    $groups[] = $group;
                }
            }
            $this->setData('groups', $groups);
        }
        return $this->getData('groups');
    }

    /**
     * Get stores
     *
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStores(): mixed
    {
        if (!$this->getData('stores')) {
            $rawStores = $this->getRawStores();

            $groupId = $this->getCurrentGroupId();
            if (!isset($rawStores[$groupId])) {
                $stores = [];
            } else {
                $stores = $rawStores[$groupId];
            }
            $this->setData('stores', $stores);
        }
        return $this->getData('stores');
    }

    /**
     * Get Current store code
     *
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCurrentStoreCode(): string
    {
        return $this->_storeManager->getStore()->getCode();
    }

    /**
     * Get Target Store RedirectUrl
     *
     * @param Store $store
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getTargetStoreRedirectUrl(Store $store): string
    {
        return $this->_urlBuilder->getUrl(
            'stores/store/redirect',
            [
                '___store' => $store->getCode(),
                '___from_store' => $this->_storeManager->getStore()->getCode(),
                ActionInterface::PARAM_NAME_URL_ENCODED => $this->encoder->encode(
                    $store->getBaseUrl()
                ),
            ]
        );
    }

    /**
     * Get Store InUrl
     *
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function isStoreInUrl(): bool
    {
        if ($this->storeInUrl === null) {
            $this->storeInUrl = $this->_storeManager->getStore()->isUseStoreInUrl();
        }
        return $this->storeInUrl;
    }

    /**
     * Get Target Store Post data
     *
     * @param Store $store
     * @param array $data
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getTargetStorePostData(\Magento\Store\Model\Store $store, $data = []): string
    {
        $data[\Magento\Store\Api\StoreResolverInterface::PARAM_NAME] = $store->getCode();
        $data['___from_store'] = $this->_storeManager->getStore()->getCode();

        $urlOnTargetStore = $store->getBaseUrl();
        $data[ActionInterface::PARAM_NAME_URL_ENCODED] = $this->urlHelper->getEncodedUrl($urlOnTargetStore);

        $url = $this->getUrl('stores/store/redirect');

        return $this->postDataHelper->getPostData(
            $url,
            $data
        );
    }
}
