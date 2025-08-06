import face_recognition
import os
import pickle
import re
import matplotlib.pyplot as plt
import seaborn as sns
import numpy as np
from sklearn.metrics import classification_report, accuracy_score, confusion_matrix

# --- Path konfigurasi ---
ENCODING_DIR = r"C:\laragon\www\absensi-stsd\public\public\encodings"
TEST_IMAGE_DIR = r"C:\laragon\www\absensi-stsd\public\face_test"

# --- Load encoding ---
print(f"🔍 Mencari file encoding di: {ENCODING_DIR}")
encoding_files = [f for f in os.listdir(ENCODING_DIR) if f.endswith('.pkl')]

if not encoding_files:
    print("❌ Tidak ada file .pkl ditemukan.")
    exit()

known_encodings = []
known_names = []
name_mapping = {}

for file_name in encoding_files:
    full_path = os.path.join(ENCODING_DIR, file_name)
    try:
        with open(full_path, 'rb') as f:
            data = pickle.load(f)
            encs = data['encodings'] if isinstance(data, dict) else data
            enc_name = os.path.splitext(file_name)[0]
            known_encodings.extend(encs)
            known_names.extend([enc_name] * len(encs))
            print(f"✅ Berhasil load: {file_name}")

            short_name = enc_name.split('_')[1].lower()
            name_mapping[short_name] = enc_name
            print(f"🔗 Mapping: {short_name} → {enc_name}")
    except Exception as e:
        print(f"❌ Gagal load {file_name}: {e}")

# --- Evaluasi gambar ---
y_true = []
y_pred = []

image_files = [f for f in os.listdir(TEST_IMAGE_DIR) if f.lower().endswith(('.jpg', '.jpeg', '.png'))]

if not image_files:
    print("❌ Tidak ada gambar ditemukan di folder:", TEST_IMAGE_DIR)
    exit()

for img_file in image_files:
    img_path = os.path.join(TEST_IMAGE_DIR, img_file)
    image = face_recognition.load_image_file(img_path)
    locations = face_recognition.face_locations(image)
    encodings = face_recognition.face_encodings(image, locations)

    base_name = os.path.splitext(img_file)[0].lower()
    match = re.match(r'([a-z]+)', base_name)
    short_name = match.group(1) if match else "unknown"
    ground_truth = name_mapping.get(short_name, "Unknown")

    if not encodings:
        print(f"⚠️ Tidak ditemukan wajah dalam gambar: {img_file}")
        continue

    for face_encoding in encodings:
        results = face_recognition.compare_faces(known_encodings, face_encoding)
        face_distances = face_recognition.face_distance(known_encodings, face_encoding)

        if True in results:
            best_match_index = face_distances.argmin()
            predicted_name = known_names[best_match_index]
            print(f"🟢 {img_file}: Wajah dikenali sebagai {predicted_name}")
        else:
            predicted_name = "Unknown"
            print(f"🔴 {img_file}: Wajah tidak dikenali.")

        y_true.append(ground_truth)
        y_pred.append(predicted_name)

# --- Evaluasi multiclass ---
print("\n📊 Evaluasi Performa:")
report = classification_report(y_true, y_pred, zero_division=0, output_dict=True)
report_text = classification_report(y_true, y_pred, zero_division=0)
print(report_text)

accuracy = accuracy_score(y_true, y_pred)
print(f"Akurasi Keseluruhan: {accuracy:.2f}")

# --- Simpan classification report ke PNG ---
plt.figure(figsize=(12, 6))
plt.axis('off')
plt.title("Classification Report", fontsize=14, weight='bold', pad=20)

report_table = [["Label", "Precision", "Recall", "F1-Score", "Support"]]
for label, metrics in report.items():
    if label in ["accuracy", "macro avg", "weighted avg"]:
        continue
    row = [
        label,
        f"{metrics['precision']:.2f}",
        f"{metrics['recall']:.2f}",
        f"{metrics['f1-score']:.2f}",
        int(metrics['support'])
    ]
    report_table.append(row)

table = plt.table(cellText=report_table, colLabels=None, loc='center', cellLoc='center')
table.auto_set_font_size(False)
table.set_fontsize(10)
table.scale(1.2, 1.5)

plt.tight_layout()
classification_output = "classification_report.png"
plt.savefig(classification_output)
plt.close()
print(f"📁 Classification report disimpan di: {classification_output}")

# --- Confusion Matrix ---
labels = sorted(set(y_true + y_pred))
cm = confusion_matrix(y_true, y_pred, labels=labels)

plt.figure(figsize=(10, 8))
sns.heatmap(cm, annot=True, fmt="d", cmap="Blues", xticklabels=labels, yticklabels=labels)
plt.xlabel("Prediksi")
plt.ylabel("Label Sebenarnya")
plt.title("Confusion Matrix - Face Recognition")
plt.tight_layout()

confusion_output = "confusion_matrix.png"
plt.savefig(confusion_output)
plt.close()

print(f"📁 Confusion matrix disimpan di: {confusion_output}")
