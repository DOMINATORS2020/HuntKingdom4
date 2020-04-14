<?php


namespace EventsBundle\Controller;


use EventsBundle\Entity\Events;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EventsController extends Controller
{

    public function AjouterEventAction( \Symfony\Component\HttpFoundation\Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $event = new Events();
        $form = $this->createForm('EventsBundle\Form\EventsType', $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $event->setNomfile("3.jpg");
            $event->getUploadFile();
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute('event_Afficher');
        }
        return $this->render('EventsBundle:Events:AjouterEvent.html.twig', array(
            'form' => $form->createView(),

        ));
    }


    public function AfficheEventsFrontAction()
    {


        $m = $this->getDoctrine()->getManager();
        $event = $m->getRepository("EventsBundle:Events")->findAll();


        return $this->render('EventsBundle:Events:AfficherEventsFront.html.twig', array(
            'event' => $event
        ));
    }


    public function AfficheEventAction()
    {


        $m = $this->getDoctrine()->getManager();
        $event = $m->getRepository("EventsBundle:Events")->findAll();


        return $this->render('EventsBundle:Events:AfficherEvent.html.twig', array(
            'event' => $event
        ));
    }



    public function deleteEventAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $Pro = $em->getRepository('EventsBundle:Events')->find($id);
        $em->remove($Pro);
        $em->flush();


        return $this->redirectToRoute('event_Afficher');
    }



    public function ModifierEventAction(\Symfony\Component\HttpFoundation\Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository('EventsBundle:Events')->find($id);
        $editForm = $this->createForm('EventsBundle\Form\EventsType', $event);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('event_Afficher');
        }
        $em = $this->getDoctrine()->getManager();

        return $this->render('EventsBundle:Events:ModifierEvent.html.twig', array(
            'Events' => $event,
            'form' => $editForm->createView(),
        ));
    }

}