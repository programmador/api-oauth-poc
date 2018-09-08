<?php

namespace App\ApiSchema;

use StdClass;

/**
 * @OA\Schema(
 *     title="Validate request model",
 *     description="Validate request model",
 * )
 */
class ValidateRequest
{
    use FillableRequestTrait;

    public function __construct(StdClass $r)
    {
        $this->fill($r);
    }

    /**
     * @OA\Property(
     *     description="Token",
     *     title="Token",
     * )
     *
     * @var string
     */
    public $token;

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