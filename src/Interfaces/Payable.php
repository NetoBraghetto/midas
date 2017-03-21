<?php
namespace Braghetto\Midas\Interfaces;

/**
* Payable
*/

interface Payable
{
    public function fill(array $data);
    public function setItems(array $items);
    public function setCustomer(array $items);
    public function pay();
}