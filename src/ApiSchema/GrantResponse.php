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
    /**
     * @OA\Property(
     *     description="Access token",
     *     title="Access token",
     * )
     *
     * @var string
     */
    private $access_token;

    /**
     * @OA\Property(
     *     description="Mac key",
     *     title="Mac key",
     * )
     *
     * @var string
     */
    private $mac_key;

    /**
     * @OA\Property(
     *     description="Token type",
     *     title="Token type",
     * )
     *
     * @var string
     */
    private $token_type;


    /**
     * @OA\Property(
     *     description="Scope",
     *     title="Scope",
     * )
     *
     * @var string
     */
    private $scope;
}