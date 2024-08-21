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
use Magento\Catalog\Model\ResourceModel\Category;
use Magento\Catalog\Model\CategoryRepository;

class ProcessCategory extends \Magento\Framework\App\Config\Value
{
    /**
     * ProcessCategory constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $config
     * @param TypeListInterface $cacheTypeList
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param Category $categoryResource
     * @param CategoryRepository $categoryRepository
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        private Category $categoryResource,
        private CategoryRepository $categoryRepository,
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
     * @return void
     * @throws \Exception
     * @SuppressWarnings(CyclomaticComplexity)
     */
    public function beforeSave()
    {
        /* @var array $value */
        $value = $this->getValue();
        $oldValue = $this->getOldValue();
        $storeId = $this->getScopeId();
        if ($oldValue) {
            $oldCategoryArray = explode(',', $oldValue);
        } else {
            $oldCategoryArray = [];
        }

        if ($value) {
            $newValueArray = explode(',', $value);
        } else {
            $newValueArray = [];
        }

        if ($value !== $oldValue) {
            //Check Add Category
            $enableCategoryArray = [];
            if (!empty($oldCategoryArray)) {
                foreach ($oldCategoryArray as $categoryId) {
                    if (!in_array($categoryId, $newValueArray)) {
                        $enableCategoryArray[] = $categoryId;
                    }
                }
            }

            //Enable Category
            foreach ($enableCategoryArray as $categoryId) {
                $categoryObject = $this->categoryRepository->get($categoryId, $storeId);
                $categoryObject->setData('excluded_html_sitemap', '0');
                $this->categoryResource->saveAttribute($categoryObject, 'excluded_html_sitemap');
            }

            foreach ($newValueArray as $categoryId) {
                $categoryObject = $this->categoryRepository->get($categoryId, $storeId);
                $categoryObject->setData('excluded_html_sitemap', '1');
                $this->categoryResource->saveAttribute($categoryObject, 'excluded_html_sitemap');
            }
        }
        return parent::beforeSave();
    }
}
