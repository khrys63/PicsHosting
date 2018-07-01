// variables
var dropArea = document.getElementById('dropArea');
var canvas = document.querySelector('canvas');
var context = canvas.getContext('2d');
var count = document.getElementById('count');
var result = document.getElementById('result');
var list = [];
var totalSize = 0;
var totalProgress = 0;

// initialisation
(function(){

    // gestionnaires
    function initHandlers() {
        dropArea.addEventListener('drop', handleDrop, false);
        dropArea.addEventListener('dragover', handleDragOver, false);
		dropArea.addEventListener('dragleave', handleDragLeave, false);
    }

    // affichage de la progression
    function drawProgress(progress) {
        context.clearRect(0, 0, canvas.width, canvas.height); // effacer le canvas

        context.beginPath();
        context.strokeStyle = '#4B9500';
        context.fillStyle = '#4B9500';
        context.fillRect(0, 0, progress * 500, 20);
        context.closePath();

        // affichage de la progression (mode texte)
        context.font = '16px Verdana';
        context.fillStyle = '#000';
        context.fillText('Progression : ' + Math.floor(progress*100) + ' %', 50, 15);
    }

    // survol lors du deplacement
    function handleDragOver(event) {
        event.stopPropagation();
        event.preventDefault();

        dropArea.className = 'hover';
    }
    // survol lors du deplacement
    function handleDragLeave(event) {
        event.stopPropagation();
        event.preventDefault();

        dropArea.className = '';
    }
    // glisser deposer
    function handleDrop(event) {
        event.stopPropagation();
        event.preventDefault();

        processFiles(event.dataTransfer.files);
    }

    // traitement du lot de fichiers
    function processFiles(filelist) {
        if (!filelist || !filelist.length || list.length) return;

        totalSize = 0;
        totalProgress = 0;
        result.textContent = '';

        for (var i = 0; i < filelist.length && i < 5; i++) {
            list.push(filelist[i]);
            totalSize += filelist[i].size;
        }
        uploadNext();
    }

    // a la fin, traiter le fichier suivant
    function handleComplete(size) {
        totalProgress += size;
        drawProgress(totalProgress / totalSize);
        uploadNext();
    }

    // mise a jour de la progression
    function handleProgress(event) {
        var progress = totalProgress + event.loaded;
        drawProgress(progress / totalSize);
    }

    // transfert du fichier
    function uploadFile(file, status) {

        // creation de l'objet XMLHttpRequest
        var xhr = new XMLHttpRequest();
        xhr.open('POST', "upload.php"); //destinationUrl.value);
        xhr.onload = function() {
            result.innerHTML += this.responseText;
            handleComplete(file.size);
        };
        xhr.onerror = function() {
            result.textContent = this.responseText;
            handleComplete(file.size);
        };
        xhr.upload.onprogress = function(event) {
            handleProgress(event);
        }
        xhr.upload.onloadstart = function(event) {
        }

        // creation de l'objet FormData
        var formData = new FormData();
        formData.append('myfile', file);
        xhr.send(formData);
    }

    // transfert du fichier suivant
    function uploadNext() {
        if (list.length) {
            count.textContent = list.length - 1;
            dropArea.className = 'uploading';

            var nextFile = list.shift();
            if (nextFile.size >= 2097152) { // 2Mb
                result.innerHTML += '<div class="f">Fichier trop gros (d&eacute;passement de la taille maximale)</div>';
                handleComplete(nextFile.size);
            } else {
                uploadFile(nextFile, status);
            }
        } else {
            dropArea.className = '';
        }
    }
    initHandlers();
})();