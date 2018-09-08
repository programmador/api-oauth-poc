<?php

namespace App\ApiSchema;

/**
 * @OA\Schema(
 *     title="Validate response model",
 *     description="Validate response model",
 * )
 */
class ValidateResponse
{
    /**
     * @OA\Property(
     *     description="User identifier",
     *     title="User identifier",
     *     format="int64",
     * )
     *
     * @var integer
     */
    public $uid;

    /**
     * @OA\Property(
     *     description="Mac key",
     *     title="Mac key",
     * )
     *
     * @var string
     */
    public $mac_key;
}