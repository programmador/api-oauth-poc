<?php

namespace App\ApiSchema;

use StdClass;

trait FillableRequestTrait
{
    private function fill(StdClass $r)
    {
        foreach(get_object_vars($this) as $n => $v) {
            if(property_exists($r, $n)) {
                $this->{$n} = $r->{$n};
            }
        }
    }
}