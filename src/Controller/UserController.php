<?php

namespace App\Controller;

use App\ApiSchema\GrantRequest;
use App\ApiSchema\ValidateRequest;
use App\Entity\User;
use App\Model\Token;
use App\Service\TokenStorage;
use StdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        $r = new GrantRequest($this->getJsonRequest($request));
        $this->validateGrantRequest($r);
        $user = $this->getUserForGrant($r->username);
        $this->validateUserForGrant($user, $r->password);
        return $this->json($ts->newToken($user->getId(), $r->scope)->toGrantRepresentation());
    }

    /**
     * @Route("/validate", name="validate", methods={"POST"},
     *     defaults={"_format": "json"},
     *     requirements={
     *         "_format": "json",
     *     }
     * )
     *
     * @OA\Post(
     *     path="/validate",
     *     @OA\RequestBody(
     *         description="Validate request",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ValidateRequest"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Validate response",
     *         @OA\JsonContent(ref="#/components/schemas/ValidateResponse")
     *     )
     * )
     *
     */
    public function validate(Request $request, TokenStorage $ts)
    {
        $r = new ValidateRequest($this->getJsonRequest($request));
        $this->validateValidationRequest($r);
        $token = $ts->findToken($r->token, $r->scope);
        $this->validateTokenForGrant($token);
        return $this->json($token->toValidateRepresentation());
    }

    private function getJsonRequest(Request $request) : StdClass
    {
        $r = json_decode($request->getContent());
        if ($r === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new BadRequestHttpException("expecting json for input");
        }
        return $r;
    }

    private function validateGrantRequest(GrantRequest $r)
    {
        if(!isset($r->username) || !isset($r->password) || !isset($r->scope)) {
            throw new BadRequestHttpException(
                "expecting 'username' 'password' and 'scope' in input json"
            );
        }
    }

    private function validateValidationRequest(ValidateRequest $r)
    {
        if(!isset($r->token) || !isset($r->scope)) {
            throw new BadRequestHttpException(
                "expecting 'token' and 'scope' in input json"
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

    private function validateTokenForGrant(Token $token)
    {
        if(!$token->getUid() || !$token->getMacKey()) {
            throw new NotFoundHttpException("token not found");
        }
    }
}
