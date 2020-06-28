<?php

namespace App\DataFixtures;

use App\Entity\Menu;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ArticleFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder){
    //je stocke l'encoder dans ma propriété $this->encoder dont je pourrai me servir dans toutes les fonctions de ma fixture
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {

        //j'appelle dans l'espace de nom faker la classe factory qui a une methode static create qui fournit une instance de la classe faker
        $faker=\Faker\Factory::create('fr_FR');

        //je faisais antérieurement en sorte que l'entité Roles contienne un role admin indispensable pour tester l'administration
/*      $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole); */


        $admin = new User();
        $admin->setUsername('julie');
        $admin->setEmail('jj@gmail.com');
        $admin->setPassword($this->encoder->encodePassword($admin, 'password'));
        $admin->setRoles(array('ROLE_ADMIN'));
        $manager->persist($admin);


/*      $adminUser = new User();
        $adminUser->setUsername('jj')
                  ->setEmail('jj@gmail.com')
                  ->setPassword($this->encoder->encodePassword($adminUser, 'password'))
                  ->addUserRole($adminRole);

        $manager->persist($adminUser); */

        //ici, nous gérons les utilisateurs
        $users = [];

        for($i = 1; $i <= 10; $i++){
            $user = new User();

            $hash = $this->encoder->encodePassword($user, 'password');
            
            $user->setUsername($faker->username)
                 ->setEmail($faker->email)
                 ->setPassword($hash);
                 
        $manager ->persist($user);
        $users[] = $user;
        

        }





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

                        $content='<p>'.join($faker->paragraphs(2), '</p><p>').'</p>';

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
                            // on récupère aléatoirement l'id d'un utilisateur pour en faire l'auteur d'un commentaire !
                            $randomuser = array_rand($users);
                            $randomUserName = $users[$randomuser];

                            $comment->setAuthor($randomUserName->getUsername())
                                    ->setContent($content)
                                    ->setCreatedAt($faker->dateTimeBetween($min))
                                    ->setArticle($article[$j]);
                            
                                    $manager->persist($comment);       

                        }
    }

}


$manager->flush();
}}