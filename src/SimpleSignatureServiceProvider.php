<?php
namespace Mamura\SimpleSignature;

use Illuminate\Support\ServiceProvider;

class SimpleSignatureServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'simple-signature');
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/simple-signature'),
        ]);
        $this->publishes([
            __DIR__.'/../resources/js/PdfEditor.js' => public_path('vendor/simple-signature/js/simple-signature.js'),
        ], 'simple-signature-assets');
    }
}