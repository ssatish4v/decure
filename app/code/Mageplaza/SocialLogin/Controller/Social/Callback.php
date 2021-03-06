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
 * @package     Mageplaza_SocialLogin
 * @copyright   Copyright (c) 2016 Mageplaza (http://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */
namespace Mageplaza\SocialLogin\Controller\Social;

use Mageplaza\SocialLogin\Model\Social;
use Magento\Framework\App\Action\Context;

/**
 * Class Callback
 * @package Mageplaza\SocialLogin\Controller\Social
 */
class Callback extends \Magento\Framework\App\Action\Action
{
	protected $social;

	public function __construct(Context $context, Social $social)
	{
		parent::__construct($context);
		$this->social = $social;
	}

	public function execute()
	{
		\Hybrid_Endpoint::process();
	}
}