<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserRequest;
use App\Form\FeedBackFormType;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(Request $request): Response
    {
        $user_request = new UserRequest();
        $form = $this->createForm(FeedBackFormType::class, $user_request, [
            'attr'=>['class'=>'form-feedback']
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $user_request = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($user_request);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('homepage/home.html.twig', [
            'controller_name' => 'HomepageController',
            'feedback_form' => $form->createView(),
        ]);
    }
}
