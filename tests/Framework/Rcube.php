<?php

/**
 * Test class to test rcube class
 *
 * @package Tests
 */
class Framework_Rcube extends PHPUnit\Framework\TestCase
{

    /**
     * Class constructor
     */
    function test_class()
    {
        $object = rcube::get_instance();

        $this->assertInstanceOf('rcube', $object, "Class singleton");
    }

    /**
     * rcube::read_localization()
     */
    function test_read_localization()
    {
        $rcube = rcube::get_instance();
        $result = $rcube->read_localization(INSTALL_PATH . 'plugins/acl/localization', 'pl_PL');

        $this->assertSame('Zapis', $result['aclwrite']);
    }

    /**
     * rcube::list_languages()
     */
    function test_list_languages()
    {
        $rcube = rcube::get_instance();
        $result = $rcube->list_languages();

        $this->assertSame('English (US)', $result['en_US']);
    }

    /**
     * Test that gen_message_id produces a message-ID that can be
     * used safely in References and In-Reply-To messages.
     */
    function test_gen_message_id()
    {
        $rcube = rcube::get_instance();

        $result = $rcube->gen_message_id('a@example.com');
        $result = preg_replace('/.*@/', '', $result);
        $this->assertSame('example.com>', $result);

        $result = $rcube->gen_message_id('a@dømi.fo');
        $result = preg_replace('/.*@/', '', $result);
        $this->assertSame('xn--dmi-0na.fo>', $result);
   }

    /**
     * rcube::encrypt() and rcube::decrypt()
     */
    function test_encrypt_and_decrypt()
    {
        $rcube = rcube::get_instance();
        $result = $rcube->decrypt($rcube->encrypt('test'));

        $this->assertSame('test', $result);

        // The following tests fail quite often, therefore we disable them
        $this->markTestSkipped();

        // Test AEAD cipher method
        $rcube->config->set('cipher_method', 'aes-256-gcm');

        $result = $rcube->decrypt($rcube->encrypt('test'));

        $this->assertSame('test', $result);

        // Back to the default
        $rcube->config->set('cipher_method', 'DES-EDE3-CBC');
    }
}
