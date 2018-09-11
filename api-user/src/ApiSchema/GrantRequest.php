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
    use FillableRequestTrait;

    public function __construct(StdClass $r)
    {
        $this->fill($r);
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