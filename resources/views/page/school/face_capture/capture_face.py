import face_recognition
import cv2
import os

# === SETTINGS ===
student_id = "230111"  # Change to the ID you want to check
dataset_folder = f"faces/{student_id}"

# Make sure we have at least one saved image
if not os.path.exists(dataset_folder) or len(os.listdir(dataset_folder)) == 0:
    print(f"No saved images found for {student_id}")
    exit()

# Load the first saved image
saved_image_path = os.path.join(dataset_folder, os.listdir(dataset_folder)[0])
saved_image = face_recognition.load_image_file(saved_image_path)
saved_encoding_list = face_recognition.face_encodings(saved_image)

if len(saved_encoding_list) == 0:
    print("No face found in saved image.")
    exit()

saved_encoding = saved_encoding_list[0]

# Start webcam
cap = cv2.VideoCapture(0)  # Change to 1 if 0 doesn't work

print("Press 'q' to quit.")
match_found = False

while True:
    ret, frame = cap.read()
    if not ret:
        break

    rgb_frame = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
    face_locations = face_recognition.face_locations(rgb_frame)
    face_encodings = face_recognition.face_encodings(rgb_frame, face_locations)

    for (top, right, bottom, left), live_encoding in zip(face_locations, face_encodings):
        # Compare faces
        results = face_recognition.compare_faces([saved_encoding], live_encoding, tolerance=0.45)

        if results[0]:
            color = (0, 255, 0)
            label = "MATCH"
            match_found = True
        else:
            color = (0, 0, 255)
            label = "NO MATCH"

        cv2.rectangle(frame, (left, top), (right, bottom), color, 2)
        cv2.putText(frame, label, (left, top - 10),
                    cv2.FONT_HERSHEY_SIMPLEX, 0.8, color, 2)

    cv2.imshow("Face Verification", frame)

    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

cap.release()
cv2.destroyAllWindows()

if match_found:
    print("MATCH")
else:
    print("NO MATCH")
