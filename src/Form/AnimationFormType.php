<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Animation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AnimationFormType extends AbstractType
{

// La premiere méthode buildForm() reçoit deux paramètres, dont le $builder qui va nous permettre de définir 
// les champs de notre formulaire. Par défaut toutes les attributs de l’entité Animation sont présents. 
// Et dites vous que chaque attribut que nous avons défini comme étant une colonne est considéré comme champs 
// dans le formulaire à moins que nous décidons de le retirer nous même.

// La seule explication qu'il y a ici je pense c'est sur le champ categories. Que fait-on là? 
// D'abord nous lui définissons comme étant un EntityType, au fait ce champ est une relation entre les entités 
// Animation et Category. Le champ categories est donc une EntityType parce qu'il va contenir un tableau d'entité Category, 
// ensuite dans le tableau des options, on définit la classe dont il s'agit, l'attribut de la classe Category a afficher, 
// si vous regardez bien l’entité Category, il n'a que deux attributs, id et label, nous choisissons donc l'attribut label 
// parce qu'il est plus parlant que d'afficher juste l'id, ensuite vient deux paramètres un peu complexes, qui vont définir 
// comment ce champ sera afficher, soit un champ select avec un attribut multiple, ou des checkbox (autant que le nombre de 
// catégories). Moi j'ai choisi le select avec l'attribut multiple. D'abord, un article peut disposer de plusieurs categories, 
// donc le multiple à true, ensuite je veux le champ select au lieu des checkbox (ça risque de devenir trop si on a beaucoup 
// de catégories), je mets donc expanded à false, vous pouvez retrouver la documentation complète sur le site de Symfony.

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', FileType::class)
            ->add('title', TextType::class)
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'label',
                'multiple' => true,
                'expanded' => false
            ])
            ->add('description', TextareaType::class)
            ->add('isPublished', CheckboxType::class);
    }

// La deuxième méthode va au fait nous permettre d'explicitement définir la classe à la qu'elle est rattachée ce 
// formulaire. Ce n'est pas obligatoire, mais c'est bien de le faire.

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Animation::class,
        ]);
    }
}