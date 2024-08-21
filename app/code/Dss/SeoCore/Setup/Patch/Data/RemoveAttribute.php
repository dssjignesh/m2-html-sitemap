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
use Magento\Catalog\Model\Product;
use Psr\Log\LoggerInterface;
use Magento\Catalog\Model\Category;

class RemoveAttribute implements DataPatchInterface
{
    /**
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
     * Apply Function
     *
     * @return void
     */
    public function apply()
    {
        try {
            /** @var EavSetup $eavSetup */
            $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

            $eavSetup->removeAttribute(Product::ENTITY, 'seo_h1_title');
            $eavSetup->removeAttribute(Category::ENTITY, 'seo_h1_title');
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
