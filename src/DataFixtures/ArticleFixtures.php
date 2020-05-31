<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //j'appelle dans l'espace de nom faker la classe factory qui a une methode static create qui fournit une instance de la classe faker
        $faker=\Faker\Factory::create('fr_FR');

        //créer 3 catégories fakées
        for($i=1; $i<=3; $i++){
            //je crée une instance de la classe catégorie, et donc j'utilise le use plus haut pour indiquer sa provenance App\Entity\Caterogy
            $category=new Category();
            $category->setTitle($faker->sentence());
            $category->setDescription($faker->paragraph());
                     
                     $manager->persist($category);

                     // créer entre 4 et 6 articles

                     for( $j=1; $j<= mt_rand(4,6); $j++) {
                        $article=new Article();
                        $content='<p>'.join($faker->paragraphs(5), '</p><p>').'</p>';

                        $article->setTitle($faker->sentence())
                                ->setContent($content)
                                ->setImage($faker->imageUrl())
                                ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                                ->setCategory($category);
            
                                $manager->persist($article);

                        //créer des commentaires par article

                        for ($k=1; $k<= mt_rand(4,10); $k++){
                            $comment=new Comment();
                            $content='<p>'.join($faker->paragraphs(5), '</p><p>').'</p>';
                            $now=new\ DateTime();
                            $interval=$now->diff($article->getCreatedAt());
                            $days=$interval->days;
                            $min='-'.$days.'days';

                            $comment->setAuthor($faker->name())
                                    ->setContent($content)
                                    ->setCreatedAt($faker->dateTimeBetween($min))
                                    ->setArticle($article);
                            
                                    $manager->persist($comment);       

                        }
    }
}

$manager->flush();
}}