<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\User;
use App\Repository\CourseRepository;
use App\Repository\UserRepository;
use App\Security\LoginAuthenticator;
use App\Service\Mailer;
use App\Service\TableOfCourseManager;
use App\Service\TableOfUsersManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class CourseController extends AbstractController
{
    /**
     * @Route("/admin/course/create", name="course")
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
     * @Route ("/admin/course/creating", name="course_creating", methods={"POST"})
     */
    public function courseCreate(UserRepository $userRepository, Request $request, EntityManagerInterface $em) :Response
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

    /**
     * @Route("/course", name="course_table")
     */
    public function courseTable(Request $request,
                                EntityManagerInterface $em,
                                PaginatorInterface $paginator,
                                TableOfCourseManager $courseTable): Response
    {
        $user = $this->getUser();

        $queryBuilder = $em->getRepository(Course::class)
            ->createQueryBuilder('course')
        ;

        $courseTable->initializeParams($request->query->all());
        $queryBuilder = $courseTable->queryTheme($queryBuilder);
        $queryBuilder = $courseTable->queryName($queryBuilder);
        $queryBuilder = $courseTable->queryOwnerID($queryBuilder);
        $queryBuilder = $courseTable->queryID($queryBuilder);

        $query = $queryBuilder->getQuery()->getResult();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            7
        );

        return $this->render('course/course_table.html.twig', [
            'controller_name' => 'CourseController',
            'user' => $user,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/course/sub",  name="course_sub", methods={"POST"})
     */
    public function courseSub(Request $request,
                              EntityManagerInterface $em,
                              Mailer $mailer,
                              CourseRepository $courseRepository) :Response
    {
        $error ='';
        $user = $this->getUser();
        $course = $courseRepository->findOneBy(['id'=>$request->request->get('btn_open')]);

        $mailer->sendConfirmationMessageSub($user, $course);
        $requestCourseId = $request->request->get('btn_open');
        if(isset($user)){
            $user->addSubCourse($requestCourseId);
            $em->persist($user);
            $em->flush();
        }


        return $this->redirectToRoute('email_notification_course');
    }

    /**
     * @Route("/course/sub/confirm/{id}",  name="email_confirm_sub")
     */
    public function confirmEmailSub() : Response
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
            'name' => '111',
            'user' => $user,
            'course' => $result,
        ]);
    }


    /**
     * @Route("/admin/course/update", name="course_update_page")
     */
    public function updatePageOpen(Request $request, CourseRepository $courseRepository): Response
    {
        $user = $this->getUser();
        $requestCourseId = $request->request->get('btn_change');
        $course = $courseRepository->findOneBy(['id'=>$requestCourseId]);

        return $this->render('course/course_update.html.twig', [
            'controller_name' => 'CourseController',
            'user' => $user,
            'course' => $course,
        ]);
    }

    /**
     * @Route("/course/open", name="course_open_page")
     */
    public function openCourse(Request $request, CourseRepository $courseRepository): Response
    {
        $user = $this->getUser();
        $requestCourseId = $request->request->get('btn_open');
        $course = $courseRepository->findOneBy(['id'=>$requestCourseId]);

        return $this->render('course/course_open.html.twig', [
            'controller_name' => 'CourseController',
            'user' => $user,
            'course' => $course,
        ]);
    }

    /**
     * @Route ("/admin/course/updating", name="course_updating", methods={"POST"})
     */
    public function courseUpdate(CourseRepository $courseRepository,
                                 UserRepository $userRepository,
                                 Request $request,
                                 EntityManagerInterface $em) :Response
    {
        $error = '';
        $requestCourseId = $request->request->get('course_id');
        $course = $courseRepository->findOneBy(['id'=>$requestCourseId]);
        if(isset($course)){
            $course->setName($request->request->get('course_name'));
            $course->setTheme($request->request->get('course_theme'));
            $course->setDescription($request->request->get('course_desc'));
            $course->setLessonCount($request->request->get('course_lessons'));
            $course->setPhotoLink($request->request->get('course_photo'));

            $em->persist($course);
            $em->flush();
        }

        return $this->redirectToRoute('course_table',['error'=>$error]);
    }

    /**
     * @Route("/course/notification", name="email_notification_course")
     */
    public function notificationEmail( ) : Response
    {
        return $this->render('course/notification.html.twig', [
            'name' => 'email_notification',
        ]);
    }
}
