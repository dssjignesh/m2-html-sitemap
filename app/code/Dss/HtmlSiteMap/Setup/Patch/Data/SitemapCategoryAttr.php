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

class SitemapCategoryAttr implements DataPatchInterface
{
    /**
     * CategoryAttribute Construct
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory          $eavSetupFactory
     */
    public function __construct(
        private ModuleDataSetupInterface $moduleDataSetup,
        private EavSetupFactory $eavSetupFactory
    ) {
    }

    /**
     * Apply function
     *
     * @return void
     */
    public function apply()
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $entityTypeId = $eavSetup->getEntityTypeId(\Magento\Catalog\Model\Category::ENTITY);
        $attributeSetId = $eavSetup->getDefaultAttributeSetId($entityTypeId);
        $eavSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'excluded_html_sitemap', [
            'sort_order' => 630,
            'note' => '',
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
            'default' => '0'
        ]);
        $idg = $eavSetup->getAttributeGroupId($entityTypeId, $attributeSetId, 'Search Engine Optimization');
        $eavSetup->addAttributeToGroup(
            $entityTypeId,
            $attributeSetId,
            $idg,
            'excluded_html_sitemap',
            1000
        );
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
