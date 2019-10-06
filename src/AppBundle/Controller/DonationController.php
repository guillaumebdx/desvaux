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
use AppBundle\Entity\Donation;

class DonationController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\RequestParam(name = "user_id")
     * @Rest\RequestParam(name = "record_id")
     * @Rest\RequestParam(name = "amount")
     * @Rest\Post("/api/donation")
     */
    public function newDonationAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $amount   = $paramFetcher->get('amount');
        $em       = $this->getDoctrine()->getManager();
        $user     = $em->getRepository(User::class)->find($paramFetcher->get('user_id'));
        $donation = new Donation();
        $donation->setRecordId($paramFetcher->get('record_id'));
        $donation->setUser($user);
        $donation->setAmount($amount);
        $user->setCredit($user->getCredit() - $amount);
        $em->persist($donation);
        $em->persist($user);
        $em->flush();
        $response = new JsonResponse('done');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
        
    }
}