<?php
namespace Mamura\SimpleSignature;

use Illuminate\Support\ServiceProvider;

class PdfEditorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'pdf-editor');
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/pdf-editor'),
        ]);
        $this->publishes([
            __DIR__.'/../resources/js/PdfEditor.js' => public_path('vendor/pdf-editor/js/pdf-editor.js'),
        ], 'pdf-editor-assets');
    }
}