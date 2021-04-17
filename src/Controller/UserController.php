<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\TableOfUsersManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/admin/users", name="users_table")
     */
    public function index(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator, TableOfUsersManager $usersTable): Response
    {
        $queryBuilder = $em->getRepository(User::class)
            ->createQueryBuilder('user')
        ;
        $usersTable->initializeParams($request->query->all());
        $queryBuilder = $usersTable->queryEmail($queryBuilder);
        $queryBuilder = $usersTable->queryName($queryBuilder);
        $queryBuilder = $usersTable->queryPhone($queryBuilder);
        $queryBuilder = $usersTable->queryID($queryBuilder);
        $query = $queryBuilder->getQuery()->getResult();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            15
        );

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route ("/admin/users/status", name="status", methods={"POST"})
     */
    public function switchStatus(UserRepository $userRepository, Request $request, TableOfUsersManager $usersTable) :Response
    {
        $response = [];
        $requestUserId = $request->request->get('user_id');
        $user = $userRepository->findOneBy(['id'=>$requestUserId]);
        if(isset($user)){
            $usersTable->switchStatus($user);
            $response['user_id'] = $requestUserId;
            $response['status'] = $user->isActive();
        }
        else{
            $response['error'] = 'No user with id = '.$request->request->get('id').' found';
        }
        return new JsonResponse($response);
    }

    /**
     * @Route ("/admin/users/promote", name="promote")
     */
    public function promoteById(UserRepository $userRepository, Request $request, TableOfUsersManager $usersTable) :Response
    {
        $error = '';
        $user = $userRepository->findOneBy($request->request->all());
        if(isset($user)){
            $usersTable->promoteUser($user);
        }
        else{
            $error = 'No user with id = '.$request->request->get('id').' found';
        }
        return $this->redirectToRoute('users_table',['error'=>$error]);
    }

    /**
     * @Route ("/admin/users/downgrade", name="downgrade")
     */
    public function downgradeById(UserRepository $userRepository, Request $request, TableOfUsersManager $usersTable) :Response
    {
        $error = '';
        $user = $userRepository->findOneBy($request->request->all());
        if(isset($user)){
            $usersTable->downgradeUser($user);
        }
        else{
            $error = 'No user with id = '.$request->request->get('id').' found';
        }
        return $this->redirectToRoute('users_table',['error'=>$error]);
    }

    /**
     * @Route ("/admin/users/delete", name="delete")
     */
    public function deleteById(UserRepository $userRepository, Request $request, TableOfUsersManager $usersTable, EntityManagerInterface $em) :Response
    {
        $error = '';
        $user = $userRepository->find($request->request->get('user_id'));
        if(isset($user))
        {
            $em->remove($user);
            $em->flush();
        }
        return $this->redirectToRoute('users_table',['error'=>$error]);
    }
}
