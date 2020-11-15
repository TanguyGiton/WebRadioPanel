<?php

namespace AppBundle\Tests\Utils;

use AppBundle\Utils\RolesHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RolesHelperTest extends WebTestCase
{
    protected $rolesHelper;

    public function setUp()
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        $this->rolesHelper = $container->get('app.roleshelper');
    }

    public function testGetRoles()
    {
        $data = $this->rolesHelper->getRoles();

        static::assertTrue(is_array($data), 'Not an array');

        static::assertArrayHasKey('ROLE_USER', $data);
        static::assertEquals($data['ROLE_USER'], 'ROLE_USER');

        static::assertArrayHasKey('ROLE_RADIO_HOST', $data);
        static::assertEquals($data['ROLE_RADIO_HOST'], 'ROLE_RADIO_HOST');

        static::assertArrayHasKey('ROLE_DJ', $data);
        static::assertEquals($data['ROLE_DJ'], 'ROLE_DJ');

        static::assertArrayHasKey('ROLE_PROGRAMME_DIRECTOR', $data);
        static::assertEquals($data['ROLE_PROGRAMME_DIRECTOR'], 'ROLE_PROGRAMME_DIRECTOR');

        static::assertArrayHasKey('ROLE_ADMIN', $data);
        static::assertEquals($data['ROLE_ADMIN'], 'ROLE_ADMIN');

        static::assertArrayHasKey('ROLE_SUPER_ADMIN', $data);
        static::assertEquals($data['ROLE_SUPER_ADMIN'], 'ROLE_SUPER_ADMIN');
    }


}
