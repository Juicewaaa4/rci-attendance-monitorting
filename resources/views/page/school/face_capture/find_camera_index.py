import cv2

print("Scanning camera indexes...")
for i in range(5):
    cap = cv2.VideoCapture(i, cv2.CAP_DSHOW)
    if cap.isOpened():
        ret, frame = cap.read()
        if ret:
            print(f"✅ Camera index {i} works")
        else:
            print(f"⚠ Camera index {i} opened but no frame")
        cap.release()
    else:
        print(f"❌ Camera index {i} not available")
