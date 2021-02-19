<?php

namespace App\Controller;

use App\Entity\Contactos;
use App\Form\Contactos1Type;
use App\Repository\ContactosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/contactos")
 */
class ContactosController extends AbstractController
{
        /**
     * @Route("/", name="contactos_index")
     */
 public function index(): Response
    {
        return $this->render('contactos/index.html.twig');
    }
    /**
     * @Route("/list/{type}", name="list")
     */
    public function list(Request $request, $type, PaginatorInterface $paginator) : Response {
        
        if ($type == 'todos') {
            $dql   = "SELECT * FROM contactos";
            $contacto = $this->getDoctrine()
            ->getManager()
            ->getRepository(Contactos::class)
            ->findAll();
            # code...
        }
        else{
            $contacto = $this->getDoctrine()
            ->getManager()
            ->getRepository(Contactos::class)
            //->createQueryBuilder('p')

            ->findBy(['tipo' => $type]);
            //->getQuery();
        }
        $pagination = $paginator->paginate(
            // Doctrine Query, not results
            $contacto,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );
        
        return $this->render('contactos/list.html.twig',[
            'list' => $contacto,
            'type' =>$type,
            'pagination' => $pagination
        ]);
    }
    /**
     * @Route("/new", name="contactos_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $contacto = new Contactos();
        $form = $this->createForm(Contactos1Type::class, $contacto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contacto);
            $entityManager->flush();

            return $this->redirectToRoute('contactos_index');
        }

        return $this->render('contactos/new.html.twig', [
            'contacto' => $contacto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contactos_show", methods={"GET"})
     */
    public function show(Contactos $contacto): Response
    {
        return $this->render('contactos/show.html.twig', [
            'contacto' => $contacto,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="contactos_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Contactos $contacto): Response
    {
        $form = $this->createForm(Contactos1Type::class, $contacto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('contactos_index');
        }

        return $this->render('contactos/edit.html.twig', [
            'contacto' => $contacto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contactos_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Contactos $contacto): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contacto->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($contacto);
            $entityManager->flush();
        }

        return $this->redirectToRoute('contactos_index');
    }
}
