<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Request\ParamFetcherInterface;

use AppBundle\Entity\User;

class UserController extends Controller
{
    const DEFAULT_CREDIT = 100;
    /**
     * @Rest\View()
     * @Rest\RequestParam(name="firstname")
     * @Rest\Post("/api/user/create")
     */
    public function createAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $em        = $this->getDoctrine()->getManager();
        $firstname = $paramFetcher->get('firstname');
        $user      = new User();
        $user->setName($firstname);
        $user->setCredit(self::DEFAULT_CREDIT);
        $em->persist($user);
        $em->flush();
        
        $response = new JsonResponse($user->getId());
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }
}
