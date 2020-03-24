import cv2
import os
import sys


def facecrop(image):
    cascPath = "C:\Python36\Lib\site-packages\cv2\data\haarcascade_frontalface_default.xml"
    cascade = cv2.CascadeClassifier(cascPath)

    img = cv2.imread(image)

    minisize = (img.shape[1],img.shape[0])
    miniframe = cv2.resize(img, minisize)

    faces = cascade.detectMultiScale(miniframe)
    counter = 0

    for f in faces:
        x, y, w, h = [ v for v in f ]
        cv2.rectangle(img, (x,y), (x+w,y+h), (255,255,255))

        sub_face = img[y:y+h, x:x+w]
        cv2.imwrite("cropped_"+str(counter)+".jpg", sub_face)
        counter += 1
    return