<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    /**
     * Permet d'afficher la liste des plats par ordre de catégories
     * @Route("/articles", name="articles_index")
     */
    
    public function index(ArticleRepository $repo, CategoryRepository $repocat, Request $request){   
        $articles=$repo->findBy([],['category' => 'desc']);
        // je récupère les 2 pour la getsion des tabs côté vue...
        $categories=$repocat->findAll(); 

        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'articles' => $articles,
            'categories' => $categories
        ]);
    }


    /**
     * Permet de créer ou modifier un plat
     * @Route("/article/new", name="article_create")
     * @Route("/article/{id}/edit", name="article_edit")
     */

     public function form( Article $article = null, Request $request, ObjectManager $manager){
        if(!$article){
            $article=new Article();
        }

        $form=$this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            if(!$article->getId()){
                $article->setCreatedAt(new\DateTime());
            }
            
            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('article_show', ['id' => $article->getId()]);

        }

        return $this->render('article/create.html.twig',[
            'formArticle'=>$form->createView(),
            'editMode'=> $article->getId()!== null
        ]);

    }

    /**
     * Route de redirection après création ou modification du plat
     * @Route("/article/{id}", name="article_show")
     * @param Article $article
     * @param Request $request
     * @return Response 
     */

    public function show(Article $article, Request $request, ObjectManager $manager)
    {
        // $article=$repo->find($id);
        $comment=new Comment();
        
        $form=$this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            // pour récupérer le nom d'utilisateur actuellement authentifié
            // en faire l'auteur du commentaire
            $user = $this->getUser()->getUsername();
            $comment->setCreatedAt(new \DateTime())
                    ->setArticle($article)
                    ->setAuthor($user);
            $manager->persist($comment);
            $manager->flush();
            return $this->redirectToRoute('article_show',['id'=> $article->getId()]);
        }
        return $this->render('article/show.html.twig',[
            'article'=>$article,
            'commentForm'=>$form->createView()
        ]);
    }

    /**
     * Permet de supprimer un plat
     * @Route("/article/{id}/delete", name="article_delete")
     * @return Response
     */

     public function delete(Article $article, ObjectManager $manager){
         $manager->remove($article);
         $manager->flush();

         $this->addFlash(
             'success',
             "Le plat a bien été supprimé de la carte"
         );

         return $this->redirectToRoute('articles_index');

     }


}
