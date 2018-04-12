<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Commune;
use AppBundle\Form\CommuneType;

use AppBundle\Entity\Gouvernorat;
use AppBundle\Form\GouvernoratType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RegistrationCommuneController extends Controller
{
    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request)
    {   
        $nom=$request->request->get('nom');
        $pseudo=$request->request->get('pseudo');
        $gouvernorat=$request->request->get('gouvernorat');

       

        // Create a new blank commune and process the form
        $commune = new Commune();
        $commune->setNom($nom);
        $commune->setPseudo($pseudo);
        $commune->setGouvernorat($gouvernorat);

        
        $form = $this->createForm(CommuneType::class, $commune);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                    
            
            // Encode the new users password
            $encoder = $this->get('security.password_encoder');
            $password = $encoder->encodePassword($commune, $commune->getPlainPassword());
            $commune->setPassword($password);

            // Set their role
            $commune->setRole('ROLE_COMMUNE');
            
            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($commune);
            $em->flush();

            return $this->redirectToRoute('login');
        }

        return $this->render('admin/register.html.twig', [
            'form' => $form->createView(),

        ]);
    }
}