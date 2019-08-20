<?php
namespace VatNumberCheck\Controller;

use Cake\Event\Event;
use Cake\Network\Exception\InternalErrorException;
use VatNumberCheck\Utility\VatNumberCheck;
/**
 * VatNumberChecks Controller.
 *
 * @property \Cake\Controller\Component\RequestHandlerComponent $RequestHandler
 * @property \VatNumberCheck\Utility\VatNumberCheck $VatNumberCheck
 */
class VatNumberChecksController extends AppController
{
/**
 * Constructor
 *
 * @param CakeRequest $request Request instance.
 * @param CakeResponse $response Response instance.
 */
	public function __construct($request = null, $response = null) {
		parent::__construct($request, $response);
		$this->constructClasses();
		if (!$this->Components->attached('RequestHandler')) {
			$this->RequestHandler = $this->Components->load('RequestHandler');
		}
	}

/**
* Called before the controller action.
*
* @return void
*/
	public function beforeFilter() {
		parent::beforeFilter();
		if (in_array($this->request->action, ['check'], true)) {
			// Disable Security component checks
			if ($this->Components->enabled('Security')) {
				$this->Components->disable('Security');
			}
			// Allow action without authentication
			if ($this->Components->enabled('Auth')) {
				$this->Auth->allow($this->request->action);
			}
		}
	}

/**
* Checks a given vat number (from POST data).
*
* @return void
*/
	public function check() {
		$vatNumber = $this->request->data('vatNumber') ?: '';
		$vatNumber = $this->VatNumberCheck->normalize($vatNumber);
		$jsonData = array_merge(compact('vatNumber'), ['status' => 'failure']);
		try {
			$vatNumberValid = $this->VatNumberCheck->check($vatNumber);
			if ($vatNumberValid) {
				$jsonData = array_merge(compact('vatNumber'), ['status' => 'ok']);
			}
		} catch (Exception $e) {
			$this->response->statusCode(503);
		}
		$this->set(compact('jsonData'));
		$this->set('_serialize', 'jsonData');
	}
}