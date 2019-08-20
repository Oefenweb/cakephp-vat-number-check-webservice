<?php
namespace VatNumberCheck\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use VatNumberCheck\Controller\VatNumberChecksController;
use VatNumberCheck\Utility\VatNumberCheck;
use VatNumberCheck\Test\TestApp;
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
	public function setUp() {
		$this->useHttpServer(true);
		$VatNumberChecks = new VatNumberCheck();
	}
/**
 * Tests `/vat_number_check/vat_number_checks/check.json`.
 *
 * @return void
 */
	public function testCheck() {
		$url = '/vat_number_check/vat_number_checks/check.json';
		// Post request, correct vat
		$data = ['vatNumber' => 'NL820345672B01'];

		$this->get('url');
		$actual = $this->post($url, $data);
		$expected = array_merge($data, ['status' => 'ok']);
		// // Test response body
		// $this->assertSame($expected, json_decode($actual, true));
		// $actual = $VatNumberChecks->response->statusCode();
		// $expected = 200;
		// // Test response code
		// $this->assertSame($expected, $actual);
		// // Get request
		// $VatNumberChecks = $this->_getMock();
		// $data = ['vatNumber' => ''];
		// $actual = $this->get($url, ['return' => 'contents']);
		// $expected = array_merge($data, ['status' => 'failure']);
		// $this->assertSame($expected, json_decode($actual, true));
		// // Post request, incorrect vat
		// $VatNumberChecks = $this->_getMock();
		// $data = ['vatNumber' => 'NL820345672B02'];
		// $actual = $this->get($url, ['return' => 'contents', 'data' => $data, 'method' => 'post']);
		// $expected = array_merge($data, ['status' => 'failure']);
		// $this->assertSame($expected, json_decode($actual, true));
		// // Post request, correct vat, timeout
		// $VatNumberChecks = $this->generate('VatNumberCheck.VatNumberChecks', [
		// 	'models' => [
		// 		'VatNumberCheck.VatNumberCheck' => ['check']
		// 	]
		// ]);
		// $VatNumberChecks->VatNumberCheck->setDataSource('vatNumberCheckWebservice');
		// $VatNumberChecks->VatNumberCheck->expects($this->any())
		// 	->method('check')->will($this->throwException(new Exception()));
		// $data = ['vatNumber' => 'NL820345672B01'];
		// $actual = $this->get($url, ['return' => 'contents', 'data' => $data, 'method' => 'post']);
		// $expected = array_merge($data, ['status' => 'failure']);
		// // Test response body
		// $this->assertSame($expected, json_decode($actual, true));
		// $actual = $VatNumberChecks->response->statusCode();
		// $expected = 503;
		// // Test response code
		// $this->assertSame($expected, $actual);
	}
}