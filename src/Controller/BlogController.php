<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\BlogType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request; /*pour importer la classe request de symfony */
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\ImagesBlog;
use phpDocumentor\Reflection\PseudoTypes\True_;
use Symfony\Component\HttpFoundation\JsonResponse;


    /**
    * @Route("/blog")
    */
class BlogController extends AbstractController
{
    /**
     * @Route("/", name="blog_index", methods={"GET"})
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Blog::class);
        $blogs = $repository->findBy([],['id'=>'DESC'],3);
        return $this->render('blog/index.html.twig', ['blogs' => $blogs]);
    }

    /**
     * @Route("/ajouter", name="blog_add" , methods={"GET","POST"})
     */
    public function add(ManagerRegistry $doctrine, Request $request): Response
    {
        $em = $doctrine->getManager();
        $blog = new blog();
        $form = $this->createForm(BlogType::class, $blog);/*pour hydrater l'objet le formulaire hydrate le projet*/
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //enregistrer en bdd
            //On récupère les images transmises
            $imagesBlog = $form->get('imagesBlog')->getData();
            //On boucle sur les images
            foreach ($imagesBlog as $imageBlog){
                //On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' .$imageBlog->guessExtension();
                //On copie le fichier dans le dossier uploads
                $imageBlog->move(
                    //la destination
                    $this->getParameter('ImagesBlog_directory'),
                    $fichier
                );
            //On stocke l'image(son nom) dans la bdd
                $img =new ImagesBlog();
                $img->setName($fichier);
                $blog->addImageBlog($img);
            }
            // ... persist the $product variable or any other work
            $em->persist($blog);
            $em->flush();
            return $this->redirectToRoute('blog_index');

        }
        return $this->render('blog/add.html.twig', ['form' => $form->createview()]);
    }

    /**
     * @Route("/{id}", name="blog_show", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function show($id, ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Blog::class);
        $blog = $repository->find($id);
        return $this->render('blog/show.html.twig', ['blog' => $blog]);

    }

    /**
     * @Route("/{id}/editer", name="blog_edit", methods={"GET","POST","DELETE"}, requirements={"id":"\d+"})
     */
    public function edit($id, ManagerRegistry $doctrine, Request $request): Response
    {
        $repository = $doctrine->getRepository(Blog::class);/* on récupère le repository, les données*/
        $em = $doctrine->getManager();/*on récupère l'entitymanager qui nous sert pour ajouter,editer,ou supprimer */
        $blog = $repository->find($id);
        /*dump ($project);*/
        $form = $this->createForm(BlogType::class, $blog);/*pour hydrater l'objet*/
        // $project->setTitle("Nouveau titre");
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           //enregistrer en bdd
            //On récupère les images transmises
            $imagesBlog = $form->get('imagesBlog')->getData();
            //On boucle sur les images
            foreach ($imagesBlog as $imageBlog){
                //On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' .$imageBlog->guessExtension();
                //On copie le fichier dans le dossier uploads
                $imageBlog->move(
                    //la destination
                    $this->getParameter('ImagesBlog_directory'),
                    $fichier
                );
            //On stocke l'image(son nom) dans la bdd
                $img =new ImagesBlog();
                $img->setName($fichier);
                $blog->addImageBlog($img);
            }
            // ... persiste la variable $blog
            $em->persist($blog);
            $em->flush();
            return $this->redirectToRoute('blog_show',['id'=>$id]);
        }

        return $this->render('blog/edit.html.twig', ['form' => $form->createView()]);
    }


    /**
     * @Route("/{id}/supprimer", name="blog_delete", requirements={"id":"\d+"})
     */
    public function delete($id, ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Blog::class);/* on récupère le repository, les données*/
        $em = $doctrine->getManager();/*on récupère l'entitymanager qui nous sert pour ajouter,editer,ou supprimer */
        $post = $repository->find($id);
        if (!$post) {
            return $this->redirectToRoute('blog_index');
        }
        $em->remove($post);
        $em->flush();
        return $this->redirectToRoute('blog_index');
    }


    /**
     * @Route("/supprimer/image/{id}", name="blog_delete_image", methods={"DELETE"}, requirements={"id":"\d+"})
     */
    public function deleteImage($id,ImagesBlog $ImagesBlog,Request $request, ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Blog::class);/* on récupère le repository, les données*/
        $em = $doctrine->getManager();/*on récupère l'entitymanager qui nous sert pour ajouter,editer,ou supprimer */
        $data = $repository->find($id);
        $data = json_decode($request->getContent(), true);//associatif pour avoir le nom des colonnes
        //on vérifie si le token est valide
        if ($this->isCsrfTokenValid('delete'.$ImagesBlog->getId(), $data['_token'])){
            //On récupère le nom de l'image
            $nom = $ImagesBlog->getName();
            //On supprime le fichier
            unlink($this->getParameter('ImagesBlog_directory').'/'.$nom);
            //on supprime l'entrée de la bdd
            $em = $doctrine->getManager();/*on récupère l'entitymanager qui nous sert pour ajouter,editer,ou supprimer */
            $em->remove($ImagesBlog);
            $em->flush();

            //On répond en JSON
            return new JsonResponse(['success'=>1]);
        }else{
            return new JsonResponse(['error'=>'Token invalid'],400);
        }
    }

}


