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

namespace Dss\HtmlSiteMap\Setup\Patch\Data;

use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Catalog\Model\Product;
use Psr\Log\LoggerInterface;

class SitemapProduct implements DataPatchInterface
{
    /**
     * ExludedHtmlSitemap constructor
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        private ModuleDataSetupInterface $moduleDataSetup,
        private EavSetupFactory $eavSetupFactory,
        private LoggerInterface $logger
    ) {
    }

    /**
     * Apply function
     *
     * @return void
     */
    public function apply()
    {
        try {
            /** @var EavSetup $eavSetup */
            $eavSetup = $this->eavSetupFactory->create(['excluded_html_sitemap' => $this->moduleDataSetup]);

            $eavSetup->addAttribute(Product::ENTITY, 'excluded_html_sitemap', [
                'group' => 'Search Engine Optimization',
                'type' => 'text',
                'backend' => \Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend::class,
                'frontend' => '',
                'label' => 'Excluded from HTML Sitemap',
                'input' => 'select',
                'class' => '',
                'source' => \Dss\HtmlSiteMap\Model\Config\Product\ExcludeHtmlSiteMap::class,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '0',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'sort_order' => 630,
                'apply_to' => ''
            ]);

        } catch (\Exception $e) {
            $this->logger->critical($e);
        }
    }

    /**
     * GetDependencies function
     *
     * @return array
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * GetAliases function
     *
     * @return array
     */
    public function getAliases(): array
    {
        return [];
    }
}
