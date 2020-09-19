<?php
/**
 * Created by PhpStorm.
 * User: Rzouga
 * Date: 2/16/2020
 * Time: 14:29
 */

namespace ProduitBundle\Controller;


use ProduitBundle\Entity\Produit;
use StockBundle\Entity\Stock;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ProduitController extends Controller
{
    public function AjouterProduitAction( \Symfony\Component\HttpFoundation\Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $produit = new Produit();
        $form = $this->createForm('ProduitBundle\Form\ProduitType', $produit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $produit->setNomfile("");
            $produit->getUploadFile();
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('produit_Afficher');
        }
        return $this->render('ProduitBundle:Produit:AjouterProduit.html.twig', array(
            'form' => $form->createView(),

        ));
    }

    public function AfficheProduitAction(Request $request)
    {


        $m = $this->getDoctrine()->getManager();
        $Produit = $m->getRepository("ProduitBundle:Produit")->findAll();

        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $Produit,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',10)

        );

        return $this->render('ProduitBundle:Produit:AfficherProduit.html.twig', array(
            'prod' => $result
        ));
    }

    public function deleteProduitAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $Pro = $em->getRepository('ProduitBundle:Produit')->find($id);
        $em->remove($Pro);
        $em->flush();


        return $this->redirectToRoute('produit_Afficher');
    }

    public function ModifierProduitAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $produit = $em->getRepository('ProduitBundle:Produit')->find($id);
        $editForm = $this->createForm('ProduitBundle\Form\ProduitType', $produit);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em->persist($produit);
            $em->flush();

            return $this->redirectToRoute('produit_Afficher');
        }
        $em = $this->getDoctrine()->getManager();

        return $this->render('ProduitBundle:produit:ModifierProduit.html.twig', array(
            'produit' => $produit,
            'form' => $editForm->createView(),
        ));
    }


    public function AfficheProduitFrontAction(Request $request)
    {
        $m = $this->getDoctrine()->getManager();
        $Prod = $m->getRepository("ProduitBundle:Produit")->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($Prod, $request->query->getInt('page', 1), 2);
        return $this->render('ProduitBundle:Produit:AfficheProduitFront.html.twig', array(
            'prod' => $pagination
        ));

    }
    public function AfficheProduitMobileAction(Request $request)
    {
        $m = $this->getDoctrine()->getManager();
        $Produit = $m->getRepository(Produit::class)->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($Produit);
        return new JsonResponse($formatted);
    }
    public function AddProductMobAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $produit = new Produit();
        $produit->setNom($request->get('nom'));
        $produit->setType($request->get('type'));
        $produit->setQuantite($request->get('quantite'));
        $produit->setPrix($request->get('prix'));


        $em->persist($produit);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($produit);
        return new JsonResponse($formatted);
    }

    public function AddPromotionMobAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $produit = new Produit();
        $produit->setNom($request->get('nom'));
        $produit->setType($request->get('type'));
        $produit->setQuantite($request->get('quantite'));
        $produit->setPrix($request->get('prix'));
        $produit->SetNvprix($request->get('nvprix'));
        $produit->SetEnabled(1);


        $em->persist($produit);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($produit);
        return new JsonResponse($formatted);
    }
    public function CAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $stock = $em->getRepository(Produit::class)->find($id);
        $em->remove($stock);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($stock);
        return new JsonResponse($formatted);
    }
    public function ModiferStockMobileAction($id, $nom,$prix,$quantite)
    {
        $em = $this->getDoctrine()->getManager();
        $stock=$this->getDoctrine()->getRepository(Produit::class)->find($id);
        $stock->setNom($nom);
        $stock->setPrix($prix);
        $stock->setQuantite($quantite);
        // $stock->IdCategorie($idCategorie);
        $em->persist($stock);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($stock);
        return new JsonResponse($formatted);
    }

}