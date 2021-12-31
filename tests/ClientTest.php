<?php

/**
 * linkedin-client
 * ClientTest.php
 *
 * PHP Version 5
 *
 * @category Production
 * @package  Default
 * @author   Philipp Tkachev <philipp@zoonman.com>
 * @date     8/17/17 19:57
 * @license  http://www.zoonman.com/projects/linkedin-client/license.txt linkedin-client License
 * @version  GIT: 1.0
 * @link     http://www.zoonman.com/projects/linkedin-client/
 */

namespace LinkedIn;

use PHPUnit\Framework\TestCase;

/**
 * Class ClientTest
 *
 * @package LinkedIn
 */
class ClientTest extends TestCase
{

    /**
     * @var \LinkedIn\Client
     */
    public $client;

    /**
     * Setup test environment
     */
    public function setUp(): void
    {
        $this->client = new Client(
            getenv('LINKEDIN_CLIENT_ID'),
            getenv('LINKEDIN_CLIENT_SECRET')
        );
    }

    /**
     * Make sure, that user redirect gets prepared correctly
     */
    public function testGetLoginUrl()
    {
        $actual = $this->client->getLoginUrl();
        $this->assertNotEmpty($actual);
    }

    /**
     * Make sure that method LinkedIn\Client::setAccessToken() works correctly
     *
     * @param $token
     * @param AccessToken|null $expectedToken
     * @param \Exception|null $expectedException
     *
     * @dataProvider getSetAccessTokenTestTable
     */
    public function testSetAccessToken($token, $expectedToken, $expectedException)
    {
        $client = new Client();

        if ($expectedException !== null) {
            $this->expectException(get_class($expectedException), $expectedException->getMessage());
        }

        $client->setAccessToken($token);

        if ($expectedToken !== null) {
            $this->assertEquals($expectedToken->getToken(), $client->getAccessToken()->getToken());
        }
    }

    public function getSetAccessTokenTestTable()
    {
        return [
            [
                'token' => null,
                'expectedToken' => null,
                'expectedException' => new \InvalidArgumentException('$accessToken must be instance of \LinkedIn\AccessToken class'),
            ],

            [
                'token' => 'test token',
                'expectedToken' => new AccessToken('test token'),
                'expectedException' => null,
            ],

            [
                'token' => new AccessToken('hello world'),
                'expectedToken' => new AccessToken('hello world'),
                'expectedException' => null,
            ],

            [
                'token' => new \StdClass(),
                'expectedToken' => null,
                'expectedException' => new \InvalidArgumentException('$accessToken must be instance of \LinkedIn\AccessToken class'),
            ],
        ];
    }
}
