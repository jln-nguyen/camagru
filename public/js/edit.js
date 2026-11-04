const video = document.getElementById('webcam');
const uploadedPreview = document.getElementById('uploaded-preview');
const stage = document.getElementById('stage');
const canvas = document.getElementById('canvas');
const captureBtn = document.getElementById('capture-btn');
const fileInput = document.getElementById('file-upload');
const uploadBtn = document.getElementById('upload-btn');      // le <label>
const cameraBtn = document.getElementById('take-photo');
const overlayThumbs = document.querySelectorAll('.overlay-thumb');

let selectedOverlays = [];
let uploadedImage = null;   // null = mode webcam
let cameraAvailable = false;

canvas.width = 640;
canvas.height = 480;

/* ---------- Modes ---------- */
function setMode(mode) {
    const useUpload = mode === 'upload';

    video.style.display = useUpload ? 'none' : 'block';
    uploadedPreview.style.display = useUpload ? 'block' : 'none';

    // En mode upload : on propose de revenir à la caméra (si elle existe)
    cameraBtn.style.display = useUpload && cameraAvailable ? 'inline-block' : 'none';
    // En mode caméra : on propose l'upload ; en mode upload on garde "changer de photo"
    uploadBtn.textContent = useUpload ? 'Change photo' : 'Upload a photo';

    if (!useUpload) {
        uploadedImage = null;
        fileInput.value = '';
    }
    updateCaptureState();
}

function updateCaptureState() {
    const hasSource = uploadedImage !== null || cameraAvailable;
    captureBtn.disabled = !(hasSource && selectedOverlays.length > 0);
}

/* ---------- Webcam ---------- */
navigator.mediaDevices.getUserMedia({ video: true })
    .then((stream) => {
        video.srcObject = stream;
        cameraAvailable = true;
        setMode('camera');
    })
    .catch((error) => {
        console.error('Error accessing webcam: ', error);
        cameraAvailable = false;
        // Pas de webcam : on laisse uniquement l'upload
        video.style.display = 'none';
        updateCaptureState();
    });

/* ---------- Upload ---------- */
fileInput.addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = (e) => {
        const img = new Image();
        img.onload = () => {
            uploadedImage = img;
            uploadedPreview.src = e.target.result;
            setMode('upload');
        };
        img.src = e.target.result;
    };
    reader.readAsDataURL(file);
});

cameraBtn.addEventListener('click', () => setMode('camera'));

/* ---------- Overlays ---------- */
overlayThumbs.forEach((thumb) => {
    thumb.addEventListener('click', () => {
        const path = thumb.dataset.overlay;

        if (selectedOverlays.includes(path)) {
            selectedOverlays = selectedOverlays.filter((p) => p !== path);
            thumb.classList.remove('selected');
        } else {
            selectedOverlays.push(path);
            thumb.classList.add('selected');
        }
        updateOverlayPreview();
        updateCaptureState();
    });
});

function updateOverlayPreview() {
    document.querySelectorAll('.overlay-preview-img').forEach((i) => i.remove());
    selectedOverlays.forEach((path) => {
        const img = document.createElement('img');
        img.src = path;
        img.className = 'overlay-preview-img';
        stage.appendChild(img);
    });
}

/* ---------- Capture ---------- */
// Reproduit "object-fit: cover" dans le canvas pour que le résultat
// corresponde exactement à ce que voit l'utilisateur
function drawCover(ctx, source, srcW, srcH) {
    const scale = Math.max(canvas.width / srcW, canvas.height / srcH);
    const w = srcW * scale;
    const h = srcH * scale;
    ctx.drawImage(source, (canvas.width - w) / 2, (canvas.height - h) / 2, w, h);
}

captureBtn.addEventListener('click', () => {
    const ctx = canvas.getContext('2d');
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    if (uploadedImage) {
        drawCover(ctx, uploadedImage, uploadedImage.naturalWidth, uploadedImage.naturalHeight);
    } else {
        drawCover(ctx, video, video.videoWidth, video.videoHeight);
    }

    uploadImage(canvas.toDataURL('image/png'));
});

function uploadImage(dataUrl) {
    const formData = new FormData();
    formData.append('photo', dataUrl);
    selectedOverlays.forEach((p) => formData.append('overlays[]', p));

    fetch('/edit-images/create', { method: 'POST', body: formData })
        .then((response) => response.text())
        .then(() => window.location.reload())
        .catch((error) => console.error('Error:', error));
}

// // Variable qui mémorise l'overlay actuellement sélectionné
// let selectedOverlays = [];

// // --- Webcam ---
// navigator.mediaDevices.getUserMedia({ video: true })
//     .then((stream) => {
//         const video = document.getElementById("webcam");
//         video.srcObject = stream;
//     })
//     .catch((error) => {
//         console.error("Error accessing webcam: ", error);
//     });

// const overlayThumbs = document.querySelectorAll('.overlay-thumb');
// const captureBtn = document.getElementById('capture-btn');

// overlayThumbs.forEach((thumb) => {
//     thumb.addEventListener('click', () => {
//         const overlayPath = thumb.dataset.overlay;

//         if (selectedOverlays.includes(overlayPath)) {
//             selectedOverlays = selectedOverlays.filter((path) => path !== overlayPath);
//             thumb.classList.remove('selected');
//         } else {
//             selectedOverlays.push(overlayPath);
//             thumb.classList.add('selected');
//         }

//         captureBtn.disabled = selectedOverlays.length === 0;

//         updateOverlayPreview();
//     });
// });

// function updateOverlayPreview() {
//     // const container = document.querySelector('.webcam-container');
//     document.getElementById('stage');

//     document.querySelectorAll('.overlay-preview-img').forEach((img) => img.remove());

//     selectedOverlays.forEach((overlayPath) => {
//         // const img = document.   ('img');
//         const img = document.createElement('img');
//         img.src = overlayPath;
//         img.className = 'overlay-preview-img';
//         // img.style.position = 'absolute';
//         // img.style.top = '0';
//         // img.style.left = '0';
//         // img.style.width = '320px';
//         // img.style.height = '240px';
//         // container.appendChild(img);
//         document.getElementById('stage').appendChild(img);
//     });
// }

// captureBtn.addEventListener('click', () => {
//     const canvas = document.getElementById('canvas');
//     const ctx = canvas.getContext('2d');

//     if (uploadedImage) {
//         console.log('Upload');
//         ctx.drawImage(uploadedImage, 0, 0, canvas.width, canvas.height);
//     }
//     else {
//         console.log('webcam');
//         const video = document.getElementById('webcam');
//         ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

//     }
//     const dataUrl = canvas.toDataURL('image/png');

//     uploadImage(dataUrl);
// });

// function uploadImage(dataUrl) {
//     const formData = new FormData();
//     formData.append('photo', dataUrl);
//     selectedOverlays.forEach((overlayPath) => {
//         formData.append('overlays[]', overlayPath);
//     });

//     fetch('/edit-images/create', {
//         method: 'POST',
//         body: formData
//     })
//     .then((response) => response.text())
//     .then((result) => {
//         console.log('Success:', result);
//         window.location.reload();
//     })
//     .catch((error) => {
//         console.error('Error:', error);
//     });
// }

// const cameraBtn = document.getElementById('take-photo');
// cameraBtn.addEventListener('click', () =>  {
//     const uploadedPreview = document.getElementById('uploaded-preview');
//     const uploadBtn = document.getElementById('upload-btn');
//     const video = document.getElementById('webcam');

//     uploadBtn.style.display = 'inline';
//     cameraBtn.style.display = 'none';
//     video.style.display = 'block';
//     uploadedPreview.style.display = ' none';
//     uploadedImage = null;
    
// });

// const uploadFile = document.getElementById('file-upload');

// // uploadFile.addEventListener('change', previewFile);
// let uploadedImage = null;

// function previewFile() {
//     const file = uploadFile.files[0];
//     if (!file) {
//         return;
//     }
//     const reader = new FileReader();
//     reader.onload = (event) => {
//         uploadedImage = new Image();
//         uploadedImage.src = event.target.result;
//         uploadedImage.onload = () => {
//             const video = document.getElementById('webcam');
//             const uploadedPreview = document.getElementById('uploaded-preview');
//             const uploadBtn = document.getElementById('upload-btn');

//             video.style.display = 'none';
//             uploadedPreview.src = event.target.result;
//             uploadedPreview.style.display = 'block';

//             captureBtn.disabled = selectedOverlays.length === 0;
//             cameraBtn.style = 'display: active';
//             uploadBtn.style = 'display: none';
//         };
//     }
//     reader.readAsDataURL(file);
// }