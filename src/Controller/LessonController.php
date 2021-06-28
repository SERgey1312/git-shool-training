<?php

namespace App\Controller;

use App\Entity\Lesson;
use App\Form\LessonFormType;
use App\Repository\CourseRepository;
use App\Repository\LessonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Routing\Annotation\Route;

class LessonController extends AbstractController
{
    /**
     * @Route("/admin/lesson/create", name="lesson_form")
     */
    public function index(Request $request, SluggerInterface $slugger, EntityManagerInterface $em, CourseRepository $courseRepository): Response
    {
        $lesson = new Lesson();

        $form = $this->createForm(LessonFormType::class, $lesson, [
            'attr'=>['class'=>'form-lesson']
        ]);

        $form->handleRequest($request);
        $requestCourseId = $request->request->get('course_id');

        if ($form->isSubmitted() && $form->isValid()) {
            $video = $form->get('video_link')->getData();
            if($video){
                $originalFilename = pathinfo($video->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$video->guessExtension();

                try {
                    $video->move(
                        $this->getParameter('video_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

            }

            if(isset($lesson)){
                $requestCourseId = $request->request->get('course');
                $course = $courseRepository->findOneBy(['id' => $requestCourseId]);
                $lesson->setCourse($course);
                $lesson->setVideoLink($newFilename);
            }

            $em->persist($lesson);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('lesson/lesson.html.twig', [
            'controller_name' => 'LessonController',
            'lesson_form' => $form->createView(),
            'course' => $requestCourseId,
        ]);
    }


    /**
     * @Route("/lesson/open", name="lesson_open")
     */
    public function openLesson(Request $request,
                               SluggerInterface $slugger,
                               EntityManagerInterface $em,
                               CourseRepository $courseRepository,
                               LessonRepository $lessonRepository): Response
    {
        $requestLessonId = $request->request->get('lesson_id');
        $lesson = $lessonRepository->findOneBy(['id' => $requestLessonId]);

        return $this->render('lesson/lesson_open.html.twig', [
            'controller_name' => 'LessonController',
            'lesson' => $lesson,
        ]);
    }

}
