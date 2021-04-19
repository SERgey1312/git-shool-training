<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\TableOfUsersManager;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonalAreaController extends AbstractController
{
    /**
     * @Route("/personal/area", name="personal_area")
     */
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->render('personal_area/personalArea.html.twig', [
            'controller_name' => 'PersonalAreaController',
            'user' => $user,
        ]);
    }

    /**
     * @Route ("/personal/area/update", name="user_update", methods={"POST"})
     */
    public function userUpdate(UserRepository $userRepository, Request $request, EntityManagerInterface $em) :Response
    {
        $error = '';
        $requestUserId = $request->request->get('user_id');
        $user = $userRepository->findOneBy(['id'=>$requestUserId]);
        $date = new DateTime($request->request->get('user_birthDate'));
        if(isset($user)){
            $user->setCity($request->request->get('user_city'));
            $user->setEmail($request->request->get('user_email'));
            $user->setCountry($request->request->get('user_country'));
            $user->setDob($date);
            $user->setGender($request->request->get('user_gender'));
            $user->setName($request->request->get('user_name'));
            $user->setPhone($request->request->get('user_phone'));
            $em->persist($user);
            $em->flush();
        }

        return $this->redirectToRoute('personal_area',['error'=>$error]);
    }
}
