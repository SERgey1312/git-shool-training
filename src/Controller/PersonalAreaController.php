<?php

namespace App\Controller;

use App\Entity\Course;
use App\Repository\UserRepository;
use App\Service\TableOfCourseManager;
use App\Service\TableOfUsersManager;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
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
    public function index(Request $request,
                          EntityManagerInterface $em,
                          PaginatorInterface $paginator,
                          TableOfCourseManager $courseTable): Response
    {
        $user = $this->getUser();
        $userCourse = $user->getSubCourse();
        $result = [];
        foreach ($userCourse as $value){
            $course = $this->getDoctrine()
                ->getRepository(Course::class)
                ->find($value);
            array_push($result, $course);
        }

        return $this->render('personal_area/personalArea.html.twig', [
            'controller_name' => 'PersonalAreaController',
            'user' => $user,
            'course' => $result,
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
