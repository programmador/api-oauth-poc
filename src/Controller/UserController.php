<?php

namespace App\Controller;

use App\Entity\User;
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
    public function grant(Request $request)
    {
        // @todo catch any other 500 - it's structure is too complex for api

        $r = json_decode($request->getContent());
        if ($r === null && json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse("expecting json for input", 400);
        }
        if(!isset($r->username) || !isset($r->password)) {
            return new JsonResponse("expecting 'username' and 'password' in input json", 400);
        }

        $em = $this->getDoctrine();
        $repo = $em->getRepository(User::class);
        $user = $repo->findOneBy(['name' => $r->username]);
        if(!$user) {
            /* Please let this line live here for my educational reasons.
            Yes, it's really bad to keep commented garbage in code. */
            //throw $this->createAccessDeniedException();
            return new JsonResponse("wrong 'username' or 'password'", 403);
        }

        if(!$user->isPasswordValid($r->password)) {
            return new JsonResponse("wrong 'username' or 'password'", 403);
        }

        // @todo implement token creation and returning
        return $this->json("expect Your token here soon ;-)");
    }
}
