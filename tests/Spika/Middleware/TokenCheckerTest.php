<?php
namespace Spika\Middleware;

use Spika\Middleware\TokenChecker;
use Spika\Db\DbInterface;
use Symfony\Component\HttpFoundation\Request;

class TokenCheckerTest extends \PHPUnit_Framework_TestCase
{
    const FIXTURE_USER_ID = '123';
    const FIXTURE_TOKEN   = 'some_token';

    /**
     * @test
     */
    public function whenValidRequestIsGiven()
    {
        $user = $this->createFixtureUser();
        $db   = $this->createMockDb();

        $db->expects(any())
            ->method('findUserById')
            ->will(returnValue($user));

        $checker = $this->createTokenChecker($db);
        $request = $this->createValidRequest();

        $this->assertNull($checker($request));
    }

    private function createTokenChecker(DbInterface $db)
    {
        return new TokenChecker(
            $db,
            $this->getMock('Psr\Log\LoggerInterface')
        );
    }

    private function createMockDb()
    {
        return $this->getMock('Spika\Db\DbInterface');
    }

    private function createFixtureUser()
    {
         return array(
            '_id'             => self::FIXTURE_USER_ID,
            'token'           => self::FIXTURE_TOKEN,
            'token_timestamp' => time(),
        );
    }

    private function createValidRequest()
    {
        return new Request(
            array(),
            array(),
            array(
                'user_id' => self::FIXTURE_USER_ID,
                'token'   => self::FIXTURE_TOKEN,
            )
        );
    }
}
