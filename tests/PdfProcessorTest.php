<?php

use Mamura\SimpleSignature\Services\PdfProcessor;

beforeEach(function () {
    $this->pdf          = new SplFileInfo(__DIR__ . '/Fixtures/dummy.pdf');
    $this->image        = new SplFileInfo(__DIR__ . '/Fixtures/signature.png');
    $this->processor    = new PdfProcessor();
});

test('it inserts image correctly on a specific page', function () {
    $output = $this->processor->insertImage(
        pdfFile: $this->pdf,
        imageFile: $this->image,
        xPx: 100,
        yPx: 100,
        canvasWidthPx: 892,
        canvasHeightPx: 1262,
        imageWidthPx: 100,
        imageHeightPx: 200,
        targetPage: 1
    );

    expect($output)
        ->toBeString()
        ->toContain('%PDF');
});

test('it throws exception when canvas size is zero', function () {
    $this->processor->insertImage(
        pdfFile: $this->pdf,
        imageFile: $this->image,
        xPx: 100,
        yPx: 100,
        canvasWidthPx: 0,
        canvasHeightPx: 0,
        imageWidthPx: 100,
        imageHeightPx: 40,
        targetPage: 1
    );
})->throws(InvalidArgumentException::class, 'Canvas width or height cannot be zero.');

test('it throws exception if pdf file is invalid', function () {
    $invalidPdf = new SplFileInfo(__DIR__ . '/Fixtures/missing.pdf');

    $this->processor->insertImage(
        pdfFile: $invalidPdf,
        imageFile: $this->image,
        xPx: 100,
        yPx: 100,
        canvasWidthPx: 892,
        canvasHeightPx: 1262,
        imageWidthPx: 100,
        imageHeightPx: 40,
        targetPage: 1
    );
})->throws(InvalidArgumentException::class, 'Arquivo PDF não encontrado');

test('it throws exception if image file is invalid', function () {
    $invalidImage = new SplFileInfo(__DIR__ . '/Fixtures/invalid.png');

    $this->processor->insertImage(
        pdfFile: $this->pdf,
        imageFile: $invalidImage,
        xPx: 100,
        yPx: 100,
        canvasWidthPx: 892,
        canvasHeightPx: 1262,
        imageWidthPx: 100,
        imageHeightPx: 40,
        targetPage: 1
    );
})->throws(InvalidArgumentException::class, 'Imagem não encontrada');
