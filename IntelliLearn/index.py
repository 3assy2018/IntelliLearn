# import face_recognition
#
# picture_of_me = face_recognition.load_image_file("photos/3assy2.jpg")
# my_face_encoding = face_recognition.face_encodings(picture_of_me)[0]
#
# # my_face_encoding now contains a universal 'encoding' of my facial features that can be compared to any other picture of a face!
#
# unknown_picture = face_recognition.load_image_file("photos/youssef1.jpg")
# unknown_face_encoding = face_recognition.face_encodings(unknown_picture)[0]
#
# # Now we can see the two face encodings are of the same person with `compare_faces`!
#
# results = face_recognition.compare_faces([my_face_encoding], unknown_face_encoding)
#
# if results[0] == True:
#     print("It's a picture of me!")
# else:
#     print("It's not a picture of me!")

import os
import face_recognition

def recognize(image, comparePhotos, studentDic):
    breaker = False
    capturedFaceEncoded = face_recognition.face_encodings(image)[0]

    for student in comparePhotos:
        for photo in comparePhotos[student]:
            photoEncoded = face_recognition.face_encodings(photo)[0]
            compareResult = face_recognition.compare_faces([capturedFaceEncoded], photoEncoded)
            if compareResult[0] == True:
                studentDic[student] = studentDic[student] + 1
                break
        if breaker:
            break

    max_key = max(studentDic, key=lambda k: studentDic[k])


    if studentDic[max_key] != 0:
        return max_key
    else:
        return False
