<?php

namespace App\ApiSchema;

use StdClass;

trait FillableResponseTrait
{
    private function fill(array $fields)
    {
        foreach($fields as $n => $v) {
            if(array_key_exists($n, $fields)) {
                $this->{$n} = $fields[$n];
            }
        }
    }
}