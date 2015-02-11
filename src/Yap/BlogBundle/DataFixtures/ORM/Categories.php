<?php

namespace Yap\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Yap\BlogBundle\Entity\Category;

class Categories implements FixtureInterface
{
	// Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
	public function load(ObjectManager $manager)
	{
		// Liste des noms de catégorie à ajouter
		$noms = array('Symfony2', 'Doctrine2', 'Tutoriel', 'Évènement');
		foreach($noms as $i => $nom)
		{
			// On crée la catégorie
			$liste_categories[$i] = new Category();
			$liste_categories[$i]->setName($nom);
			// On la persiste
			$manager->persist($liste_categories[$i]);
		}
	// On déclenche l'enregistrement
	$manager->flush();
	}
}