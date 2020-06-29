<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin", name="admin_")
 */

class AdminCommentsController extends AbstractController
{

/**
     * Liste les commentaires
     * @Route("/comments", name="comments")
     * @Security("has_role('ROLE_ADMIN')")      
     */
    public function commentslist(CommentRepository $repo, Request $request){
        $comments = $repo->findAll();
        return $this->render('admin/comments.html.twig', [
            'controller_name' => 'AdminCommentsController',
            'comments' => $comments

        ]);

    }



/**
     * Edition des commentaires
     * @Route("/comment/edit/{id}", name="comment_edit")
     * @Security("has_role('ROLE_ADMIN')")      
     */

     public function editcomment(Comment $comment, Request $request, ObjectManager $manager){
         $form = $this->createForm(CommentType::class, $comment);
         $form->handleRequest($request);
         
         if ($form->isSubmitted() && $form->isValid()) { 
             
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash("message", "le commentaire a bien été modifié");
            return $this->redirectToRoute("admin_comments");
         }

          return $this->render('admin/editcomment.html.twig',[
            'comment'=>$comment,
            'formComment'=>$form->createView()

        ]);

     }


}
