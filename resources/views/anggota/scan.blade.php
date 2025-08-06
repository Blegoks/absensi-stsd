@extends('anggota.layout')

@section('title', 'Scan Wajah')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-lg font-semibold mb-4">Scan Wajah</h2>
    <p>Kamera akan otomatis aktif. Klik tombol untuk melakukan absensi wajah.</p>

   <input type="hidden" id="juruarah_id" value="{{ Auth::user()->anggota->juru_arah_id ?? '' }}">


    <video id="video" width="640" height="480" autoplay class="border mb-4"></video>
    <canvas id="canvas" width="640" height="480" class="hidden"></canvas>

    <button onclick="takeSnapshot()" class="bg-green-500 text-white px-4 py-2 rounded">Scan Wajah</button>

    <div id="result" class="mt-4 text-sm"></div>
</div>


<script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const resultDiv = document.getElementById('result');

    async function startCamera() {
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: true });
                video.srcObject = stream;
            } catch (err) {
                resultDiv.innerText = 'Gagal mengakses kamera: ' + err.message;
            }
        } else {
            resultDiv.innerText = 'Browser tidak mendukung akses kamera. Gunakan HTTPS atau localhost.';
        }
    }

   function takeSnapshot() {
    const context = canvas.getContext('2d');
    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    canvas.toBlob(blob => {
        const formData = new FormData();
        formData.append('image', blob, 'wajah.jpg');

        const juruarahId = document.getElementById('juruarah_id').value;
        formData.append('id_juruarah', juruarahId);

        // Kirim ke Python API
        fetch("http://127.0.0.1:5050/anggota/scan", {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                // Kirim ke Laravel (jika perlu catat juga)
                fetch("{{ route('anggota.scan.submit') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        image: canvas.toDataURL('image/jpeg'),
                        juruarah_id: juruarahId
                    })
                })
                .then(res => res.json())
                .then(res => {
                    if (res.status === 'success') {
                        resultDiv.innerHTML = `<span class="text-green-600 font-semibold">${res.message}</span>`;
                    } else {
                        resultDiv.innerHTML = `<span class="text-red-600 font-semibold">${res.message}</span>`;
                    }
                });

            } else {
                resultDiv.innerHTML = `<span class="text-red-600 font-semibold">${data.message}</span>`;
            }
        })
        .catch(err => {
            resultDiv.innerText = 'Gagal mengirim data ke server Python.';
        });
    }, 'image/jpeg');
}


    window.addEventListener('DOMContentLoaded', startCamera);
</script>
@endsection
