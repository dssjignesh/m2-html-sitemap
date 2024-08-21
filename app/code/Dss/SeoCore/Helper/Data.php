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

namespace Dss\SeoCore\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Helper\Context;

class Data extends AbstractHelper
{
    /**
     * Data constructor.
     *
     * @param Context $context
     * @param PostHelper $postDataHelper
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        public PostHelper $postDataHelper,
        protected StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
    }

    /**
     * Get Store id
     *
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStoreId(): int
    {
        return $this->storeManager->getStore()->getId();
    }

    /**
     * Get Store code
     *
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getStoreCode(): string
    {
        return $this->storeManager->getStore()->getCode();
    }

    /**
     * Get store manager
     *
     * @return StoreManagerInterface
     */
    public function getStoreManager(): StoreManagerInterface
    {
        return $this->storeManager;
    }

    /**
     * Enable H1 title
     *
     * @return bool
     */
    public function isEnableH1Title(): bool
    {
        return $this->scopeConfig->isSetFlag(
            'dss_seo_core/general/enable_h1_title',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Google verification
     *
     * @param int|null $storeId
     * @return mixed
     */
    public function getGoogleVerification($storeId = null): mixed
    {
        return $this->scopeConfig->getValue(
            'dss_seo_core/general/google_verification',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get Bing verification
     *
     * @return mixed
     */
    public function getBingVerification(): mixed
    {
        return $this->scopeConfig->getValue(
            'dss_seo_core/general/bing_verification',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Rewrite function implode() fix all the version of php
     *
     * @param string $separator
     * @param array $array
     * @return string
     */
    public function implode($separator, $array): string
    {
        if (!is_array($separator) && $this->compareVersionPhp()) {
            return implode($separator, $array);
        }
        return implode($array, $separator);
    }

    /**
     * Compare Version Php
     *
     * @return bool
     */
    public function compareVersionPhp(): bool
    {
        if (version_compare(PHP_VERSION, '7.4', '>=')) {
            return true;
        }
        return false;
    }
}
