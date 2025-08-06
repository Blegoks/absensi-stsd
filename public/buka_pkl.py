import os
import pickle

file_path = "C:/laragon/www/absensi-stsd/public/public/encodings/70_wahyu.pkl"

if not os.path.exists(file_path):
    print("File tidak ditemukan:", file_path)
else:
    with open(file_path, "rb") as f:
        data = pickle.load(f)
    print(data)
