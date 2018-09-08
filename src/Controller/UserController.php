<?php

namespace App\Controller;

use App\ApiSchema\GrantRequest;
use App\Entity\User;
use App\Service\TokenStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Info(
 *         version="1.0.0",
 *         title="Mauris User API",
 *         description="Mauris User API",
 * )
 */
class UserController extends AbstractController
{
    /**
     * @Route("/grant", name="grant", methods={"POST"},
     *     defaults={"_format": "json"},
     *     requirements={
     *         "_format": "json",
     *     }
     * )
     *
     * @OA\Post(
     *     path="/grant",
     *     @OA\RequestBody(
     *         request="Grant",
     *         description="Grant request",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/GrantRequest"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Grant response",
     *         @OA\JsonContent(ref="#/components/schemas/GrantResponse")
     *     )
     * )
     *
     */
    public function grant(Request $request, TokenStorage $ts)
    {
        $r = $this->getJsonRequest($request);
        $this->validateGrantRequest($r);
        $user = $this->getUserForGrant($r->username);
        $this->validateUserForGrant($user, $r->password);
        return $this->json($ts->getToken($user->getId(), $r->scope)->toArray());
    }

    private function getJsonRequest(Request $request) : GrantRequest
    {
        $r = json_decode($request->getContent());
        if ($r === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new BadRequestHttpException("expecting json for input");
        }
        return new GrantRequest($r);
    }

    private function validateGrantRequest(GrantRequest $r)
    {
        if(!isset($r->username) || !isset($r->password) || !isset($r->scope)) {
            throw new BadRequestHttpException(
                "expecting 'username' 'password' and 'scope' in input json"
            );
        }
    }

    private function getUserForGrant(string $name) : ?User
    {
        $em = $this->getDoctrine();
        $repo = $em->getRepository(User::class);
        return $repo->findOneBy(compact('name'));
    }

    private function validateUserForGrant(?User $user, ?string $password)
    {
        if(!$password || !$user || !$user->isPasswordValid($password)) {
            throw new AccessDeniedHttpException("wrong 'username' or 'password'");
        }
    }
}
