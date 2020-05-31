<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    /* alternative antérieure
    public function index()
    {
        $repo=$this->getDoctrine()->getRepository(Article::class);*/
    public function index(ArticleRepository $repo)
        {   
        $articles=$repo->findAll();
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/", name="home")
     */

    public function home()
    {
        return $this->render('blog/home.html.twig',[
            'title'=>"Bienvenue ici les amis",
            'age'=> 31
        ]);
    }

        /**
     * @Route("/blog/new", name="blog_create")
     * @Route("/blog/{id}/edit", name="blog_edit")
     */

     /**j'ai besoin de pouvoir analyser les requêtes du formulaire. j'ai donc besoin d'un paramètre à cette fonction que je vais appeler request et qui sera de la classe request. Si j'utilise cette classe je dois préciser qu'elle vient du composant http fundation */
     /**inititialement ici, c'était ma fonction create, je l'ai rappeler form pour permettre à la fois la creation et l'édition */
     /**null est requis dans l'hypothèse où on est dans la création de l'article et non dans l'édition */
     public function form( Article $article = null, Request $request, ObjectManager $manager){
        if(!$article){
            $article=new Article();
        }

        $form=$this->createForm(ArticleType::class, $article);
        
       // $form=$this->createFormBuilder($article)
                  /* ->add('title', TextType::class, [
                       'attr'=>[
                           'placeholder' => "Titre de l'article"
                       ]
                   ])*/

                   //->add('title')
                   //->add('content')
                   //->add('image')
                   /*->add( 'save', SubmitType::class, [
                    'label' => 'Enregistrer'
                ])*/
                //   ->getForm();
                   

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            if(!$article->getId()){
                $article->setCreatedAt(new\DateTime());
            }
            

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);

        }

        return $this->render('blog/create.html.twig',[
            'formArticle'=>$form->createView(),
            'editMode'=> $article->getId()!== null
        ]);

    }

    /**
     * @Route("/blog/{id}", name="blog_show")
     */

    /*alternative antérieure
    public function show($id)
    {
        $repo=$this->getDoctrine()->getRepository(Article::class);*/
    public function show(ArticleRepository $repo, $id, Request $request, ObjectManager $manager)
    {
        $article=$repo->find($id);
        $comment=new Comment();
        $form=$this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $comment->setCreatedAt(new \DateTime())
                    ->setArticle($article);
            $manager->persist($comment);
            $manager->flush();
            return $this->redirectToRoute('blog_show',['id'=> $article->getId()]);
        }
        return $this->render('blog/show.html.twig',[
            'article'=>$article,
            'commentForm'=>$form->createView()
        ]);
    }


}
