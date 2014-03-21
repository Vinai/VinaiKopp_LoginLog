<?php


class VinaiKopp_LoginLog_Test_Config_AdminhtmlTest
    extends EcomDev_PHPUnit_Test_Case_Config
{
    protected function getFile()
    {
        $dir = Mage::getModuleDir('etc', 'VinaiKopp_LoginLog');
        return "$dir/adminhtml.xml";
    }
    
    protected function getXml()
    {
        $file = $this->getFile();
        $xml = simplexml_load_file($file);
        return $xml;
    }
    
    public function assertMenuDefined($path, $action = '', $message = '') {
        $nodePath = 'menu/' . implode('/children/', explode('/', $path));
        $result = $this->getXml()->xpath($nodePath);
        if (! $message) {
            $defaultMessage = sprintf('Failed to assert the menu %s is defined', $path);
        }
        if (! $result) {
            $this->fail($message ?: $defaultMessage);
        }
        if ($action && $action != (string) $result[0]->action) {
            if (! $message) {
                $defaultMessage = sprintf('Failed to assert the %s menu action %s is defined', $path, $action);
            }
            $this->fail($message ?: $defaultMessage);
        }
    }
    
    public function assertAclDefined($path, $message = null)
    {
        $nodePath = 'acl/resources/admin/children/' . implode('/children/', explode('/', $path));
        $result = $this->getXml()->xpath($nodePath);
        if (! $message) {
            $message = sprintf('Failed to assert the ACL %s is defined', $path);
        }
        if (! $result) {
            $this->fail($message);
        }
    }
    
    /**
     * @test
     */
    public function itShouldHaveAnAdminhtmlXml()
    {
        $this->assertFileExists($this->getFile());
    }

    /**
     * @test
     */
    public function itShouldAddAMenuEntry()
    {
        $this->assertMenuDefined('customer/login_log', 'adminhtml/loginlog');
    }

    /**
     * @test
     */
    public function itShouldAddAnAclEntry()
    {
        $this->assertAclDefined('customer/login_log');
    }
} 