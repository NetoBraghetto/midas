<?php
namespace Braghetto\Midas\Payers;

use Braghetto\Midas\Interfaces\Payable;
/**
* MercadoPago
*/
class MercadoPago implements Payable
{
	private $id;
	private $secret;

	public function __construct($client_id, $client_secret)
	{
		$this->id = $client_id;
		$this->secret = $client_secret;
	}
}