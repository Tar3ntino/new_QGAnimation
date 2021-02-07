<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
/* Test de connexion User : 
Une fixture est un morceau de code qui permet de fixer un environnement logiciel pour exécuter des tests logiciels. Cet environnement constant est toujours le même à chaque exécution des tests. Il permet de répéter les tests indéfiniment et d'avoir toujours les mêmes résultats.

La fonction load() prend en paramètre les variables de classe : 
    Objectmanager : afin de pouvoir appeler les fonctions d'enregistrement en BDD

    Dans un idéal, elle devrait prendre en paramètre également : 
    UserPassportInterface : afin de pouvoir appeler les fonctions encodage MDP

Cependant, la fonction load définit () est déjà présente dans la classe Fixture et ne prend pas la classe
UserPassportInterface en paramètre, nous devons donc l'appeler à l'extérieur de load dans la classe UserFixture via une méthode __construct : 

On affecte dans l'encoder de l'instance ($this->encoder) la variable à encoder en paramètre.
$this = l'instance /la variable qui appelle la fonction à l'extérieur.
*/

private $encoder;

public function __construct(UserPasswordEncoderInterface $encoder)
{
    $this->encoder = $encoder;
}

    public function load(ObjectManager $manager)
    {
        // Création d'un utilisateur admin pour test dans BDD : 
        $user = new User();
        $user->setUsername('admin');
        $user->setPassword(
            $this->encoder->encodePassword($user, 'monmotdepasse')
        ); 
        $user->setEmail('monemail@monemail.fr');
        $manager->persist($user); // sauvegarde de la donnée avant envoi BDD
        $manager->flush();

        // Création d'un utilisateur membre pour test dans BDD : 
        $user_m = new User();
        $user_m->setUsername('membre');
        $user_m->setPassword(
            $this->encoder->encodePassword($user_m, 'mdp')
        );
        $user_m->setEmail('membre@membre.fr');
        $manager->persist($user_m);
        $manager->flush();

    }
}
