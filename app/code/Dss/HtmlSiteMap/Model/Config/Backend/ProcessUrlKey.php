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

namespace Dss\HtmlSiteMap\Model\Config\Backend;

use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Data\Collection\AbstractDb;
use Dss\HtmlSiteMap\Helper\Data;

class ProcessUrlKey extends \Magento\Framework\App\Config\Value
{
    /**
     * ProcessUrlKey constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $config
     * @param TypeListInterface $cacheTypeList
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param Data $dataHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        private Data $dataHelper,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $config,
            $cacheTypeList,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * Before save
     *
     * @return \Magento\Framework\App\Config\Value
     */
    public function beforeSave(): \Magento\Framework\App\Config\Value
    {
        /* @var array $value */
        $value = $this->getValue();
        if ($value) {
            $value = rtrim($value);
            $value = preg_replace("/\r\n|\r|\n/", ' ', $value);
            $value = preg_replace('/[\s]+/mu', ' ', $value);
            $value = trim($value);
            $value = rtrim($value, ",");
            $value = $this->dataHelper->createSlugByString($value);
        } else {
            $value = \Dss\HtmlSiteMap\Helper\Data::DEFAULT_URL_KEY;
        }
        $this->setValue($value);
        return parent::beforeSave();
    }
}
