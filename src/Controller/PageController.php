<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Trabajador;
use App\Form\TrabajadorFormType;
use App\Form\TrabajadorFormTypeOLD;
use Symfony\Component\String\Slugger\SluggerInterface;

use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
class PageController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ManagerRegistry $doctrine): Response{

        $repositorio = $doctrine->getRepository(Trabajador::class);
        $trabajadores = $repositorio->findAll();

        return $this->render('page/index.html.twig', [
            'trabajadores' => $trabajadores
        ]);
    } 

    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('page/about.html.twig', []);
    }

    #[Route('/service', name: 'service')]
    public function service(): Response
    {
        return $this->render('page/service.html.twig', []);
    }

    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('page/contact.html.twig', []);
    }

    #[Route('/team', name: 'team')]
    public function team(ManagerRegistry $doctrine): Response{

        $repositorio = $doctrine->getRepository(Trabajador::class);
        $trabajadores = $repositorio->findAll();

      return $this->render('page/team.html.twig', [
            'trabajadores' => $trabajadores
        ]);
    }

    #[Route('/trabajador', name: 'app_trabajador')]

    public function trabajador(ManagerRegistry $doctrine, Request $request, SessionInterface $session
    , SluggerInterface $slugger){
        $user = $this->getUser();
        
       
        $trabajador = new trabajador();
        $formulario = $this->createForm(TrabajadorFormType::class, $trabajador);
        $formulario->handleRequest($request);
        


    if ($formulario->isSubmitted() && $formulario->isValid()) {
        $trabajador = $formulario->getData();
        $file = $formulario->get('foto')->getData();
        if ($file) {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
    
            // Move the file to the directory where images are stored
            try {
    
                $file->move(
                    $this->getParameter('images_directory'), $newFilename
                );
               
            } catch (FileException $e) {
               
            }
            $trabajador->setFoto($newFilename);
        }
           
        $entityManager = $doctrine->getManager();    
        $entityManager->persist($trabajador);
        $entityManager->flush();
    }
    
    return $this->render('join_us/trabajador.html.twig', array(
        'formulario' => $formulario->createView()));

}

    }


