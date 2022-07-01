<?php

namespace App\Controller;




use App\Entity\Employe;
use App\Form\EmployeFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

/*
 * 
 */


class EmployeController extends AbstractController
{


    /**
     * Une fonction d'un controller s'appelera une action
     * Le nom de cette action (cette fonction) commencera toujours par un verbe
     * On privilégie l'anglais. A defaut, on nomme correctement ses variables en français
     * 
     * la route = 1e param: l'uri, 2e param: le nom de la route, 3e param: la méthode HTTP
     * 
     * @Route("/ajouter-un-employe.html", name="employe_create", methods={"GET|POST"})
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        #variabilisation d'un nouvel objet de type Employe
        $employe = new Employe();

        #On crée dans une variable un formulaire à partir de notre prototype EmployeFormType
        # pour faire foctionner le mécanisme d'auto hydratation d'obj de Symfo, vous devrez passer en 2e argument votre objet $employe
        # Mais également que tous les noms de vos champs dans le prototype de form (EmployeFormType) aient EXACTEMENT les mêmes noms que les propriétés de la Class à laquelle il est rattaché.
        $form = $this->createForm(EmployeFormType::class, $employe);

        # pour que synf récupère les données des imput du form vous devez handleRequest()
        $form->handleRequest($request);
        /////////////-------------------- 2e partie :POSDT----------//////////////////

if($form->isSubmitted() && $form->isValid()){

    # cette méthode pour récup les données des inputs est la 1ere méthode.
    # Nous utili la 2nde , gràce au mécanisme d'auto hydratation de symf
   // $form->get('salary')->getData();

    $entityManager->persist($employe);
    $entityManager->flush();

    return $this->redirectToRoute('default_home');

 }    
    ////////////////// ------------ 1ere Partie : GET ------------- /////////////////


  
      #on passe en param le formulaire a notre vue view twig
        return $this->render("form/employe.html.twig", [
            "form_employe" => $form->createView()# On doit createView() sur $form
        ]);
    }#end function create()

    /**
     * @Route("/modifier-un-employe-{id}", name="employe_update", methods={"GET|POST"})
     */
    public function update(Employe $employe, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form =$this->createForm(EmployeFormType::class, $employe)
        ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($employe);
            $entityManager->flush();

            return $this->redirectToRoute('default_home');
        }//end if

        return $this->render("form/employe.html.twig", [
            'employe' => $employe,
            'form_employe' => $form->createView()
        ]);
    }# end function update()

    /**
     * @Route("/supprimer-un-employe-{id}", name="employe_delete", methods={"GET"})
     */
    public function delete(Employe $employe, EntityManagerInterface $entityManager): RedirectResponse
    {
        $entityManager->remove($employe);
        $entityManager->flush();
        

        return $this->redirectToRoute("default_home");
    }#end function delete()
}#end class
