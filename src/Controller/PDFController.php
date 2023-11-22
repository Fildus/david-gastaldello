<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class PDFController extends AbstractController
{
    #[Route('/pdf/{slug}', name: 'app_pdf')]
    public function pdf(string $slug): Response
    {
        $pdfPath = $this->getParameter('kernel.project_dir') . '/public/cert/' . 'certificat_david-gastaldello_' . $slug . '.pdf';

        if (!file_exists($pdfPath)) {
            return $this->redirectToRoute('app_list');
        }

        return (new BinaryFileResponse($pdfPath))->setContentDisposition(
            disposition: ResponseHeaderBag::DISPOSITION_INLINE,
            filename: $slug
        );
    }

    #[Route('/', name: 'app_list')]
    public function list(): Response
    {
        $finder = new Finder();
        $finder->files()->in($this->getParameter('kernel.project_dir') . '/public/cert');

        $fileNames = [];
        foreach ($finder as $file) {
            $file = $file->getRelativePathname();
            $fileNames[$file] = str_replace(['certificat_david-gastaldello_', '.pdf'], ['', ''], $file);
        }

        return $this->render('list/index.html.twig', [
            'files' => $fileNames
        ]);
    }
}
