<?php
/**
 * Copyright © 2016 PlazaThemes.com. All rights reserved.
 *
 * @author PlazaThemes Team <contact@plazathemes.com>
 */

namespace Plazathemes\Layerednavigation\Controller\CatalogSearch\Result;

use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResourceConnection;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Search\Model\QueryFactory;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\CatalogSearch\Controller\Result\Index
{
    /**
     * @var QueryFactory
     */
    private $_queryFactory;

    /**
     * Catalog Layer Resolver
     *
     * @var Resolver
     */
    private $layerResolver;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $resultPageFactory;

    public function __construct(
        Context $context,
        Session $catalogSession,
        StoreManagerInterface $storeManager,
        QueryFactory $queryFactory,
        Resolver $layerResolver,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        parent::__construct($context, $catalogSession, $storeManager, $queryFactory, $layerResolver);
        $this->_queryFactory = $queryFactory;
        $this->layerResolver = $layerResolver;
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $layer_action = $this->getRequest()->getParam('layer_action');
        if($layer_action == 1) {
            $this->layerResolver->create(Resolver::CATALOG_LAYER_SEARCH);
            /* @var $query \Magento\Search\Model\Query */
            $query = $this->_queryFactory->get();

            $query->setStoreId($this->_storeManager->getStore()->getId());

            if ($query->getQueryText() != '') {
                if ($this->_objectManager->get('Magento\CatalogSearch\Helper\Data')->isMinQueryLength()) {
                    $query->setId(0)->setIsActive(1)->setIsProcessed(1);
                } else {
                    $query->saveIncrementalPopularity();

                    if ($query->getRedirect()) {
                        $this->getResponse()->setRedirect($query->getRedirect());
                        return;
                    }
                }

                $this->_objectManager->get('Magento\CatalogSearch\Helper\Data')->checkNotes();

                $page = $this->resultPageFactory->create();

                $search_result = $page->getLayout()->getBlock('search_result_list')->toHtml();

                $data['productlist'] = $search_result;

                $this->getResponse()->representJson(
                    $this->_objectManager->get('Magento\Framework\Json\Helper\Data')->jsonEncode($data)
                );
            } else {
                $this->getResponse()->setRedirect($this->_redirect->getRedirectUrl());
            }
        } else {
            return parent::execute(); // TODO: Change the autogenerated stub
        }
    }
}