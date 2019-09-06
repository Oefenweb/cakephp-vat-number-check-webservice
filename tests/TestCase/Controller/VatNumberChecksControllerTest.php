<?php
namespace VatNumberCheck\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * VatNumberChecksController Test Case.
 *
 * @property \VatNumberCheck\Controller\VatNumberChecksController $VatNumberChecks
 */
class VatNumberChecksControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->useHttpServer(true);
    }

    /**
     * The URL to call in the tests of `check`
     *
     * @var string
     */
    const CHECK_VAT_URL = '/vat_number_check/vat_number_checks/check.json';

    /**
     * Tests `/vat_number_check/vat_number_checks/check.json`.
     *
     * @return void
     */
    public function testCheckPost()
    {
        $data = ['vatNumber' => 'NL820345672B01'];
        $this->post(static::CHECK_VAT_URL, $data);
        $this->assertResponseOk();
        $this->assertContentType('application/json');

        $actual = $this->_response->getBody();
        $expected = array_merge($data, ['status' => 'ok']);
        $this->assertSame($expected, json_decode($actual, true));
    }

    /**
     * Tests `/vat_number_check/vat_number_checks/check.json`.
     *
     *  Get request
     *
     * @return void
     */
    public function testCheckGet()
    {
        $this->get(static::CHECK_VAT_URL);
        $this->assertResponseCode(503);
    }

    /**
     * Tests `/vat_number_check/vat_number_checks/check.json`.
     *
     *  Post request, incorrect VAT.
     *
     * @return void
     */
    public function testCheckIncorrectVat()
    {
        $data = ['vatNumber' => 'NL820345672B02'];
        $this->post(static::CHECK_VAT_URL, $data);
        $this->assertResponseOk();
        $this->assertContentType('application/json');

        $actual = $this->_response->getBody();
        $expected = array_merge($data, ['status' => 'failure']);
        $this->assertSame($expected, json_decode($actual, true));
    }

    // TODO: add test for 503 `testCheckPostCorrectVatTimeout`
}
