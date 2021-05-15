<?php

namespace App\Controller;

use App\Entity\Course;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractController
{
    /**
     * @Route("/course", name="course")
     */
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->render('course/course.html.twig', [
            'controller_name' => 'CourseController',
            'user' => $user,
        ]);
    }

    /**
     * @Route ("/course/creating", name="course_creating", methods={"POST"})
     */
    public function userUpdate(UserRepository $userRepository, Request $request, EntityManagerInterface $em) :Response
    {
        $error = '';
        $course = new Course();
        $requestUserId = $request->request->get('user_id');
        $user = $userRepository->findOneBy(['id'=>$requestUserId]);
        if(isset($course)){
            $course->setName($request->request->get('course_name'));
            $course->setTheme($request->request->get('course_theme'));
            $course->setDescription($request->request->get('course_desc'));
            $course->setLessonCount($request->request->get('course_lessons'));
            $course->setOwner($user);
            $course->setPhotoLink($request->request->get('course_photo'));

            $em->persist($course);
            $em->flush();
        }

        return $this->redirectToRoute('personal_area',['error'=>$error]);
    }
}
