<?php

namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HomePageController extends AbstractController
{
    protected $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    /**
     * @Route("/", name="homepage")
     */
    public function hello(EntityManagerInterface $em)
    {


        return $this->render('hello.html.twig');
    }

    /**
     * @Route("/cv", name="cv")
     */
    public function cv()
    {
        return $this->render('cv.html.twig');
    }

    /**
     * @Route("/don", name="don")
     */
    public function don()
    {
        return $this->render('don.html.twig');
    }

    /**
     * @Route("/dontpn", name="dontpn")
     */
    public function dontpn()
    {
        return $this->render('dontpn.html.twig');
    }

    /**
     * @Route("/charge", name="charge")
     */
    public function paiement()
    {
        $var = 'sk_test_51KfKXAArrVmPtiM7c2l9METinjYeDSwXBE6qtckuF6z6bq3OEfmePz8aWrJemNSNoUR8CKvyxtQPrxc8qg1oPPZq006I9eqvnH';

        \Stripe\Stripe::setApiKey($var);

        $token = $_POST['stripeToken']; // This is a $20.00 charge in US Dollar.

        try {
            $charge = \Stripe\Charge::create(
                array(
                    'amount' => 2000,
                    'currency' => 'usd',
                    'source' => $token
                )
            );
        } catch (\Stripe\Exception\CardException $e) {
            $message_exception = '<br> Status is : ' . $e->getHttpStatus() . '<br>';
            $message_exception .= '<br> Type is : ' . $e->getError()->type . '<br>';
            $message_exception .= '<br> Code is : ' . $e->getError()->code . '<br>';
            $message_exception .= '<br> Param is : ' . $e->getError()->param . '<br>';
            $message_exception .= '<br> Message is : ' . $e->getError()->message . '<br>';
            return $this->echec_paiement($e->getError()->code);
        } catch (\Stripe\Exception\RateLimitException $e) {
            // Too many requests made to the API too quickly
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Invalid parameters were supplied to Stripe's API
        } catch (\Stripe\Exception\AuthenticationException $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            // Network communication with Stripe failed
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
        } catch (\Exception $e) {
            // Something else happened, completely unrelated to Stripe
        }
        return $this->reussi_paiement();
        return new Response("bro");
    }

    /**
     * @Route("/reussi", name="reussi")
     */
    function reussi_paiement()
    {
        return $this->render('reussi.html.twig', ['msg' => 'toto']);
    }

    /**
     * @Route("/echec", name="echec")
     */
    function echec_paiement($data)
    {
        return $this->render('echec.html.twig', ['msg' => $data]);
    }

    /**
     * @Route("/projets", name="projets")
     */
    function projets($data)
    {
        return $this->render('projets.html.twig', ['msg' => $data]);
    }

}
