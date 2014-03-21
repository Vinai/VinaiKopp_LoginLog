<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this Module to
 * newer versions in the future.
 *
 * @category   Magento
 * @package    VinaiKopp_LoginLog
 * @copyright  Copyright (c) 2014 Vinai Kopp http://netzarbeiter.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


class VinaiKopp_LoginLog_Test_TestHelper
    extends EcomDev_PHPUnit_Test_Case
{
    public function prepareAdminRequest()
    {
        $this->stubCoreSession();
        $this->stubAdminSession();
        $this->stubAdminhtmlSession();
        $this->stubCookie();
    }
    
    public function getMessageCollectionStub()
    {
        $mockMessageCollection = $this->getModelMock('core/message_collection');
        $mockMessageCollection->expects($this->any())
            ->method('getItems')
            ->will($this->returnValue(array()));
        return $mockMessageCollection;
    }

    public function stubCoreSession()
    {
        $mockMessageCollection = $this->getMessageCollectionStub();

        $mockCoreSession = $this->getModelMockBuilder('core/session')
            ->disableOriginalConstructor()
            ->getMock();
        $mockCoreSession->expects($this->any())
            ->method('start')
            ->will($this->returnSelf());
        $mockCoreSession->expects($this->any())
            ->method('getMessages')
            ->will($this->returnValue($mockMessageCollection));
        Mage::unregister('_singleton/core/session');
        Mage::register('_singleton/core/session', $mockCoreSession);
    }

    public function stubAdminhtmlSession()
    {
        $mockMessageCollection = $this->getMessageCollectionStub();

        $mockAdminhtmlSession = $this->getModelMockBuilder('adminhtml/session')
            ->disableOriginalConstructor()
            ->getMock();
        $mockAdminhtmlSession->expects($this->any())
            ->method('getMessages')
            ->will($this->returnValue($mockMessageCollection));
        Mage::unregister('_singleton/adminhtml/session');
        Mage::register('_singleton/adminhtml/session', $mockAdminhtmlSession);
    }

    public function stubAdminSession()
    {
        $mockAdminUser = $this->getModelMock('admin/user');
        $mockAdminUser->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(1));

        $mockAdminSession = $this->getModelMockBuilder('admin/session')
            ->disableOriginalConstructor()
            ->setMethods(array('isLoggedIn', 'getUser', 'isAllowed'))
            ->getMock();
        $mockAdminSession->expects($this->any())
            ->method('isLoggedIn')
            ->will($this->returnValue(true));
        $mockAdminSession->expects($this->any())
            ->method('getUser')
            ->will($this->returnValue($mockAdminUser));
        $mockAdminSession->expects($this->any())
            ->method('isAllowed')
            ->will($this->returnValue(true));
        Mage::unregister('_singleton/admin/session');
        Mage::register('_singleton/admin/session', $mockAdminSession);
    }

    public function stubCookie()
    {
        $mockCookie = $this->getModelMock('core/cookie');
        $mockCookie->expects($this->any())
            ->method('get')
            ->will($this->returnValue(array('admimhtml' => 'dummy-session-id')));
        $this->replaceByMock('singleton', 'core/cookie', $mockCookie);
    }
} 