<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use App\Repository\UserRepository;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin", name="admin_")
 */

class AdminController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * Liste les utilisateur inscrits
     * @Route("/users", name="users")
     * @Security("has_role('ROLE_ADMIN')")      
     */
    public function userslist(UserRepository $repo){
        $users = $repo->findAll();
        return $this->render('admin/users.html.twig', [
            'controller_name' => 'AdminController',
            'users' => $users
        ]);

    }

    /**
     * Modification des utilisateurs
     * @Route("/user/edit/{id}", name="user_edit")
     */

    public function useredit (User $user, Request $request, ObjectManager $manager){


        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush;

            $this->addFlash("message", "l'utilisateur a bien été modifié");
            return $this->redirectToRoute("admin_users");
        }

        return $this->render('admin/edituser.html.twig',[
           'userForm' => $form->createView()
        ]);
    }


}
