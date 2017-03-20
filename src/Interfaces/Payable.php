<?php
namespace Braghetto\Midas\Interfaces;

/**
* Payable
*/

interface Payable
{
    public function setItems();
    public function fill();
    public function pay();
}