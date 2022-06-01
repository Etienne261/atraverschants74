<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ResourceType;
use App\Entity\Resource;
use App\Entity\Song;
use Symfony\Component\HttpFoundation\Request; /*pour importer la classe request de symfony */
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Files;

/**
 * @Route("/ressource")
 */
class RessourceController extends AbstractController
{
    /**
     * @Route("/", name="ressource_index", methods={"GET"})
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Resource::class);
        $resources = $repository->findAll();
        return $this->render('ressource/index.html.twig', ['resources' => $resources]);
    }

    /**
     * @Route("/ajouter", name="ressource_add" , methods={"GET","POST"})
     */
    public function add(ManagerRegistry $doctrine, Request $request): Response
    {
        $em = $doctrine->getManager();
        $resource = new Resource();
        $form = $this->createForm(ResourceType::class, $resource);/*pour hydrater l'objet, le formulaire hydrate le projet*/
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //enregistrer en bdd
            //On récupère les images transmises
            $files = $form->get('files')->getData();
            //On boucle sur les images
            foreach ($files as $file) {
                //On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $file->guessExtension();
                //On copie le fichier dans le dossier uploads
                $file->move(
                    //la destination
                    $this->getParameter('files_directory'),
                    $fichier
                );
                //On stocke l'image(son nom) dans la bdd
                $img = new Files();
                $img->setName($fichier);
                $resource->addFile($img);
            }
            // ... persist the $product variable or any other work
            $em->persist($resource);
            $em->flush();
            return $this->redirectToRoute('ressource_index');
        }
        return $this->render('ressource/add.html.twig', ['formresource' => $form->createview()]);
    }
    /**
     * @Route("/{id}", name="ressource_show", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function show($id, ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Resource::class);
        $resource = $repository->find($id);
        $songName = $resource->getSong()->getName();
        return $this->render('ressource/show.html.twig', ['resource' => $resource]);
    }
    

    /**
     * @Route("/{id}/editer", name="ressource_edit", methods={"GET","POST"}, requirements={"id":"\d+"})
     */
    public function edit($id, ManagerRegistry $doctrine, Request $request): Response
    {
        $repository = $doctrine->getRepository(Resource::class);/* on récupère le repository, les données*/
        $em = $doctrine->getManager();/*on récupère l'entitymanager qui nous sert pour ajouter,editer,ou supprimer */
        $resource = $repository->find($id);
        $form = $this->createForm(ResourceType::class, $resource);/*pour hydrater l'objet*/
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //enregistrer en bdd
            //On récupère les images transmises
            $files = $form->get('files')->getData();
            //On boucle sur les images
            foreach ($files as $file) {
                //On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $file->guessExtension();
                //On copie le fichier dans le dossier uploads
                $file->move(
                    //la destination
                    $this->getParameter('files_directory'),
                    $fichier
                );
                //On stocke l'image(son nom) dans la bdd
                $img = new Files();
                $img->setName($fichier);
                $resource->addFile($img);
            }
            // ... persist the $product variable or any other work
            $em->persist($resource);
            $em->flush();
            return $this->redirectToRoute('ressource_show', ['id' => $id]);
        }

        return $this->render('ressource/edit.html.twig', ['formresource' => $form->createView()]);
    }


    /**
     * @Route("/{id}/supprimer", name="ressource_delete", requirements={"id":"\d+"})
     */
    public function delete($id, ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Resource::class);/* on récupère le repository, les données*/
        $em = $doctrine->getManager();/*on récupère l'entitymanager qui nous sert pour ajouter,editer,ou supprimer */
        $post = $repository->find($id);
        if (!$post) {
            return $this->redirectToRoute('ressource_index');
        }
        $em->remove($post);
        $em->flush();
        return $this->redirectToRoute('ressource_index');
    }

    /**
     * @Route("/enregistrer", name="ressource_record")
     */
    public function record(): Response
    {
        return $this->render('song/record.html.twig');
    }


}
