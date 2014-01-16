<?php
/**
 * This file is part of the WillCorpZombieBundle package
 *
 * (c) Yann Eugoné <yann.eugone@gmail.com> ; William Sauvan <william.sauvan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WillCorp\ZombieBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class GameControllerTest
 *
 * todo Implement database bootstrap
 *          [@link http://sgoettschkes.blogspot.fr/2012/06/symfony2-test-database-best-pratice.html}
 *
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class GameControllerTest extends WebTestCase
{
    /**
     * Authentication vars used for requests
     * @var array
     */
    protected static $authenticationServerVars = array(
        'PHP_AUTH_USER' => 'yann.eugone',
        'PHP_AUTH_PW'   => 'yann'
    );

    /**
     * Test the method "upgradeStrongholdAction"
     * Assert that the use must be authenticated to access this page
     */
    public function testUpgradeStrongholdAuthentication()
    {
        $client = static::createClient();
        $this->requestAjax($client, 'GET', '/game/stronghold/2/upgrade');

        $response = $client->getResponse();

        $this->assertEquals(302, $response->getStatusCode());
    }

    /**
     * Test the method "upgradeStrongholdAction"
     * Assert that the required stronghold belong to the authenticated user
     */
    public function testUpgradeStrongholdAuthorisation()
    {
        $client = static::createAuthenticatedClient();
        $this->requestAjax($client, 'GET', '/game/stronghold/1/upgrade');

        $response = $client->getResponse();

        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * Test the method "upgradeStrongholdAction"
     *
     * todo Test content
     */
    public function testUpgradeStronghold()
    {
        $client = static::createAuthenticatedClient();
        $this->requestAjax($client, 'GET', '/game/stronghold/2/upgrade');

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $jsonResponse = $this->getJsonResponse($response);

        $this->assertJsonResponseErrors(array(), $jsonResponse);
    }

    /**
     * Test the method "upgradeBuildingAction"
     * Assert that the use must be authenticated to access this page
     */
    public function testUpgradeBuildingAuthentication()
    {
        $client = static::createClient();
        $this->requestAjax($client, 'GET', '/game/building/4/upgrade');

        $response = $client->getResponse();

        $this->assertEquals(302, $response->getStatusCode());
    }

    /**
     * Test the method "upgradeBuildingAction"
     * Assert that the required building is in the stronghold that belong to the authenticated user
     */
    public function testUpgradeBuildingAuthorisation()
    {
        $client = static::createAuthenticatedClient();
        $this->requestAjax($client, 'GET', '/game/building/1/upgrade');

        $response = $client->getResponse();

        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * Test the method "upgradeBuildingAction"
     *
     * todo Test content
     */
    public function testUpgradeBuilding()
    {
        $client = static::createAuthenticatedClient();
        $this->requestAjax($client, 'GET', '/game/building/4/upgrade');

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $jsonResponse = $this->getJsonResponse($response);

        $this->assertJsonResponseErrors(array(), $jsonResponse);
    }

    /**
     * Create and return an authenticated
     *          {@see createClient}
     */
    protected static function createAuthenticatedClient(array $options = array(), array $server = array())
    {
        $server = array_merge(static::$authenticationServerVars, $server);

        return static::createClient($options, $server);
    }

    /**
     * Perform an AJAX request on the given $client
     *
     * @param Client $client
     * @param string $method
     * @param string $uri
     * @param array  $parameters
     *
     * @return Crawler
     */
    protected function requestAjax(Client $client, $method, $uri, array $parameters = array())
    {
        return $client->request($method, $uri, $parameters, array(), array('HTTP_X-Requested-With' => 'XMLHttpRequest'));
    }

    /**
     * Return the structured response, extracted from the $crawler response
     *
     * @param Response $response
     *
     * @return array
     */
    protected function getJsonResponse(Response $response)
    {
        $response = json_decode(trim($response->getContent()), true);
        $this->assertSame(JSON_ERROR_NONE, json_last_error());

        return $response;
    }

    /**
     * Assert the expected errors of a JSON response
     *
     * @param array $expectedErrors
     * @param array $jsonResponse
     */
    protected function assertJsonResponseErrors(array $expectedErrors, $jsonResponse)
    {
        $actualErrors = $jsonResponse['errors'];

        $this->assertInternalType('array', $actualErrors);

        foreach ($expectedErrors as $property => $message) {
            $this->assertArrayHasKey($property, $actualErrors);
            $this->assertSame($message, $actualErrors[$property]);
        }
    }
}