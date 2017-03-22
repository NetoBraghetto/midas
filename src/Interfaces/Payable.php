<?php
namespace Braghetto\Midas\Interfaces;

/**
* Payable
*/

interface Payable
{
    public function fill(array $data);
    public function pay();
}