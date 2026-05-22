import sys
import face_recognition
import json
import os
import numpy as np
from PIL import Image

def main():
    output_data = {}

    if len(sys.argv) < 2:
        output_data["error"] = "No image path provided"
        print(json.dumps(output_data))
        sys.exit(1)

    image_path = sys.argv[1]

    # Resolve image location relative to "public"
    base_dir = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
    full_path = os.path.join(base_dir, "public", image_path)

    if not os.path.exists(full_path):
        output_data["error"] = f"Image file does not exist: {full_path}"
        print(json.dumps(output_data))
        sys.exit(1)

    try:
        img = Image.open(full_path).convert("RGB")
        img.thumbnail((800, 800))
        image = np.array(img)

        import dlib
        model_type = "cnn" if getattr(dlib, "DLIB_USE_CUDA", False) else "hog"

        face_locations = face_recognition.face_locations(image, model=model_type)
        encodings = face_recognition.face_encodings(image, face_locations)

        if len(encodings) == 0:
            output_data["error"] = "No face found in the image"
        else:
            output_data["encoding"] = encodings[0].tolist()

    except Exception as e:
        output_data["error"] = str(e)

    # 👇 print only JSON — no debug text
    sys.stdout.write(json.dumps(output_data))

if __name__ == "__main__":
    main()
