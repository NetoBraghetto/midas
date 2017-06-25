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
        '101' => 'visa',
        '102' => 'master',
        '103' => 'amex',
        '104' => 'diners',
        '107' => 'elo',
        '120' => 'hipercard',
        '121' => 'melicard',
        '203' => 'bolbradesco',
    ];

    private $status = [
        'pending' => 2,
        'in_process' => 3,
        'approved' => 4,
        'authorized' => 5,
        'rejected' => 6,
        'cancelled' => 7,
        'in_mediation' => 8,
        'refunded' => 9,
        'charged_back' => 10,
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
            // 'installments',
        ],
        'payment' => [
            'group',
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

    public function fill(array $data, $bail = false)
    {
        // if ((int) $data['payment']['group'] === 2) {
        //     unset($this->validationFields['order']['installments']);
        // }
        if (!$this->validate($this->validationFields, $data, $bail)) {
            return false;
        }
        $this->parsedOrder = [
            'transaction_amount' => $data['order']['total'],
            // 'installments' => $data['order']['installments'],
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
        if ((int) $data['payment']['group'] !== 2) {
            $this->parsedOrder['installments'] = $data['order']['installments'];
        }

        if (!empty($data['vendor'])) {
            $this->parsedOrder = array_merge($this->parsedOrder, $data['vendor']);
        }
        return true;
    }

    public function pay()
    {
        $this->response = $this->client->post('/v1/payments', $this->parsedOrder);
        return ($this->response['status'] == 201);
    }

    // public function getFormatedResponse()
    // {
    //     $response = $this->response;
    //     if ($response['status'] == 201) {
    //         $vendor_fee = null;
    //         if (!empty($response['fee_details'])) {
    //             foreach ($response['fee_details'] as $fee) {
    //                 if ($fee['type'] == 'mercadopago_fee') {
    //                     $vendor_fee = $fee['amount'];
    //                 }
    //             }
    //         }
    //         return [
    //             'vendor_id' => $response['id'],
    //             'status' => $this->status[$response['status']],
    //             'order_total' => $response['transaction_details']['total_paid_amount'],
    //             'received_amount' => $response['transaction_details']['net_received_amount'],
    //             'total_paid' => $response['transaction_details']['total_paid_amount'],
    //             'installments' => $response['installments'],
    //             'installment_value' => $response['transaction_details']['installment_amount'],
    //             'vendor_fee' => $vendor_fee,

    //             'http_status' => $response['status'],
    //             'response' => $response
    //         ];
    //     }
    //     return false;
    // }

    public function getRawResponse()
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
