window.initPdfEditor = function (options = {}) {
  const target = document.querySelector(options.el || '#pdf-editor-app');

  new Vue({
    el: target,
    data: {
      pdfFile: null,
      imageFile: null,
      imageData: null,
      imageX: 50,
      imageY: 50,
      dragging: false,
      offsetX: 0,
      offsetY: 0,
      pages: 0,
      pageWithImage: 1,
      imageWidth: 100,
      imageHeight: 40,
      resizing: false,
    },
    methods: {
      handlePdfUpload(e) {
        this.pdfFile = e.target.files[0];
        this.renderPdf();
      },

      handleImageUpload(e) {
        this.imageFile = e.target.files[0];
        const reader = new FileReader();
        reader.onload = (e) => {
          this.imageData = e.target.result;
        };
        reader.readAsDataURL(this.imageFile);
      },

      renderPdf() {
        const fileReader = new FileReader();
        fileReader.onload = (e) => {
          const loadingTask = pdfjsLib.getDocument({ data: e.target.result });
          loadingTask.promise.then(pdf => {
            this.pages = pdf.numPages;

            for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
              pdf.getPage(pageNum).then(page => {
                const canvasRef = this.$refs['canvas-' + pageNum];
                const canvas = Array.isArray(canvasRef) ? canvasRef[0] : canvasRef;

                const context = canvas.getContext('2d');
                const viewport = page.getViewport({ scale: 1.5 });

                canvas.width = viewport.width;
                canvas.height = viewport.height;

                page.render({ canvasContext: context, viewport });
              });
            }
          });
        };
        fileReader.readAsArrayBuffer(this.pdfFile);
      },

      startDrag(pageNumber, e) {
        this.dragging = true;

        const imgRef = this.$refs['draggableImage-' + pageNumber];
        const img = Array.isArray(imgRef) ? imgRef[0] : imgRef;

        this.offsetX = e.offsetX;
        this.offsetY = e.offsetY;

        document.addEventListener('mousemove', this.onDrag);
        document.addEventListener('mouseup', this.stopDrag);
      },

      onDrag(e) {
        if (!this.dragging) return;
      
        // Detectar página atual sob o cursor enquanto arrasta
        for (let i = 1; i <= this.pages; i++) {
          const pageRef = this.$refs['page-' + i];
          const page = Array.isArray(pageRef) ? pageRef[0] : pageRef;
          const rect = page.getBoundingClientRect();
      
          if (
            e.clientX >= rect.left &&
            e.clientX <= rect.right &&
            e.clientY >= rect.top &&
            e.clientY <= rect.bottom
          ) {
            this.pageWithImage = i;
            break;
          }
        }
      
        // Agora usa a página correta para calcular posição
        const wrapperRef = this.$refs['page-' + this.pageWithImage];
        const wrapper = Array.isArray(wrapperRef) ? wrapperRef[0] : wrapperRef;
        const rect = wrapper.getBoundingClientRect();
      
        this.imageX = e.clientX - rect.left - this.offsetX;
        this.imageY = e.clientY - rect.top - this.offsetY;
      },
      

      stopDrag() {
        this.dragging = false;

        console.log(this.pageWithImage);

        document.removeEventListener('mousemove', this.onDrag);
        document.removeEventListener('mouseup', this.stopDrag);
      },

      startResize(pageNumber, e) {
        this.resizing = true;
        this.pageWithImage = pageNumber;
        this.resizeStartX = e.clientX;
        this.resizeStartY = e.clientY;
      
        const img = this.$refs['draggableImage-' + pageNumber];
        const image = Array.isArray(img) ? img[0] : img;
        this.startWidth = image.offsetWidth;
        this.startHeight = image.offsetHeight;
      
        document.addEventListener('mousemove', this.onResize);
        document.addEventListener('mouseup', this.stopResize);
      },
      
      onResize(e) {
        if (!this.resizing) return;
      
        const dx = e.clientX - this.resizeStartX;
        const dy = e.clientY - this.resizeStartY;
      
        this.imageWidth = Math.max(20, this.startWidth + dx);
        this.imageHeight = Math.max(10, this.startHeight + dy);
      },
      
      stopResize() {
        this.resizing = false;
        document.removeEventListener('mousemove', this.onResize);
        document.removeEventListener('mouseup', this.stopResize);
      },
      

      submitForm() {

        const canvasRef = this.$refs['canvas-' + this.pageWithImage];
        const canvas = Array.isArray(canvasRef) ? canvasRef[0] : canvasRef;

        const imgRef = this.$refs['draggableImage-' + this.pageWithImage];
        const img = Array.isArray(imgRef) ? imgRef[0] : imgRef;

        const formData = new FormData();
        formData.append('pdf', this.pdfFile);
        formData.append('image', this.imageFile);
        formData.append('page', this.pageWithImage);
        formData.append('x', this.imageX);
        formData.append('y', this.imageY);
        formData.append('canvas_width', canvas?.width || 0);
        formData.append('canvas_height', canvas?.height || 0);
        //formData.append('image_width', img?.offsetWidth || 0);
        //formData.append('image_height', img?.offsetHeight || 0);
        formData.append('image_width', this.imageWidth);
        formData.append('image_height', this.imageHeight);

        console.log('Enviando para página:', this.pageWithImage);

        fetch('/simple-signature', {
          method: 'POST',
          body: formData
        })
        .then(res => res.blob())
        .then(blob => {
          const url = window.URL.createObjectURL(blob);
          const link = document.createElement('a');
          link.href = url;
          link.download = 'output.pdf';
          link.click();
        });
      }
    }
  });
};
