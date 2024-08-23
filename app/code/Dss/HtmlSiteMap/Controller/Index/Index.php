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

namespace Dss\HtmlSiteMap\Controller\Index;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\UrlInterface;
use Dss\HtmlSiteMap\Helper\Data;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * Index constructor.
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param RequestInterface $request
     * @param Data $helper
     * @param UrlInterface $url
     */
    public function __construct(
        Context $context,
        protected PageFactory $resultPageFactory,
        private RequestInterface $request,
        protected Data $helper,
        protected UrlInterface $url
    ) {
        $this->resultFactory = $context->getResultFactory();
        $this->url = $context->getUrl();
        parent::__construct($context);
    }

    /**
     * Execute
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $redirectUrl= $this->url->getUrl('');
        $isEnabled = $this->helper->isEnable();
        $title = $this->helper->getTitleSiteMap();
        $description = $this->helper->getDescriptionSitemap();
        $keywords = $this->helper->getKeywordsSitemap();
        $metaTitle = $this->helper->getMetaTitleSitemap();
        if ($isEnabled) {
            $identifier = trim($this->request->getPathInfo(), '/');
            $route = $this->helper->getModuleRoute();

            if ($identifier !== $route) {
                $redirectUrl= $this->url->getUrl($route);
                return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl($redirectUrl);
            }
            $resultPage = $this->resultPageFactory->create();
            $resultPage->getConfig()->getTitle()->set(__($title));
            $resultPage->getConfig()->setDescription($description);
            $resultPage->getConfig()->setKeywords($keywords);
            $resultPage->getConfig()->setMetaTitle($metaTitle);
            return $resultPage;
        } else {
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl($redirectUrl);
        }
    }
}
