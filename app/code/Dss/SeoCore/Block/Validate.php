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

namespace Dss\SeoCore\Block;

use Dss\SeoCore\Helper\Data;
use Magento\Framework\View\Element\Template\Context;

class Validate extends \Magento\Framework\View\Element\Template
{
    /**
     * Validate constructor.
     *
     * @param Data $dataHelper
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        private Data $dataHelper,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Get helper
     *
     * @return Data
     */
    public function getHelper(): Data
    {
        return $this->dataHelper;
    }
}
