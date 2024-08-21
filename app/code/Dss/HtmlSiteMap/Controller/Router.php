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

namespace Dss\HtmlSiteMap\Controller;

use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\DataObject;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
use Dss\HtmlSiteMap\Helper\Data;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

class Router implements RouterInterface
{
    /**
     * @var bool
     */
    private $dispatched = false;

    /**
     * Router constructor.
     *
     * @param ActionFactory $actionFactory
     * @param EventManagerInterface $eventManager
     * @param PageFactory $resultPageFactory
     * @param ResultFactory $resultFactory
     * @param UrlInterface $url
     * @param StoreManagerInterface $storeManager
     * @param Data $helper
     */
    public function __construct(
        protected ActionFactory $actionFactory,
        protected EventManagerInterface $eventManager,
        protected PageFactory $resultPageFactory,
        private ResultFactory $resultFactory,
        private UrlInterface $url,
        private StoreManagerInterface $storeManager,
        protected Data $helper
    ) {
    }

    /**
     * Match
     *
     * @param RequestInterface $request
     * @return \Magento\Framework\App\ActionInterface|null
     */
    public function match(RequestInterface $request): \Magento\Framework\App\ActionInterface|null
    {
        /** @var \Magento\Framework\App\Request\Http $request */
        if (!$this->dispatched) {
            $identifier = trim($request->getPathInfo(), '/');
            $this->eventManager->dispatch('core_controller_router_match_before', [
                'router' => $this,
                'condition' => new DataObject(['identifier' => $identifier, 'continue' => true])
            ]);
            $stores = $this->storeManager->getStores(false);

            $routeObject = [];
            foreach ($stores as $store) {
                $storeId = $store->getId();
                $route = $this->helper->getModuleRoute($storeId);
                if (!in_array($route, $routeObject)) {
                    $routeObject[] = $route;
                }
            }

            if (in_array($identifier, $routeObject)) {
                $request->setModuleName('custom_route')
                    ->setControllerName('index')
                    ->setActionName('index');
                $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $identifier);
                $this->dispatched = true;

                return $this->actionFactory->create(
                    \Magento\Framework\App\Action\Forward::class
                );
            }

            return null;
        }
        return null;
    }

    /**
     * Execute
     *
     * @return $this|\Magento\Framework\View\Result\Page
     */
    public function execute(): \Magento\Framework\View\Result\Page
    {
        $redirectUrl= $this->url->getUrl('');
        $isEnabled = $this->helper->isEnable();
        $title = $this->helper->getTitleSiteMap();
        $description = $this->helper->getDescriptionSitemap();
        $keywords = $this->helper->getKeywordsSitemap();
        $metaTitle = $this->helper->getMetaTitleSitemap();
        if ($isEnabled) {
            $resultPage = $this->resultPageFactory->create();
            $resultPage->getConfig()->getTitle()->set(__($title));
            $resultPage->getConfig()->setDescription($description);
            $resultPage->getConfig()->setKeywords($keywords);
            $resultPage->getConfig()->setMetadata('meta_title', $metaTitle);
            return $resultPage;
        } else {
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl($redirectUrl);
        }
    }
}
