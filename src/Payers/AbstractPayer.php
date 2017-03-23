<?php
namespace Braghetto\Midas\Payers;

/**
* AbstractPayer
*/
abstract class AbstractPayer
{
    protected $validationFields = [];

    protected function validateSequentialArray($fields, $items)
    {
        $fields = array_flip($fields);
        foreach ($items as $item) {
            if (count(array_diff_key($fields, $item)) > 0) {
                return false;
            }
        }
        return true;
    }

    protected function validate($fields, $data)
    {
        foreach ($fields as $key => $field) {
            if (is_array($field)) {
                if (!isset($data[$key]) || !is_array($data[$key])) {
                    return false;
                }
                if (isset($field[0], $data[$key][0]) && is_array($field[0]) && is_array($data[$key][0])) {
                    if (!$this->validateSequentialArray($field[0], $data[$key])) {
                        return false;
                    }
                } elseif (!$this->validate($field, $data[$key])) {
                    return false;
                }
            } else {
                if (!isset($data[$field])) {
                    return false;
                }
            }
        }
        return true;
    }
}
