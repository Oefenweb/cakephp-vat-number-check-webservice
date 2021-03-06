<?php
App::uses('VatNumberChecksController', 'VatNumberCheck.Controller');

/**
 * VatNumberChecksController Test Case
 *
 * @property VatNumberCheck.VatNumberChecksController $VatNumberChecks
 */
class VatNumberChecksControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = [];

/**
 * Tests `/vat_number_check/vat_number_checks/check.json`.
 *
 * @return void
 */
	public function testCheck() {
		$url = '/vat_number_check/vat_number_checks/check.json';

		// Post request, correct vat

		$VatNumberChecks = $this->_getMock();

		$data = ['vatNumber' => 'NL820345672B01'];

		$actual = $this->testAction($url, ['return' => 'contents', 'data' => $data, 'method' => 'post']);
		$expected = array_merge($data, ['status' => 'ok']);

		// Test response body
		$this->assertSame($expected, json_decode($actual, true));

		$actual = $VatNumberChecks->response->statusCode();
		$expected = 200;

		// Test response code
		$this->assertSame($expected, $actual);

		// Get request

		$VatNumberChecks = $this->_getMock();

		$data = ['vatNumber' => ''];

		$actual = $this->testAction($url, ['return' => 'contents']);
		$expected = array_merge($data, ['status' => 'failure']);

		$this->assertSame($expected, json_decode($actual, true));

		// Post request, incorrect vat

		$VatNumberChecks = $this->_getMock();

		$data = ['vatNumber' => 'NL820345672B02'];

		$actual = $this->testAction($url, ['return' => 'contents', 'data' => $data, 'method' => 'post']);
		$expected = array_merge($data, ['status' => 'failure']);

		$this->assertSame($expected, json_decode($actual, true));
	}

/**
 * Tests `/vat_number_check/vat_number_checks/check.json`.
 *
 *  Post request, correct vat, timeout
 *
 * @return void
 * @requires PHPUnit 5.7
 * @todo This should ever happen, right?
 */
	public function testCheck503() {
		$url = '/vat_number_check/vat_number_checks/check.json';

		$VatNumberChecks = $this->generate('VatNumberCheck.VatNumberChecks', [
			'models' => [
				'VatNumberCheck.VatNumberCheck' => ['check']
			]
		]);
		$VatNumberChecks->VatNumberCheck->setDataSource('vatNumberCheckWebservice');
		$VatNumberChecks->VatNumberCheck->expects($this->any())
			->method('check')->will($this->throwException(new Exception()));

		$data = ['vatNumber' => 'NL820345672B01'];

		$actual = $this->testAction($url, ['return' => 'contents', 'data' => $data, 'method' => 'post']);
		$expected = array_merge($data, ['status' => 'failure']);

		// Test response body
		$this->assertSame($expected, json_decode($actual, true));

		$actual = $VatNumberChecks->response->statusCode();
		$expected = 503;

		// Test response code
		$this->assertSame($expected, $actual);
	}

/**
 * Gets a mocked controller instance.
 *
 * @return VatNumberChecksController
 */
	protected function _getMock() {
		$VatNumberChecks = $this->generate('VatNumberCheck.VatNumberChecks');
		$VatNumberChecks->VatNumberCheck = ClassRegistry::init('VatNumberCheck.VatNumberCheck', true);
		$VatNumberChecks->VatNumberCheck->setDataSource('vatNumberCheckWebservice');

		return $VatNumberChecks;
	}
}
