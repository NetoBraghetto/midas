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

	protected $items;

	protected $parsedItems;

	protected $customer;

	protected $parsedCustomer;

	protected $address;

	protected $parsedAddress;

	protected $itemValidationFields = [
		'id',
		'name',
		'image_url',
		'description',
		'quantity',
		'price',
	];

	protected $shippingValidationFields = [
		'street',
		'number',
		'zip_code',
	];

	protected $customerValidationFields = [
		'name',
		'last_name',
		'phones',
		'created_at',
	];

	public function __construct($client_id, $client_secret)
	{
		$this->id = $client_id;
		$this->secret = $client_secret;
	}

    public function fill(array $data)
    {
    	if (!isset($data['items'], $data['shipping'], $data['customer'])) {
    		return false;
    	}

    	$this->setItems($data['items']);
    	$this->setShipping($data['shipping']);
    	$this->setCustomer($data['customer']);
    }

    public function pay()
    {
    	if (isset($this->parsedItems, $this->parsedAddress, $this->parsedCustomer)) {
    		
    	}
    	return false;
    }

    public function setItems(array $items)
    {
    	if (!$this->validateItems($items)) {
    		return false;
    	}
    	$this->items = $items;

    	foreach ($items as $item) {
    		$this->parsedItems[] = [
				'id' => $item['id'],
				'title' => $item['name'],
				'picture_url' => $item['image_url'],
				'description' => $item['description'],
				'quantity' => $item['quantity'],
				'unit_price' => $item['price'],
    		];
    	}
    }

    public function setShipping(array $address)
    {
    	if (count(array_diff_key($this->addressValidationFields, $address)) > 0) {
			return false;
		}
    	$this->address = $address;

		$this->parsedAddress[] = [
			'receiver_address' => [
				'street_name' => $address['street'],
				'street_number' => $address['number'],
				'zip_code' => $address['zip_code'],
				// 'floor' => $address['AAA'],
				// 'apartment' => $address['AAA'],
			]
		];
    }

    public function setCustomer(array $customer)
    {
    	if (count(array_diff_key($this->customerValidationFields, $customer)) > 0) {
			return false;
		}
    	$this->customer = $customer;

		$this->parsedCustomer[] = [
			'first_name' => $customer['name'],
			'last_name' => $customer['last_name'],
			'registration_date' => $customer['created_at'],
			'phone' => [
				'area_code' => $customer['phones'][0]['area_code'],
				'number' => $customer['phones'][0]['number'],
			],
			'address' => [
				'street_name' => $this->address['street'],
				'street_number' => $this->address['number'],
				'zip_code' => $this->address['zip_code'],
			],
		];
    }

	protected function validateItems(array $items)
	{
		foreach ($items as $item) {
			if (count(array_diff_key($this->itemValidationFields, $item)) > 0) {
				return false;
			}
		}
		return true;
	}
}