<?php

namespace App\ApiSchema;

/**
 * @OA\Schema(
 *     title="Grant request model",
 *     description="Grant request model",
 * )
 */
class GrantRequest
{
    /**
     * @OA\Property(
     *     description="Username",
     *     title="Username",
     * )
     *
     * @var string
     */
    private $username;

    /**
     * @OA\Property(
     *     description="Password",
     *     title="Password",
     * )
     *
     * @var string
     */
    private $password;

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