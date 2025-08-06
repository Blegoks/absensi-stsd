import cv2
import face_recognition
import os
import pickle
import time
import sys

# Input data anggota
id_anggota = sys.argv[1]
nama = sys.argv[2]

# Buat direktori encoding jika belum ada
ENCODING_DIR = os.path.join(os.path.dirname(__file__), "../public/encodings")
os.makedirs(ENCODING_DIR, exist_ok=True)

# Inisialisasi webcam
video_capture = cv2.VideoCapture(0)
if not video_capture.isOpened():
    print("Kamera tidak bisa dibuka.")
    exit()

encodings = []
total_samples = 100
samples_per_pose = total_samples // 4

poses = [
    ("Menghadap DEPAN", 25),
    ("Hadap KANAN", 25),
    ("Hadap KIRI", 25),
    ("Putar kepala ke KANAN dan KIRI perlahan", 25),
]


print("Persiapan pendaftaran wajah...")
print("Harap ikuti instruksi berikut. Setiap pose akan direkam selama 3 detik.")

for pose_instruction, sample_target in poses:
    print(f"Sekarang: {pose_instruction}")
    for i in range(3, 0, -1):
        print(f"   Mulai dalam {i} detik...")
        time.sleep(1)

    captured = 0
    start_time = time.time()
    while captured < sample_target:
        ret, frame = video_capture.read()
        if not ret:
            continue

        rgb_frame = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
        face_locations = face_recognition.face_locations(rgb_frame, model='hog')


        if face_locations:
            face_enc = face_recognition.face_encodings(rgb_frame, face_locations)[0]
            encodings.append(face_enc)
            captured += 1
            print(f"   [{captured}/{sample_target}] sampel diambil ({pose_instruction})")

        cv2.putText(frame, f"{pose_instruction} ({captured}/{sample_target})", (10, 30),
                    cv2.FONT_HERSHEY_SIMPLEX, 0.8, (255, 255, 0), 2)
        cv2.imshow('Pendaftaran Wajah', frame)

        if cv2.waitKey(1) & 0xFF == ord('q'):
            print(" Pendaftaran dibatalkan oleh pengguna.")
            video_capture.release()
            cv2.destroyAllWindows()
            exit()

print("\nSemua pose selesai direkam. Menyimpan data...")

video_capture.release()
cv2.destroyAllWindows()

# Simpan encoding ke file .pkl
data = {
    "id": int(id_anggota),  # pastikan tipe ID adalah integer
    "nama": nama,
    "encodings": encodings
}

file_path = os.path.join(ENCODING_DIR, f"{id_anggota}_{nama.replace(' ', '_')}.pkl")
with open(file_path, "wb") as f:
    pickle.dump(data, f)

print(f"Pendaftaran wajah berhasil!\nDisimpan di: {file_path}")
