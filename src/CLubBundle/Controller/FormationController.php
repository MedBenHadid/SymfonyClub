<?php

namespace CLubBundle\Controller;
use CLubBundle\CLubBundle;
use CLubBundle\Entity\Club;
use CLubBundle\Entity\Formation;
use CLubBundle\Form\ClubType;
use CLubBundle\Form\FormationType;
use CLubBundle\Form\RechercheType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
class FormationController extends Controller
{
    public function indexAction()
    {
        //$club= $this->getDoctrine()->getRepository(Club::class)->find(2);

        $formation = $this->getDoctrine()
            ->getRepository(Formation::class)
            ->findAll();

         //$clubName=$formation->getClub()->getNom();
        //var_dump($formation);

       // var_dump($club->getNom());



        return $this->render('@CLub/Formation/find.html.twig',array('formations' => $formation) );
    }

    public function rechercheFormationAction(Request $request)
    {
        $invalid='';
        $formation = new Formation();
        $defaultData = ['message' => 'Invalid !'];
        $form = $this->createFormBuilder($defaultData)
            ->add('Chercher', TextType::class, ['required'=> false])
            ->add('send', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->getData('Chercher')['Chercher']!=''){

            $formation = $this->getDoctrine()
                ->getRepository(Formation::class)
                ->findBy(array('Titre'=>$form->getData('Chercher')['Chercher']));

            if (empty($formation)){
                $invalid="invalid serch";
                return $this->render('@CLub/Formation/recherche.html.twig',array('Form'=>$form->createView(), 'invalid'=>$invalid ,'formations'=>$formation));
            }

            //var_dump($form->getData('Chercher')['Chercher']);

        }else{
            $formation =$this->getDoctrine()->getRepository(Formation::class)->findAll();
//var_dump($formation);
        }
        return $this->render('@CLub/Formation/recherche.html.twig',array('Form'=>$form->createView(), 'invalid'=>$invalid,'formations'=>$formation));


    }

    public function AddFormationAction(Request $request){
    $formation=new Formation();
    $form = $this->createForm(FormationType::class,$formation);
    $form=$form->handleRequest($request);
    if($form->isSubmitted()){
        $em=$this->getDoctrine()->getManager();
        $em->persist($formation);
        $em->flush();
        return $this->redirectToRoute('showFormation');
    }
        return $this->render('@CLub/Formation/ajout.html.twig',array('Form'=>$form->createView()));

    }



    public function updateFormationAction(Request $request,$id){
//print_r($request);
        $manager = $this->getDoctrine()->getManager();
        $formationUpdate = $manager->getRepository(Formation::class)->find($id);
        //print_r($request);
        $form =  $this->createForm(FormationType::class, $formationUpdate);

        $form =$form->handleRequest($request);
        if($form->isSubmitted()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($formationUpdate);
            $em->flush();
            return $this->redirectToRoute('showFormation');
        }
        return $this->render('@CLub/Formation/update.html.twig',array('FormUp'=>$form->createView()));



        /*    $manager->remove($clubUpdate);
            $manager->flush();*/

        return $this->redirectToRoute('update/{$id}');

    }

    public function deleteFormationAction($id){

        $manager = $this->getDoctrine()->getManager();
        $FormationDeleted = $manager->getRepository(Formation::class)
            ->find($id);
        $manager->remove($FormationDeleted);
        $manager->flush();

        return $this->redirectToRoute('showFormation');

    }
}

