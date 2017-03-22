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

	private $payment_methods = [
		'101' => 'visa'
	];

	protected $order;

	protected $parsedOrder;

	protected $items;

	protected $parsedItems;

	protected $customer;

	protected $parsedCustomer;

	protected $address;

	protected $parsedAddress;

	protected $validationFields = [
		'order' => [
	        'id',
	        'total',
	        'installments',
	    ],
	    'payment' => [
	        'method',
	    ],
	    'items' => [
	        'id',
			'name',
			'image_url',
			'description',
			'quantity',
			'price',
	    ],
	    'shipping_address' => [
	        'street',
	        'number',
	        'zip_code',
	    ],
	    'customer' => [
	        'name',
	        'last_name',
	        'email',
	        'phones' => [
	            ['area_code', 'number'],
	        ],
	        'created_at',
	    ],
	];
	// protected $orderValidationFields = [
	// 	'id',
	// 	'total',
	// 	'payment_method',
	// 	'installments',
	// 	'payment',
	// ];

	// protected $itemValidationFields = [
	// 	'id',
	// 	'name',
	// 	'image_url',
	// 	'description',
	// 	'quantity',
	// 	'price',
	// ];

	// protected $shippingValidationFields = [
	// 	'street',
	// 	'number',
	// 	'zip_code',
	// ];

	// protected $customerValidationFields = [
	// 	'name',
	// 	'last_name',
	// 	'phones',
	// 	'created_at',
	// ];

	public function __construct($client_id, $client_secret)
	{
		$this->id = $client_id;
		$this->secret = $client_secret;
	}

    public function fill(array $data)
    {
    	if (
    		!isset($data['items'], $data['shipping'], $data['customer']) &&
    		count(array_diff_key($this->orderValidationFields, $data['order'])) > 0
    	) {
    		return false;
    	}
    	$this->order = $data['order'];

    	$this->setItems($data['items']);
    	$this->setShipping($data['shipping']);
    	$this->setCustomer($data['customer']);
    	$this->parsedOrder = [
    		'transaction_amount' => $data['order']['total'],
			'installments' => $data['order']['installments'],
			'payment_method_id' => $this->payment_methods[$data['payment']['method']],
			'payer' => $this->parsedCustomer,
			'external_reference' => $data['order']['id'],
			'additional_info' => [
				'items' => $this->parsedItems,
				'payer' => $this->parsedCustomer,
				'shipments' => $this->parsedAddress
			],
    	];

    	if (!empty($data['vendor'])) {
    		$this->parsedOrder = array_merge($this->parsedOrder, $data['vendor']);
    	}
    }

    public function pay()
    {
    	if (isset($this->parsedItems, $this->parsedAddress, $this->parsedCustomer)) {
    		
    	}
    	return false;
    }

    protected function setItems(array $items)
    {
    	if (!$this->validateItems($items)) {
    		return false;
    	}
    	$this->items = $items;
    	$this->parsedItems = [];

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

		$this->parsedAddress = [
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

		$this->parsedCustomer = [
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