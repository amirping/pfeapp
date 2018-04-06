<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Vote;
use AppBundle\Entity\Projet;
use AppBundle\Entity\Citoyen;
use AppBundle\Form\VoteType;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\JsonSerializable;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

  /**
     * @Route("api/commune/{commune}/projets/{projet}/vote")
     * 
     */  


class VoteController extends Controller
{
    /**
     * @Route("/")
     * @Method("GET")
     */
    public function listAction(Request $request,$projet,$commune)
    {

        $liste=null;

        $encoders = array( new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $em = $this->getDoctrine()->getManager();
        $p = $em->getRepository('AppBundle:Projet')->find( $projet);

        if ($p!=null) {
        $c=$p->getCommune();
        

        

        if ($c==$commune) {

        
        $em = $this->getDoctrine()->getManager();
        $votes = $em->getRepository('AppBundle:Vote')->findBy(['projet' => $projet]);

        foreach ($votes as $vote ) {
            $nom=$vote->getCitoyen()->getNom();
            $prenom=$vote->getCitoyen()->getPrenom();
            $liste[]=$prenom.' '.$nom;
            }
        
        }
    }
        
        $response = $serializer->serialize($liste, 'json');
         
        
        return new Response($response);
    
    }

 

  /**
     * @Route("/new", name="new_vote")
     * @Method("POST")
     */
    public function newAction(Request $request,$projet,$commune)
    {

        $encoders = array( new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);


        $citoyen_id=$request->request->get('citoyen');
        
    
        $em = $this->getDoctrine()->getManager();
        $p = $em->getRepository('AppBundle:Projet')->find( $projet);

        $emm = $this->getDoctrine()->getManager();
        $citoyen = $emm->getRepository('AppBundle:Citoyen')->find($citoyen_id);

        if ($p!=null) {
        $c=$p->getCommune();
        
        $rep=null;
        

        if ($c==$commune) {


        $vote = new Vote();
        $vote->setCitoyen($citoyen);
        $vote->setProjet($p);
        

        $form = $this->createForm(VoteType::class, $vote);
        $form->handleRequest($request);
 
        
        $em = $this->getDoctrine()->getManager();
        $votecheck = $em->getRepository('AppBundle:Vote')->findBy(['projet' => $projet , 'citoyen' => $citoyen_id]);

        if ($votecheck== null) {

            $em->persist($vote);
            $em->flush();

            $rep =array(
                'id' => $vote->getId());
        }
    }
}
        
       


           

        $response = $serializer->serialize($rep, 'json');
        return new Response($response);
        
    }




}
