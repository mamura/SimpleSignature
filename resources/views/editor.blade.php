<div id="pdf-editor-app">
    <form @submit.prevent="submitForm" enctype="multipart/form-data">
        <div>
            <label>PDF:
                <input type="file" @change="handlePdfUpload" accept="application/pdf" required>
            </label>
        </div>
        <div>
            <label>Imagem:
                <input type="file" @change="handleImageUpload" accept="image/*" required>
            </label>
        </div>

        <div id="pdf-container" ref="pdfContainer">
            <div
                v-for="(page, index) in pages"
                :key="index"
                class="pdf-page-wrapper"
                :ref="'page-' + (index + 1)"
                style="position: relative; margin-bottom: 30px;"
            >
                <canvas
                    :ref="'canvas-' + (index + 1)"
                    class="pdf-page-canvas"
                    style="border: 1px solid #ccc; display: block;"
                ></canvas>

                <!-- Container para imagem + redimensionador -->
                <div
                v-if="imageData && pageWithImage === index + 1"
                :style="{
                    position: 'absolute',
                    left: imageX + 'px',
                    top: imageY + 'px',
                    width: imageWidth + 'px',
                    height: imageHeight + 'px',
                    zIndex: 10
                }"
                >
                <!-- Imagem ocupando 100% do container -->
                <img
                    :src="imageData"
                    :ref="'draggableImage-' + (index + 1)"
                    :style="{
                    width: '100%',
                    height: '100%',
                    cursor: 'move',
                    display: 'block'
                    }"
                    @mousedown="startDrag(index + 1, $event)"
                >

                <!-- Handle para redimensionar -->
                <div
                    :style="{
                    position: 'absolute',
                    width: '12px',
                    height: '12px',
                    background: 'black',
                    bottom: '0px',
                    right: '0px',
                    cursor: 'nwse-resize',
                    zIndex: 11
                    }"
                    @mousedown.stop="startResize(index + 1, $event)"
                ></div>
                </div>

            </div>
        </div>

        <input type="hidden" name="x" :value="imageX">
        <input type="hidden" name="y" :value="imageY">
        <input type="hidden" name="page" :value="pageWithImage">

        <button type="submit">Enviar</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.12.313/pdf.min.js"></script>
<script src="{{ asset('vendor/pdf-editor/js/pdf-editor.js') }}"></script>
<script>
    window.initPdfEditor({
        el: '#pdf-editor-app'
    });
</script>
