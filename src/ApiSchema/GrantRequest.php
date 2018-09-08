<?php

namespace App\ApiSchema;

use StdClass;

/**
 * @OA\Schema(
 *     title="Grant request model",
 *     description="Grant request model",
 * )
 */
class GrantRequest
{
    public function __construct(StdClass $r)
    {
        foreach(get_object_vars($this) as $n => $v) {
            if(property_exists($r, $n)) {
                $this->{$n} = $r->{$n};
            }
        }
    }

    /**
     * @OA\Property(
     *     description="Username",
     *     title="Username",
     * )
     *
     * @var string
     */
    public $username;

    /**
     * @OA\Property(
     *     description="Password",
     *     title="Password",
     * )
     *
     * @var string
     */
    public $password;

    /**
     * @OA\Property(
     *     description="Scope",
     *     title="Scope",
     * )
     *
     * @var string
     */
    public $scope;
}