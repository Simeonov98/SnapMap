<?php

namespace App\Controller;

use App\Entity\File;
use App\Form\FileType;
use App\Repository\FileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class FileController extends AbstractController
{
    /**
    @Route("/file",name="fileIndex");
    */
    public function index(FileRepository $fileRepository): Response
    {
        return $this->render('upload.html.twig', [

        ]);
    }
    /**
     * @Route("/fileNew", name="fileNew");
     */
    public function new(Request $request): Response
    {
        $file = new File();
        $form = $this->createForm(FileType::class, $file);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $azz = $request->files->get('file');
            var_dump($azz); die;
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($file);
            $entityManager->flush();

            return $this->redirectToRoute('file_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('file/new.html.twig', [
            'file' => $file,
            'form' => $form,
        ]);
    }

//    /**
//     * @Route('/{id}', name: 'file_show', methods: ['GET'])
//    */
//     public function show(File $file): Response
//    {
//        return $this->render('file/show.html.twig', [
//            'file' => $file,
//        ]);
//    }
//    /**
//    @Route('/{id}/edit', name: 'file_edit', methods: ['GET', 'POST']);
//     */
//    public function edit(Request $request, File $file): Response
//    {
//        $form = $this->createForm(FileType::class, $file);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $this->getDoctrine()->getManager()->flush();
//
//            return $this->redirectToRoute('file_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->renderForm('file/edit.html.twig', [
//            'file' => $file,
//            'form' => $form,
//        ]);
//    }

//    /**
//     * @Route('/{id}', name: 'file_delete', methods: ['POST']);
//     * @param Request $request
//     * @param File $file
//     * @return Response
//     */
//    public function delete(Request $request, File $file): Response
//    {
//        if ($this->isCsrfTokenValid('delete'.$file->getId(), $request->request->get('_token'))) {
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->remove($file);
//            $entityManager->flush();
//        }
//
//        return $this->redirectToRoute('file_index', [], Response::HTTP_SEE_OTHER);
//    }
}
