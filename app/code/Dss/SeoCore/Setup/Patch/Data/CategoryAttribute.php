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

namespace Dss\SeoCore\Setup\Patch\Data;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Psr\Log\LoggerInterface;

class CategoryAttribute implements DataPatchInterface
{
    /**
     * SeoH1TitleCategoryAttribute constructor
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
            $eavSetup = $this->eavSetupFactory->create(['seo_h1_title' => $this->moduleDataSetup]);
            $entityTypeId = $eavSetup->getEntityTypeId(\Magento\Catalog\Model\Category::ENTITY);
            $attributeSetId = $eavSetup->getDefaultAttributeSetId($entityTypeId);
            $eavSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'seo_h1_title', [
                'type' => 'varchar',
                'label' => 'Custom H1 Page',
                'input' => 'text',
                'source' => '',
                'required' => false,
                'sort_order' => 610,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Search Engine Optimization',
                'note' => 'This new heading 1 will replace the original h1 of your page'
            ]);
            $idg = $eavSetup->getAttributeGroupId($entityTypeId, $attributeSetId, 'Search Engine Optimization');
            $eavSetup->addAttributeToGroup(
                $entityTypeId,
                $attributeSetId,
                $idg,
                'seo_h1_title',
                46
            );

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
