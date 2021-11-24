<?php

namespace App\Controller;

use App\Entity\Cotizaciones;
use App\Entity\Clientes;
use App\Entity\Almacenes;
use App\Entity\Productos;
use App\Entity\CotizacionesProductos;
use App\Entity\ProductosAlmacenes;
use App\Form\CotizacionesType;
use App\Repository\CotizacionesRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * @Route("/docs")
 */
class DocsController extends AbstractController
{
    /**
     * @Route("/cot/{slug}", name="public_pdf_cotizaciones", methods={"GET","POST"})
     */
    public function pdf(CotizacionesRepository $repo, $slug): Response
    {
        $cotizacion = $repo->findOneBy(['slug' => $slug]);

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('cotizaciones/pdf.html.twig', [
            'cotizacion' => $cotizacion
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A5', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("Cotizacion -".$cotizacion->getId()." - JASA SUBLIMACION.pdf", [
            "Attachment" => false
        ]);

        exit();
        
        return $this->render('cotizaciones/pdf.html.twig', [
            'cotizacion' => $cotizacion
        ]);
    }
}
