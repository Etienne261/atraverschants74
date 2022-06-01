<?php

namespace App\Controller;
use App\Repository\SongRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\SongType;
use App\Entity\Song;
use App\Entity\Resource;
use Symfony\Component\HttpFoundation\Request; /*pour importer la classe request de symfony */
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Images;
use phpDocumentor\Reflection\PseudoTypes\True_;
use Symfony\Component\HttpFoundation\JsonResponse;

    /**
    * @Route("/chansons")
    */
class SongController extends AbstractController
{
    /**
     * @Route("/", name="song_index", methods={"GET"})
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Song::class);
        $songs = $repository->findBy([],['name'=>'ASC']);
        return $this->render('song/index.html.twig', ['songs' => $songs]);
    }
    /**
     * @Route("/ajouter", name="song_add" , methods={"GET","POST"})
     */
    public function add(ManagerRegistry $doctrine, Request $request): Response
    {
        $em = $doctrine->getManager();
        $song = new song();
        $form = $this->createForm(SongType::class, $song);/*pour hydrater l'objet le formulaire hydrate le projet*/
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //enregistrer en bdd
            //On récupère les images transmises
            $images = $form->get('images')->getData();
            //On boucle sur les images
            foreach ($images as $image){
                //On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' .$image->guessExtension();
                //On copie le fichier dans le dossier uploads
                $image->move(
                    //la destination
                    $this->getParameter('images_directory'),
                    $fichier
                );
            //On stocke l'image(son nom) dans la bdd
                $img =new Images();
                $img->setName($fichier);
                $song->addImage($img);
            }
            // ... persist the $product variable or any other work
            $em->persist($song);
            $em->flush();
            return $this->redirectToRoute('song_index');

        }
        return $this->render('song/add.html.twig', ['form' => $form->createview()]);
    }

    /**
     * @Route("/{id}", name="song_show", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function show($id, ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Song::class);
        $song = $repository->find($id);
        return $this->render('song/show.html.twig', ['song' => $song]);

    }

    /**
     * @Route("/{id}/editer", name="song_edit", methods={"GET","POST","DELETE"}, requirements={"id":"\d+"})
     */
    public function edit($id, ManagerRegistry $doctrine, Request $request): Response
    {
        $repository = $doctrine->getRepository(Song::class);/* on récupère le repository, les données*/
        $em = $doctrine->getManager();/*on récupère l'entitymanager qui nous sert pour ajouter,editer,ou supprimer */
        $song = $repository->find($id);
        /*dump ($project);*/
        $form = $this->createForm(SongType::class, $song);/*pour hydrater l'objet*/
        // $project->setTitle("Nouveau titre");
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();
            //On boucle sur les images
            foreach ($images as $image){
                //On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' .$image->guessExtension();
                //On copie le fichier dans le dossier uploads
                $image->move(
                    //la destination
                    $this->getParameter('images_directory'),
                    $fichier
                );
            //On stocke l'image(son nom) dans la bdd
                $img =new Images();
                $img->setName($fichier);
                $song->addImage($img);
            }
            $em->flush();
            return $this->redirectToRoute('song_show',['id'=>$id]);
        }

        return $this->render('song/edit.html.twig', ['form' => $form->createView()]);
    }


    /**
     * @Route("/{id}/supprimer", name="song_delete", requirements={"id":"\d+"})
     */
    public function delete($id, ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Song::class);/* on récupère le repository, les données*/
        $em = $doctrine->getManager();/*on récupère l'entitymanager qui nous sert pour ajouter,editer,ou supprimer */
        $post = $repository->find($id);
        if (!$post) {
            return $this->redirectToRoute('song_index');
        }
        $em->remove($post);
        $em->flush();
        return $this->redirectToRoute('song_index');
    }

    /**
     * @Route("/supprimer/image/{id}", name="song_delete_image", methods={"DELETE"}, requirements={"id":"\d+"})
     */
    public function deleteImage($id,Images $image,Request $request, ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Song::class);/* on récupère le repository, les données*/
        $em = $doctrine->getManager();/*on récupère l'entitymanager qui nous sert pour ajouter,editer,ou supprimer */
        $data = $repository->find($id);

        $data = json_decode($request->getContent(), true);//associatif pour avoir le nom des colonnes
        //on vérifie si le token est valide
        if ($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])){
            //On récupère le nom de l'image
            $nom = $image->getName();
            //On supprime le fichier
            unlink($this->getParameter('images_directory').'/'.$nom);
            //on supprime l'entrée de la bdd
            $em = $doctrine->getManager();/*on récupère l'entitymanager qui nous sert pour ajouter,editer,ou supprimer */
            $em->remove($image);
            $em->flush();

            //On répond en JSON
            return new JsonResponse(['success'=>1]);
        }else{
            return new JsonResponse(['error'=>'Token invalid'],400);
        }
    }

}
