<?php
namespace Braghetto\Midas\Payers;

/**
* AbstractPayer
*/
abstract class AbstractPayer
{
    protected $validationFields = [];
    protected $missingFields = [];
    private $tail = '';

    protected function validateSequentialArray($fields, $items, $bail)
    {
        $fields = array_flip($fields);
        foreach ($items as $key => $item) {
            $diff = array_diff_key($fields, $item);
            if (count($diff) > 0) {
                $localTail = $this->tail . $key . '.';
                foreach ($diff as $field => $value) {
                    $this->missingFields[] = $localTail . $field;
                }
                if ($bail) {
                    return false;
                }
            }
        }
        return true;
    }

    protected function validate($fields, $data, $bail)
    {
        foreach ($fields as $key => $field) {
            if (is_array($field)) {
                $this->tail .= $key . '.';
                if (!isset($data[$key]) || !is_array($data[$key])) {
                    if ($bail) {
                        return false;
                    }
                }

                if (isset($field[0], $data[$key][0]) && is_array($field[0]) && is_array($data[$key][0])) {
                    if (!$this->validateSequentialArray($field[0], $data[$key], $bail)) {
                        if ($bail) {
                            return false;
                        }
                    }
                } else {
                    if (!$this->validate($field, $data[$key], $bail)) {
                        if ($bail) {
                            return false;
                        }
                    }
                }
            } else {
                if (!isset($data[$field])) {
                    $this->missingFields[] = $this->tail . $field;
                    if ($bail) {
                        return false;
                    }
                }
            }
        }
        $this->tail = '';
        return empty($this->missingFields);
    }

    public function fill(array $data, $bail = false)
    {
        return $this->missingFields;
    }

    public function getMissingFields()
    {
        return $this->missingFields;
    }
}
