<?php

namespace App\DataFixtures;

use App\Entity\Menu;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //j'appelle dans l'espace de nom faker la classe factory qui a une methode static create qui fournit une instance de la classe faker
        $faker=\Faker\Factory::create('fr_FR');

        // Pour générer des catégories simples pour les items (articles) du menu
    
       for($h=1; $h<6; $h++){
            $category=new Category();
              $category->setName($faker->word(6));

            $manager->persist($category);          


        //créer des menus fakés ... trop nombreux. PB Mais c'est du Fake !
        $menu = [];
        for($i=0; $i<=1; $i++){

            $menu[$i]=new Menu();
            $menu[$i]->setTitle($faker->sentence())
                ->setImage($faker->imageUrl())
                ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                ->setEndDate($faker->DateTime('2021-05-29 22:30:48', 'Europe/Paris'))
                ->setPrice($faker->randomFloat($nbMaxDecimals = NULL, $min = 30, $max = 90));
                     
                     $manager->persist($menu[$i]);

        }

                     // créer entre 4 et 6 articles

                     $article = [];
                     for( $j=0; $j<= mt_rand(4,6); $j++) {

                        $article[$j]=new Article();

                        $content='<p>'.join($faker->paragraphs(5), '</p><p>').'</p>';

                        $article[$j]->setTitle($faker->sentence())
                                ->setContent($content)
                                ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                                ->setPrice($faker->randomFloat($nbMaxDecimals = NULL, $min = 8, $max = 25))
                                ->setAllergen($faker->sentence())
                                ->setCategory($category);
                                //->addMenu($menu);

                        // on récupère un nombre aléatoire de menu dans un tableau
                        $randomMenu = (array) array_rand($menu, rand(1, count($menu)));
                        // puis on y rattache des articles
                        foreach ($randomMenu as $key => $value) {
                            $article[$j]->addMenu($menu[$key]);
                        }
          
                                $manager->persist($article[$j]);


                        //créer des commentaires par article

                        for ($k=1; $k<= mt_rand(4,10); $k++){
                            $comment=new Comment();
                            $content='<p>'.join($faker->paragraphs(5), '</p><p>').'</p>';
                            $now=new\ DateTime();
                            $interval=$now->diff($article[$j]->getCreatedAt());
                            $days=$interval->days;
                            $min='-'.$days.'days';

                            $comment->setAuthor($faker->name())
                                    ->setContent($content)
                                    ->setCreatedAt($faker->dateTimeBetween($min))
                                    ->setArticle($article[$j]);
                            
                                    $manager->persist($comment);       

                        }
    }

}


$manager->flush();
}}