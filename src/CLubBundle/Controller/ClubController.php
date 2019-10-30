<?php

namespace CLubBundle\Controller;
use CLubBundle\Entity\Club;
use CLubBundle\Form\ClubType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ClubController extends Controller
{
    public function indexAction()
    {

        return new Response('Bonjour mes etudiants');
    }

    public function HomeAction(){
      return $this->render('@CLub/Club/HomePage.html.twig') ;
    }
    public function readAction($nom){
        return $this->render('@CLub/Club/Read.html.twig' ,array('n' => $nom) );
    }

    public function listAction(){
        $formations = array();
        $formations[0] = array('ref' => 'F123' , 'title' => 'formation symfony');
        $formations[1] = array('ref' => 'F23' , 'title' => 'formation symfony 4.3 ');
        return $this->render('@CLub/Club/Detail.html.twig',array('tab'=>$formations));

    }
    public function showAction($title , $ref){
        return $this->render('@CLub/Club/show.html.twig' , array('t'=> $title , 'r'=>$ref ));
    }

    public function findElementAction(){
        $clubs = $this->getDoctrine()
            ->getRepository(Club::class)
            ->findAll();
        return $this->render('@CLub/Club/find.html.twig',array('clubs'=>$clubs));

    }
    public function deleteElementAction($id){

        $manager = $this->getDoctrine()->getManager();
        $clubDeleted = $manager->getRepository(Club::class)
            ->find($id);
        $manager->remove($clubDeleted);
        $manager->flush();

        return $this->redirectToRoute('find');

    }

    public function updateElementAction(Request $request,$id){
//print_r($request);
        $manager = $this->getDoctrine()->getManager();
        $clubUpdate = $manager->getRepository(Club::class)->find($id);
        //print_r($request);
        $form =  $this->createForm(ClubType::class, $clubUpdate);

        $form =$form->handleRequest($request);
        if($form->isSubmitted()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($clubUpdate);
            $em->flush();
            return $this->redirectToRoute('find');
        }
        return $this->render('@CLub/Club/update.html.twig',array('U'=>$form->createView()));



    /*    $manager->remove($clubUpdate);
        $manager->flush();*/

        return $this->redirectToRoute('update/{$id}');

    }

    public function ReadFormAction(Request $request){
        $club=new Club();
        $form =  $this->createForm(ClubType::class, $club);
        $form =$form->handleRequest($request);
            if($form->isSubmitted()){
                $em=$this->getDoctrine()->getManager();
                $em->persist($club);
                $em->flush();
                return $this->redirectToRoute('find');
            }
        return $this->render('@CLub/Club/ajout.html.twig',array('f'=>$form->createView()));

    }

}
