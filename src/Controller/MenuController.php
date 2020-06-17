<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Form\MenuType;
use App\Entity\Article;
use App\Repository\MenuRepository;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MenuController extends AbstractController
{
    /**
     * Pour l'affichage des menus
     * @Route("/menus", name="menus")
     */
    public function index(MenuRepository $repo, ArticleRepository $repoArticle, Request $request)
    {
        $menus = $repo->findAll();
        $articles = $repoArticle->findAll();
        return $this->render('menu/index.html.twig', [
            'controller_name' => 'MenuController',
            'menus' => $menus,
            'articles'=> $articles
        ]);
    }


    /**
     * @Route("/menu/new", name="menu_create")
     * @Route("/menu/{id}/edit", name="menu_edit")
     */

    public function form( Menu $menu = null, Request $request, ObjectManager $manager){
        if(!$menu){
            $menu=new Menu();
        }
        
        // à ajouter pour tester la classe CollectionType de MenuType 
        // dans l'hypothèse où je souhaite saisir et enregistrer un article en même temps qu'un nouveau menu !!!
        // $article=new Article();
        // $menu->addArticle($article);

        $form=$this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            if(!$menu->getId()){
                $menu->setCreatedAt(new\DateTime());
            }
        
            $manager->persist($menu);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le menu a bien été modifié"
            );

            return $this->redirectToRoute('menu_show', ['id' => $menu->getId()]);

        }

        return $this->render('menu/create.html.twig',[
            'formMenu'=>$form->createView(),
            'editMode'=> $menu->getId()!== null
        ]);

    }

    /**
     * Redirection après édition/création
     * @Route("/menu/{id}", name="menu_show")
     */

    public function show(MenuRepository $repo, $id, Request $request, ObjectManager $manager)
    {
        $menu=$repo->find($id);
        return $this->render('menu/show.html.twig',[
            'menu'=>$menu
        ]);
    }


    /**
     * Permet de supprimer un menu
     * @Route("/menu/{id}/delete", name="menu_delete")
     * @return Response
     */

    public function delete(Menu $menu, ObjectManager $manager){
        $manager->remove($menu);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le menu a bien été supprimé"
        );

        return $this->redirectToRoute("menus");

    }


}
