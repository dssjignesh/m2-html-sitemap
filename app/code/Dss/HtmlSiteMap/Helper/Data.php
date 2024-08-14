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

namespace Dss\HtmlSiteMap\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Config\Model\ResourceModel\Config\Data\CollectionFactory;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public const MAX_PRODUCTS = 'dss_htmlsitemap/product/max_products';
    public const SORT_PRODUCT = 'dss_htmlsitemap/product/sort_product';
    public const ORDER_PRODUCT = 'dss_htmlsitemap/product/order_product';
    public const DEFAULT_URL_KEY = 'sitemap';

    /**
     * @var string
     */
    public $scopeStore = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param CollectionFactory $configFactory
     */
    public function __construct(
        protected Context $context,
        protected CollectionFactory $configFactory
    ) {
        parent::__construct($context);
    }

    /**
     * Get AdditionalUrl
     *
     * @return mixed|string
     */
    public function getAdditionUrl(): string
    {
        $additionUrl = $this->scopeConfig->getValue('dss_htmlsitemap/addition/addition_link', $this->scopeStore);

        $additionUrl = ($additionUrl == '') ? '' : $additionUrl;
        return $additionUrl;
    }

    /**
     * Get Cms link
     *
     * @return mixed|string
     */
    public function getCmsLink(): string
    {
        $cmsLink = $this->scopeConfig->getValue('dss_htmlsitemap/cms/do_something', $this->scopeStore);

        $cmsLink = ($cmsLink == '') ? '' : $cmsLink;
        return $cmsLink;
    }

    /**
     * Is Enable
     *
     * @return bool
     */
    public function isEnable(): bool
    {
        $isEnable = $this->scopeConfig->isSetFlag('dss_htmlsitemap/general/enable', $this->scopeStore);
        return $isEnable;
    }

    /**
     * Get Module route
     *
     * @param string $storeId
     * @return mixed|string
     */
    public function getModuleRoute($storeId = null): string
    {
        if ($storeId) {
            $value = $this->scopeConfig->getValue('dss_htmlsitemap/general/router', $this->scopeStore, $storeId);
        } else {
            $value = $this->scopeConfig->getValue('dss_htmlsitemap/general/router', $this->scopeStore);
        }
        if (!$value) {
            $value = self::DEFAULT_URL_KEY;
        }
        return $value;
    }

    /**
     * Get Max prodcuct
     *
     * @return mixed
     */
    public function getMaxProduct(): mixed
    {
        return $this->scopeConfig->getValue(
            self::MAX_PRODUCTS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Short Product
     *
     * @return mixed
     */
    public function getSortProduct(): mixed
    {
        return $this->scopeConfig->getValue(
            self::SORT_PRODUCT,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Order product
     *
     * @return mixed
     */
    public function getOrderProduct(): mixed
    {
        return $this->scopeConfig->getValue(
            self::ORDER_PRODUCT,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Title sitemap
     *
     * @return mixed
     */
    public function getTitleSiteMap(): mixed
    {
        return $this->scopeConfig->getValue(
            "dss_htmlsitemap/general/title",
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Discription Site map
     *
     * @return mixed
     */
    public function getDescriptionSitemap(): mixed
    {
        return $this->scopeConfig->getValue(
            "dss_htmlsitemap/for_search/description",
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get keyword sitemap
     *
     * @return mixed
     */
    public function getKeywordsSitemap(): mixed
    {
        return $this->scopeConfig->getValue(
            "dss_htmlsitemap/for_search/keywords",
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Meta title sitemap
     *
     * @return mixed
     */
    public function getMetaTitleSitemap(): mixed
    {
        return $this->scopeConfig->getValue(
            "dss_htmlsitemap/for_search/meta_title",
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get ConfigWithoutCache
     *
     * @param int $storeId
     * @param string $path
     * @return bool|mixed
     */
    public function getConfigWithoutCache($storeId, $path): mixed
    {
        $collection = $this->configFactory->create();
        $collection->addFieldToFilter('path', $path);

        if ((int)$storeId === 0) {
            $collection->addFieldToFilter('scope', 'default');
            $collection->addFieldToFilter('scope_id', '0');
        }
        $categoryDisable = false;
        if ($collection->getSize()) {
            $valueStoreArray = $this->getValueStore($collection, $storeId);
            $defaultValue = $valueStoreArray['default_value'];
            $categoryDisable = $valueStoreArray['store_value'];
            if ($categoryDisable === false) {
                $categoryDisable = $defaultValue;
            }
        }
        return $categoryDisable;
    }

    /**
     * Get ValueStore
     *
     * @param object $collection
     * @param int $storeId
     * @return array
     */
    protected function getValueStore($collection, $storeId): array
    {
        $categoryDisable = false;
        $defaultValue = '';
        foreach ($collection as $item) {
            if ((int)$storeId === 0) {
                $categoryDisable = $item->getValue();
            }
            if ((int)$item->getScopeId() === 0 && $item->getScope() == 'default') {
                $defaultValue = $item->getValue();
            }
            if ((int)$storeId !== 0 && (int)$item->getScopeId() === (int)$storeId) {
                $categoryDisable = $item->getValue();
            }
        }
        return [
            'default_value' => $defaultValue,
            'store_value' => $categoryDisable
        ];
    }

    /**
     * Get CategoryDisable
     *
     * @param string $storeId
     * @return mixed|string
     */
    public function getCategoryDisable($storeId = null): string
    {
        if ($storeId !== null) {
            $categoryDisable = $this->scopeConfig->getValue(
                'dss_htmlsitemap/category/id_category',
                $this->scopeStore,
                $storeId
            );
        } else {
            $categoryDisable = $this->scopeConfig->getValue('dss_htmlsitemap/category/id_category', $this->scopeStore);
        }

        $categoryDisable = ($categoryDisable == '') ? '' : $categoryDisable;
        return $categoryDisable;
    }

    /**
     * Is EnableCategory
     *
     * @return bool
     */
    public function isEnableCategory(): bool
    {
        $enableCategory = $this->scopeConfig->isSetFlag('dss_htmlsitemap/category/enable_category', $this->scopeStore);
        return $enableCategory;
    }

    /**
     * Is EnableProduct
     *
     * @return mixed|bool
     */
    public function isEnableProduct(): bool
    {
        $enableProduct = $this->scopeConfig->isSetFlag('dss_htmlsitemap/product/enable_product', $this->scopeStore);
        return $enableProduct;
    }

    /**
     * Is EnableCms
     *
     * @return mixed|bool
     */
    public function isEnableCms(): bool
    {
        $enableCms = $this->scopeConfig->isSetFlag('dss_htmlsitemap/cms/enable_cms', $this->scopeStore);
        return $enableCms;
    }

    /**
     * Is EnableStoreView
     *
     * @return mixed|bool
     */
    public function isEnableStoreView(): bool
    {
        $enableStoreView = $this->scopeConfig->isSetFlag('dss_htmlsitemap/store/enable_store', $this->scopeStore);
        return $enableStoreView;
    }

    /**
     * Order Template
     *
     * @return mixed|string
     */
    public function orderTemplates(): string
    {
        $orderTemplates = $this->scopeConfig->getValue('dss_htmlsitemap/general/order_templates', $this->scopeStore);

        $orderTemplates = ($orderTemplates == '') ? '' : $orderTemplates;
        return $orderTemplates;
    }

    /**
     * Get Base Url
     *
     * @return string
     */
    public function getBaseUrl(): string
    {
        $baseUrl = $this->_urlBuilder->getUrl();
        return $baseUrl;
    }

    /**
     * Title category
     *
     * @return mixed|string
     */
    public function titleCategory(): string
    {
        $titleCategory = $this->scopeConfig->getValue('dss_htmlsitemap/category/title_category', $this->scopeStore);

        $titleCategory = ($titleCategory == '') ? '' : $titleCategory;
        return $titleCategory;
    }

    /**
     * Get ShowLinkAt
     *
     * @return mixed|string
     */
    public function getShowLinkAt(): string
    {
        $showLinkAt = $this->scopeConfig->getValue('dss_htmlsitemap/general/show_link', $this->scopeStore);

        $showLinkAt = ($showLinkAt === '' || $showLinkAt === null) ? 'footer' : $showLinkAt;
        return $showLinkAt;
    }

    /**
     * Title Cms
     *
     * @return mixed|string
     */
    public function titleCms(): string
    {
        $titleCms = $this->scopeConfig->getValue('dss_htmlsitemap/cms/title_cms', $this->scopeStore);

        $titleCms = ($titleCms == '') ? '' : $titleCms;
        return $titleCms;
    }

    /**
     * Title Product
     *
     * @return mixed|string
     */
    public function titleProduct(): string
    {
        $titleProduct = $this->scopeConfig->getValue('dss_htmlsitemap/product/title_product', $this->scopeStore);

        $titleProduct = ($titleProduct == '') ? '' : $titleProduct;
        return $titleProduct;
    }

    /**
     * Title Store
     *
     * @return mixed|string
     */
    public function titleStore(): string
    {
        $titleStore = $this->scopeConfig->getValue('dss_htmlsitemap/store/title_store', $this->scopeStore);

        $titleStore = ($titleStore == '') ? '' : $titleStore;
        return $titleStore;
    }

    /**
     * Title Addition
     *
     * @return mixed|string
     */
    public function titleAddition(): string
    {
        $titleAddition = $this->scopeConfig->getValue('dss_htmlsitemap/addition/title_addition', $this->scopeStore);

        $titleAddition = ($titleAddition == '') ? '' : $titleAddition;
        return $titleAddition;
    }

    /**
     * Create Slug ByString
     *
     * @param string $string
     * @return mixed|string|string[]|null
     */
    public function createSlugByString($string): string|null
    {
        if ($string === '' || $string === null) {
            return $string;
        } else {
            $unicode = [
                'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
                'd'=>'đ',
                'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
                'i'=>'í|ì|ỉ|ĩ|ị',
                'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
                'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
                'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
                'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
                'D'=>'Đ',
                'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
                'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
                'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
                'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
                'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
            ];
            foreach ($unicode as $nonUnicode => $uni) {
                $string = preg_replace("/($uni)/i", $nonUnicode, $string);
            }
            //Replaces all spaces with hyphens.
            $string = str_replace(' ', '-', $string);
            // Removes special chars.
            $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);

            // Replaces multiple hyphens with single one.
            $string = preg_replace('/-+/', '-', $string);
        }
        $string = strtolower($string);
        $string = trim($string, '-');
        return $string;
    }

    /**
     * OpenNewTab
     *
     * @return mixed|string
     */
    public function openNewTab(): string
    {
        $openNewTab = $this->scopeConfig->getValue('dss_htmlsitemap/addition/open_new_tab', $this->scopeStore);

        $openNewTab = ($openNewTab == '') ? '' : $openNewTab;
        return $openNewTab;
    }
}
