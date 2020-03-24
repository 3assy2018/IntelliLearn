import time

import cv2
import sys
import matplotlib.pyplot as plt
import index as fr
import os
import threading
import queue
import argparse

# construct the argument parser and parse the arguments
ap = argparse.ArgumentParser()
ap.add_argument("-c", "--capture", required=True,
	help="Path to current call capture.")
args = vars(ap.parse_args())

newQ = queue.Queue()

cascPath = "C:\Python36\Lib\site-packages\cv2\data\haarcascade_frontalface_default.xml"
basePath = "../public/storage/photos"
# Create the haar cascade
faceCascade = cv2.CascadeClassifier(cascPath)

# Video Mode
# video_capture = cv2.VideoCapture(0)

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
                    print(recognizer)
            else:
                print(False)
            queue.task_done()
        except:
            queue.task_done()


def detectAndRecognize():
    img = cv2.imread("../public/storage/" + args['capture'])
    # Convert into grayscale
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    # Detect faces
    faces = faceCascade.detectMultiScale(gray, 1.1, 4)
    # Draw rectangle around the faces
    for (x, y, w, h) in faces:
        cv2.rectangle(img, (x, y), (x + w, y + h), (255, 0, 0), 2)
        sub_face = img[y:y + h, x:x + w]
        newQ.put([sub_face, comparePhotos, studentsWDic])
    # Display the output
    time.sleep(10)


streamWorker = threading.Thread(
    target=detectAndRecognize,
    name='worker-{}'.format(0),
)
streamWorker.start()


recognizer_worker = threading.Thread(
    target=run_recognizer_queue,
    args=(newQ,),
    name='worker-{}'.format(6),
)
recognizer_worker.setDaemon(True)
recognizer_worker.start()
