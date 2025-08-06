from flask import Flask, request, jsonify
from flask_cors import CORS
import face_recognition
import pickle
import os
from werkzeug.utils import secure_filename

app = Flask(__name__)
CORS(app, resources={r"/*": {"origins": [
    "http://localhost:5050", 
    "http://127.0.0.1:5050", 
    "http://[::1]:5173",
    "https://absensi-stsd.test"
]}})

# Path encoding Laravel
BASE_DIR = os.path.abspath(os.path.dirname(__file__))
ENCODING_DIR = os.path.join(BASE_DIR, "..", "public", "encodings")
TMP_DIR = os.path.join(BASE_DIR, "tmp")

os.makedirs(ENCODING_DIR, exist_ok=True)
os.makedirs(TMP_DIR, exist_ok=True)

# Fungsi memuat semua encoding dari anggota
def load_known_faces():
    known_faces = []

    for file in os.listdir(ENCODING_DIR):
        if file.endswith(".pkl"):
            with open(os.path.join(ENCODING_DIR, file), "rb") as f:
                data = pickle.load(f)
                encodings = data["encodings"]
                known_faces.append({
                    "id": data["id"],
                    "nama": data["nama"],
                    "encodings": encodings
                })

    return known_faces

@app.route("/anggota/scan", methods=["POST"])
def scan():
    if 'image' not in request.files:
        return jsonify({'status': 'error', 'message': 'Tidak ada file gambar yang dikirim.'})

    juruarah_id = request.form.get('id_juruarah')
    if not juruarah_id:
        return jsonify({'status': 'error', 'message': 'ID juru arah tidak dikirim.'})

    file = request.files['image']
    file_path = os.path.join(TMP_DIR, secure_filename(file.filename))
    file.save(file_path)

    try:
        image = face_recognition.load_image_file(file_path)
        face_encodings = face_recognition.face_encodings(image)
    except Exception as e:
        return jsonify({'status': 'error', 'message': f'Gagal memproses gambar: {str(e)}'})

    if not face_encodings:
        return jsonify({'status': 'error', 'message': 'Wajah tidak ditemukan pada gambar.'})

    known_faces = load_known_faces()
    tolerance = 0.5  # Semakin rendah = semakin ketat

    for face_encoding in face_encodings:
        best_match = None
        best_distance = 1.0

        for person in known_faces:
            distances = face_recognition.face_distance(person["encodings"], face_encoding)
            min_distance = min(distances)

            print(f"[{person['id']}] {person['nama']} - Jarak: {min_distance:.4f}")

            if min_distance < best_distance and min_distance < tolerance:
                best_distance = min_distance
                best_match = person

        if best_match:
            return jsonify({
                'status': 'success',
                'id_anggota': best_match["id"],
                'nama': best_match["nama"],
                'juru_arah_id': juruarah_id,
                'message': f'Wajah dikenali sebagai {best_match["nama"]}',
                'distance': best_distance
            })

        # Jika tidak ada yang cocok, tampilkan kandidat terdekat
        all_candidates = []
        for person in known_faces:
            distances = face_recognition.face_distance(person["encodings"], face_encoding)
            min_distance = min(distances)
            all_candidates.append((person, min_distance))

        all_candidates.sort(key=lambda x: x[1])
        closest = all_candidates[0] if all_candidates else (None, None)

        if closest[0]:
            return jsonify({
                'status': 'error',
                'message': 'Wajah tidak dikenali.',
                'closest_match_id': closest[0]["id"],
                'closest_name': closest[0]["nama"],
                'closest_distance': float(closest[1]),
                'juruarah_id': juruarah_id
            })

    return jsonify({'status': 'error', 'message': 'Tidak ada wajah yang cocok.', 'juruarah_id': juruarah_id})

if __name__ == '__main__':
    app.run(host='127.0.0.1', port=5050, debug=True)
