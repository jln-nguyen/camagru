// Variable qui mémorise l'overlay actuellement sélectionné
let selectedOverlays = [];

// --- Webcam ---
navigator.mediaDevices.getUserMedia({ video: true })
    .then((stream) => {
        const video = document.getElementById("webcam");
        video.srcObject = stream;
    })
    .catch((error) => {
        console.error("Error accessing webcam: ", error);
    });

const overlayThumbs = document.querySelectorAll('.overlay-thumb');
const captureBtn = document.getElementById('capture-btn');

overlayThumbs.forEach((thumb) => {
    thumb.addEventListener('click', () => {
        const overlayPath = thumb.dataset.overlay;

        if (selectedOverlays.includes(overlayPath)) {
            selectedOverlays = selectedOverlays.filter((path) => path !== overlayPath);
            thumb.classList.remove('selected');
        } else {
            selectedOverlays.push(overlayPath);
            thumb.classList.add('selected');
        }

        captureBtn.disabled = selectedOverlays.length === 0;

        updateOverlayPreview();
    });
});

function updateOverlayPreview() {
    const container = document.querySelector('.webcam-container');

    document.querySelectorAll('.overlay-preview-img').forEach((img) => img.remove());

    selectedOverlays.forEach((overlayPath) => {
        const img = document.createElement('img');
        img.src = overlayPath;
        img.className = 'overlay-preview-img';
        img.style.position = 'absolute';
        img.style.top = '0';
        img.style.left = '0';
        img.style.width = '320px';
        img.style.height = '240px';
        container.appendChild(img);
    });
}

captureBtn.addEventListener('click', () => {
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');

    if (uploadedImage) {
        console.log('Upload');
        ctx.drawImage(uploadedImage, 0, 0, canvas.width, canvas.height);
    }
    else {
        console.log('webcam');
        const video = document.getElementById('webcam');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    }
    const dataUrl = canvas.toDataURL('image/png');

    uploadImage(dataUrl);
});

function uploadImage(dataUrl) {
    const formData = new FormData();
    formData.append('photo', dataUrl);
    selectedOverlays.forEach((overlayPath) => {
        formData.append('overlays[]', overlayPath);
    });

    fetch('/edit-images/create', {
        method: 'POST',
        body: formData
    })
    .then((response) => response.text())
    .then((result) => {
        console.log('Success:', result);
        window.location.reload();
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}

const cameraBtn = document.getElementById('take-photo');
cameraBtn.addEventListener('click', () =>  {
    const uploadedPreview = document.getElementById('uploaded-preview');
    const uploadBtn = document.getElementById('upload-btn');
    const video = document.getElementById('webcam');

    uploadBtn.style.display = 'inline';
    cameraBtn.style.display = 'none';
    video.style.display = 'block';
    uploadedPreview.style.display = ' none';
    uploadedImage = null;
    
});

const uploadFile = document.getElementById('upload-file');

uploadFile.addEventListener('change', previewFile);
let uploadedImage = null;

function previewFile() {
    const file = uploadFile.files[0];
    if (!file) {
        return;
    }
    const reader = new FileReader();
    reader.onload = (event) => {
        uploadedImage = new Image();
        uploadedImage.src = event.target.result;
        uploadedImage.onload = () => {
            const video = document.getElementById('webcam');
            const uploadedPreview = document.getElementById('uploaded-preview');
            const uploadBtn = document.getElementById('upload-btn');

            video.style.display = 'none';
            uploadedPreview.src = event.target.result;
            uploadedPreview.style.display = 'block';

            captureBtn.disabled = selectedOverlays.length === 0;
            cameraBtn.style = 'display: active';
            uploadBtn.style = 'display: none';
        };
    }
    reader.readAsDataURL(file);
}