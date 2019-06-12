<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_CronSchedule
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\CronSchedule\Controller\Adminhtml;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Cron\Model\ResourceModel\Schedule;
use Mageplaza\CronSchedule\Model\ResourceModel\Schedule\Collection;
use Mageplaza\CronSchedule\Model\ResourceModel\Schedule\CollectionFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Ui\Component\MassAction\Filter;

/**
 * Class AbstractLog
 * @package Mageplaza\CronSchedule\Controller\Adminhtml
 */
abstract class AbstractLog extends Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Mageplaza_CronSchedule::log';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var Schedule
     */
    protected $scheduleResource;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * AbstractLog constructor.
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Filter $filter
     * @param Schedule $scheduleResource
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Filter $filter,
        Schedule $scheduleResource,
        CollectionFactory $collectionFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->filter            = $filter;
        $this->scheduleResource  = $scheduleResource;
        $this->collectionFactory = $collectionFactory;

        parent::__construct($context);
    }

    /**
     * Load layout, set breadcrumbs
     *
     * @return Page
     */
    protected function _initAction()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE);

        return $resultPage;
    }

    /**
     * @param Collection $collection
     *
     * @return int
     */
    protected function deleteSchedule($collection)
    {
        $count = 0;

        /** @var \Magento\Cron\Model\Schedule $item */
        foreach ($collection->getItems() as $item) {
            try {
                $this->scheduleResource->delete($item);
                $count++;
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }

        return $count;
    }
}