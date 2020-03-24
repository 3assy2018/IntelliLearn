import cv2
import sys
import matplotlib.pyplot as plt
import index as fr
import os
import threading
import queue


newQ = queue.Queue

cascPath = "C:\Python36\Lib\site-packages\cv2\data\haarcascade_frontalface_default.xml"
basePath = "dataset"
# Create the haar cascade
faceCascade = cv2.CascadeClassifier(cascPath)

# Video Mode
video_capture = cv2.VideoCapture(0)

# Detect faces in the image
students = os.listdir(basePath)
comparePhotos = {}
studentsWDic = {}
attendees = {}

for student in students:
    studentsWDic[student] = 0
    attendees[student] = 0
    comparePhotos[student] = []
    studentPhotos = os.listdir(basePath + "/" + student)
    for studentPhoto in studentPhotos:
        img = cv2.imread(basePath + "/" + student + "/" + studentPhoto)
        comparePhotos[student].append(img)


def run_recognizer_queue(queue):
    while True:
        qElement = queue.get()
        try:
            recognizer = fr.recognize(qElement[0], qElement[1], qElement[2])
            if recognizer:
                if attendees[recognizer] != 1:
                    attendees[recognizer] = 1
                    print(recognizer + " attended")
            else:
                print("Not Identified")
            queue.task_done()
        except:
            queue.task_done()


def stream():
    while True:
        try:
            # Capture frame-by-frame
            ret, img = video_capture.read()

            gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)

            faces = faceCascade.detectMultiScale(
                gray,
                scaleFactor=1.1,
                minNeighbors=5,
                minSize=(30, 30),
                flags=cv2.CASCADE_SCALE_IMAGE
            )

            # Draw a rectangle around the faces
            for (x, y, w, h) in faces:
                cv2.rectangle(img, (x, y), (x + w, y + h), (255, 0, 0), 2)
                sub_face = img[y:y + h, x:x + w]
                newQ.put([img, comparePhotos, studentsWDic])
            cv2.imshow('Attendance Tracker', img)

            if cv2.waitKey(1) & 0xFF == ord('q'):
                break
        except:
            pass

    # When everything is done, release the capture
    video_capture.release()
    cv2.destroyAllWindows()


for i in range(5):
    streamWorker = threading.Thread(
        target=stream,
        name='worker-{}'.format(i),
    )
    streamWorker.start()

recognizer_worker = threading.Thread(
    target=run_recognizer_queue,
    args=(newQ,),
    name='worker-{}'.format(6),
)
recognizer_worker.setDaemon(True)
recognizer_worker.start()
