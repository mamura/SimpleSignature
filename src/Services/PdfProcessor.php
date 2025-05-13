<?php
namespace Mamura\SimpleSignature\Services;

use setasign\Fpdi\Tcpdf\Fpdi;

class PdfProcessor
{
    public function insertImage(
        $pdfFile,
        $imageFile,
        $xPx,
        $yPx,
        $canvasWidthPx,
        $canvasHeightPx,
        $imageWidthPx,
        $imageHeightPx,
        $targetPage = 1
    ): string
    {
        $xPx            = (float) $xPx;
        $yPx            = (float) $yPx;
        $canvasWidthPx  = (float) $canvasWidthPx;
        $canvasHeightPx = (float) $canvasHeightPx;
        $imageWidthPx   = (float) $imageWidthPx;
        $imageHeightPx  = (float) $imageHeightPx;
        $targetPage     = (int) $targetPage;

        if ($canvasWidthPx == 0 || $canvasHeightPx == 0) {
            throw new \InvalidArgumentException('Canvas width or height cannot be zero.');
        }

        $pdfPath   = is_string($pdfFile) ? $pdfFile : $pdfFile->getPathname();
        $imagePath = is_string($imageFile) ? $imageFile : $imageFile->getPathname();

        if (!file_exists($pdfPath)) {
            throw new \InvalidArgumentException("Arquivo PDF não encontrado: $pdfPath");
        }

        if (!file_exists($imagePath)) {
            throw new \InvalidArgumentException("Imagem não encontrada: $imagePath");
        }

        $pdf            = new Fpdi();
        $pdf->setSourceFile(is_string($pdfFile) ? $pdfFile : $pdfFile->getPathname());
        $pageCount      = $pdf->setSourceFile($pdfFile->getPathName());

        for ($page = 1; $page <= $pageCount; $page++) {
            $pageId = $pdf->importPage($page);
            $size   = $pdf->getTemplateSize($pageId);

            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($pageId, 0, 0, $size['width']);

            if ($page === $targetPage) {
                $x           = ($xPx / $canvasWidthPx) * $size['width'];
                $y           = ($yPx / $canvasHeightPx) * $size['height'];
                $imageWidth  = ($imageWidthPx / $canvasWidthPx) * $size['width'];
                $imageHeight = ($imageHeightPx / $canvasHeightPx) * $size['height'];

                $pdf->Image($imagePath, $x, $y, $imageWidth, $imageHeight);
            }
        }

        $tempPath = sys_get_temp_dir() . '/pdf_test_output_' . uniqid() . '.pdf';
        $pdf->Output($tempPath, 'F'); // Força gravação no disco

        $output = file_get_contents($tempPath);
        unlink($tempPath); // Limpa o arquivo

        return $output;
    }
}
