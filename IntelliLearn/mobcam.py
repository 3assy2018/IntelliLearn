import urllib.request
import cv2
import numpy as np
import cropper

url='http://10.105.78.59:8080/shot.jpg'

cascPath = "C:\Python36\Lib\site-packages\cv2\data\haarcascade_frontalface_default.xml"

# Create the haar cascade
faceCascade = cv2.CascadeClassifier(cascPath)

while True:
    imgResp=urllib.request.urlopen(url)
    imgNp=np.array(bytearray(imgResp.read()),dtype=np.uint8)
    img=cv2.imdecode(imgNp,-1)
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)

    faces = faceCascade.detectMultiScale(
        gray,
        scaleFactor=1.1,
        minNeighbors=5,
        minSize=(30, 30),
        flags=cv2.CASCADE_SCALE_IMAGE
    )

    print("Found {0} faces!".format(len(faces)))

    # Draw a rectangle around the faces
    for (x, y, w, h) in faces:
        cv2.rectangle(img, (x, y), (x + w, y + h), (255, 0, 0), 2)

    cropper.facecrop(img)

    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

    # all the opencv processing is done here
    cv2.imshow('test',cv2.resize(img, (957, 654)))
    if ord('q')==cv2.waitKey(10):
        exit(0)