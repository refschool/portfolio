<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DonPaymentController extends AbstractController
{
    /** 
     * @Route("/don",name="don")
     * 
     */
    public function showCardForm()
    {
        \Stripe\Stripe::setApiKey('sk_test_51KfKXAArrVmPtiM7c2l9METinjYeDSwXBE6qtckuF6z6bq3OEfmePz8aWrJemNSNoUR8CKvyxtQPrxc8qg1oPPZq006I9eqvnH');
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => 50,
            'currency' => 'eur',
        ]);

        //dd($paymentIntent->client_secret);
        return $this->render('don.html.twig', [
            'clientSecret' => $paymentIntent->client_secret
        ]);
    }
}
