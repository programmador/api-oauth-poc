<?php

namespace App\ApiSchema;

/**
 * @OA\Schema(
 *     title="Grant response model",
 *     description="Grant response model",
 * )
 */
class GrantResponse
{
    use FillableResponseTrait;

    public function __construct(array $fields)
    {
        $this->fill($fields);
    }

    /**
     * @OA\Property(
     *     description="Access token",
     *     title="Access token",
     * )
     *
     * @var string
     */
    public $access_token;

    /**
     * @OA\Property(
     *     description="Mac key",
     *     title="Mac key",
     * )
     *
     * @var string
     */
    public $mac_key;

    /**
     * @OA\Property(
     *     description="Token type",
     *     title="Token type",
     * )
     *
     * @var string
     */
    public $token_type;


    /**
     * @OA\Property(
     *     description="Scope",
     *     title="Scope",
     * )
     *
     * @var string
     */
    public $scope;

    /**
     * @OA\Property(
     *     description="Expiration minutes",
     *     title="Expiration minutes",
     *     format="int64",
     * )
     *
     * @var integer
     */
    public $expires_in;
}