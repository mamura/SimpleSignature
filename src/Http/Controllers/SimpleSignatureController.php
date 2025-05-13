<?php
namespace Mamura\SimpleSignature\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Mamura\SimpleSignature\Services\PdfProcessor;

class SimpleSignatureController extends Controller
{
    public function index()
    {
        //dd(view()->getFinder()->getHints());

        return view('simplse-signature::editor');
    }

    public function store(Request $request)
    {
        $pdf            = $request->file('pdf');
        $image          = $request->file('image');
        $x              = $request->input('x');
        $y              = $request->input('y');
        $canvasWidth    = $request->input('canvas_width');
        $canvasHeight   = $request->input('canvas_height');
        $imageWidth     = (int) $request->input('image_width');
        $imageHeight    = (int) $request->input('image_height');
        $page           = (int) $request->input('page', 1);
        
        $output = (new PdfProcessor())->insertImage(
            $pdf,
            $image,
            $x,
            $y,
            $canvasWidth,
            $canvasHeight,
            $imageWidth,
            $imageHeight,
            $page
        );

        return response()->streamDownload(
            function () use ($output) {
                echo $output;
            },
            200,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="output.pdf"',
            ]
        );
    }

    public function show()
    {
        return view('simple-signature::show');
    }
}