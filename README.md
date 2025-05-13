# ✍️ Simple Signature – Assinaturas digitais em PDF para Laravel

O **Simple Signature** é um pacote Laravel que permite a **inserção visual, redimensionável e arrastável de imagens** (como assinaturas digitalizadas) em arquivos PDF diretamente de uma interface intuitiva com Vue 2.

---

## 🚀 Recursos

✅ Upload de PDF e imagem (assinatura)  
✅ Interface Vue 2 para visualização e posicionamento da imagem  
✅ Redimensionamento e arraste da imagem sobre o PDF  
✅ Compatível com múltiplas páginas  
✅ Integração completa com Laravel  
✅ Testado com PestPHP

---

## 📦 Instalação

### Via repositório local (monorepo)

Adicione em `composer.json` do seu projeto Laravel:

```json
"repositories": [
  {
    "type": "path",
    "url": "./packages/Mamura/SimpleSignature"
  }
]
```

E então execute:

```bash
composer require mamura/simple-signature:*
```

---

## 🔧 Publicação dos Assets

Execute os comandos abaixo para publicar os arquivos JS e views:

```bash
php artisan vendor:publish --tag=public
php artisan vendor:publish --tag=views
```

Isso irá disponibilizar os arquivos em:

- `public/vendor/simple-signature/js/signature.js`
- `resources/views/vendor/simple-signature/editor.blade.php`

---

## 🔍 Exemplo de Rota

No seu `web.php`, adicione:

```php
use Illuminate\Support\Facades\Route;
use Mamura\SimpleSignature\Http\Controllers\SimpleSignatureController;

Route::get('/simple-signature', [SimpleSignatureController::class, 'index']);
Route::post('/simple-signature', [SimpleSignatureController::class, 'store']);
```

---

## 🖼️ Interface

A interface permite:

- Upload de um PDF
- Upload da imagem da assinatura
- Navegação por páginas
- Posicionamento e redimensionamento da assinatura
- Geração do PDF com a imagem posicionada

---

## 🧪 Testes

O pacote possui testes automatizados com **Pest**. Para executar:

```bash
cd packages/Mamura/SimpleSignature
./vendor/bin/pest
```

Certifique-se de possuir os arquivos de fixtures:

- `tests/Fixtures/dummy.pdf`
- `tests/Fixtures/signature.png`

---

## 📝 Licença

MIT © [Mamura Mota](https://github.com/mamura)
