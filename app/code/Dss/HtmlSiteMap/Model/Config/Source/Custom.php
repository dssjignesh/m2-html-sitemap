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

namespace Dss\HtmlSiteMap\Model\Config\Source;

class Custom implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * To Option array
     *
     * @return array
     */
    public function toOptionArray(): array
    {

        return [
            ['value' => 'name', 'label' => __('Name')],
            ['value' => 'date', 'label' => __('Date')],
            ['value' => 'price', 'label' => __('Price')],
        ];
    }
}
