<?php
namespace Braghetto\Midas\Payers;

use Braghetto\Midas\Interfaces\Payable;
use MP;

/**
* MercadoPago
*/

class MercadoPago extends AbstractPayer implements Payable
{
    private $client;

    private $payment_methods = [
        '101' => 'visa'
    ];

    private $status = [
        'approved' => '1',
        'pending' => '2',
        'authorized' => 'authorized',
        'in_process' => 'in_process',
        'in_mediation' => 'in_mediation',
        'rejected' => '4',
        'cancelled' => '5',
        'refunded' => 'refunded',
        'charged_back' => 'charged_back',
    ];

    // protected $attributes;

    protected $parsedOrder;

    protected $response;

    // protected $items;

    // protected $parsedItems;

    // protected $customer;

    // protected $parsedCustomer;

    // protected $address;

    // protected $parsedAddress;

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
            [
                'id',
                'name',
                'image_url',
                'description',
                'quantity',
                'price',
            ]
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
        ],
    ];

    public function __construct($client_id, $client_secret = null, $sandbox = true)
    {
        if (isset($client_secret)) {
            $this->client = new MP($client_id, $client_secret);
        } else {
            $this->client = new MP($client_id);
        }
        $this->client->sandbox_mode($sandbox);
    }

    public function fill(array $data)
    {
        if (!$this->validate($this->validationFields, $data)) {
            return false;
        }
        $this->parsedOrder = [
            'transaction_amount' => $data['order']['total'],
            'installments' => $data['order']['installments'],
            'payment_method_id' => $this->payment_methods[$data['payment']['method']],
            'payer' => [
                'email' => $data['customer']['email'],
            ],
            'external_reference' => $data['order']['id'],
            'additional_info' => [
                'items' => $this->parseItems($data['items']),
                'payer' => [
                    'first_name' => $data['customer']['name'],
                    'last_name' => $data['customer']['last_name'],
                    'phone' => [
                        'area_code' => $data['customer']['phones'][0]['area_code'],
                        'number' => $data['customer']['phones'][0]['number'],
                    ],
                    'address' => [
                        'street_name' => $data['shipping_address']['street'],
                        'street_number' => $data['shipping_address']['number'],
                        'zip_code' => $data['shipping_address']['zip_code'],
                    ],
                ],
                'shipments' => [
                    'receiver_address' => [
                        'street_name' => $data['shipping_address']['street'],
                        'street_number' => $data['shipping_address']['number'],
                        'zip_code' => $data['shipping_address']['zip_code'],
                    ],
                ],
            ],
        ];

        if (!empty($data['vendor'])) {
            $this->parsedOrder = array_merge($this->parsedOrder, $data['vendor']);
        }
        return true;
    }

    public function pay()
    {
        $this->response = $this->client->post('/v1/payments', $this->parsedOrder);
        $response = $this->response['response'];
        foreach ($response['fee_details'] as $fee) {
            if ($fee['type'] == 'mercadopago_fee') {
                $vendor_fee = $fee['amount'];
            }
        }
        if ($this->response['status'] == 201) {
            return [
                'vendor_id' => $response['id'],
                'status' => $this->status[$response['status']],
                'order_total' => $response['transaction_details']['total_paid_amount'],
                'received_amount' => $response['transaction_details']['net_received_amount'],
                'total_paid' => $response['transaction_details']['total_paid_amount'],
                'installments' => $response['installments'],
                'installment_value' => $response['transaction_details']['installment_amount'],
                'vendor_fee' => $vendor_fee,

                'http_status' => $this->response['status'],
                'response' => $response
            ];
        }
        return false;
    }

    public function getResponse()
    {
        return $this->response;
    }

    protected function parseItems(array $items)
    {
        $parsed = [];
        foreach ($items as $item) {
            $parsed[] = [
                'id' => $item['id'],
                'title' => $item['name'],
                'picture_url' => $item['image_url'],
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
            ];
        }
        return $parsed;
    }
}
