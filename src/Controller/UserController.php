<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\TokenStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/grant", name="grant", methods={"POST"},
     *     defaults={"_format": "json"},
     *     requirements={
     *         "_format": "json",
     *     }
     * )
     */
    public function grant(Request $request, TokenStorage $ts)
    {
        $r = json_decode($request->getContent());
        if ($r === null && json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse("expecting json for input", 400);
        }
        if(!isset($r->username) || !isset($r->password) || !isset($r->scope)) {
            return new JsonResponse("expecting 'username' 'password' and 'scope' in input json",
                400);
        }

        $em = $this->getDoctrine();
        $repo = $em->getRepository(User::class);
        $user = $repo->findOneBy(['name' => $r->username]);
        if(!$user || !$user->isPasswordValid($r->password)) {
            return new JsonResponse("wrong 'username' or 'password'", 403);
        }

        return $this->json($ts->createToken($user->getId(), $r->scope)->toArray());
    }
}
