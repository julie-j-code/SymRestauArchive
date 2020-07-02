<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
// use Doctrine\ORM\Query\Expr\Select;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
// use Doctrine\ORM\EntityManagerInterface;
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

     // Dans cette version j'ai rajouté ArticleRpository pour pouvoir boucler 
     // sur articles et comments respectivement dans div.card-header et div.collapse du div.accordion 
     // tests opérationnels !!!!
    
     public function commentslist(CommentRepository $repo, ArticleRepository $articles, Request $request){
        $comments = $repo->findBy([],['article' => 'desc']);
        $articles = $articles->findAll();
        return $this->render('admin/commentbyarticle.html.twig', [
            'controller_name' => 'AdminCommentsController',
            'comments' => $comments,
            'articles' => $articles
        ]);

    }

/*     public function index(EntityManagerInterface $manager)
    {
        $comments = $manager->createQuery('SELECT c FROM App\Entity\Comment c GROUP BY c.article')->getResult();
        return $this->render('admin/comments.html.twig', [
            'controller_name' => 'AdminCommentsController',
            'comments' => $comments

        ]);

    } */



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
