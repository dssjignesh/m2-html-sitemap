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

use Magento\Cms\Model\PageFactory;
use Magento\Cms\Model\Page;

class Checkbox extends \Magento\Framework\View\Element\Template
{
    /**
     * Checkbox constructor.
     * @param Page $page
     * @param PageFactory $pageFactory
     */
    public function __construct(
        public Page $page,
        public PageFactory $pageFactory
    ) {
    }

    /**
     * To Option array
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        $this->getStoreId();
        $cms = [];
        $page = $this->pageFactory->create();
        foreach ($page->getCollection() as $item) {
            $cms[$item->getId()] = $item->getTitle();
        }

        $cmsArray = [];
        $count = 0;
        foreach ($cms as $id => $title) {
            $cmsArray[$count]['value'] = $id;
            $cmsArray[$count]['label'] = $title;
            $count++;
        }
        return $cmsArray;
    }
}
