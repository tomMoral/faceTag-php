import os
import argparse
import csv
import numpy as np

from iqface.detect import FaceDetect

def face_detect(pic_path, face_detector):
    '''Face detector, save in a Conputed directory the faces it detects

    Parameters
    ----------
        pic_path : str
            path of the picture

        face_detector : Object FaceDetect
            Object to perform the face detection step

        align : Object ALIGNER
            Object to perform the alignment step

    '''

    import cv2
    import Image
    from scipy.misc import imresize
    #The detected faces will be stored in the Computed directory
    #If it does not exist, it will be created here
    dirname = os.path.dirname(pic_path)
    dirname = os.path.join(dirname, 'Computed')

    #Detect Faces with the face Detector of CV2
    im = cv2.imread(pic_path, cv2.CV_LOAD_IMAGE_GRAYSCALE)
    im_color = cv2.imread(pic_path)
    if im_color == None:
        return ''

    rects = face_detector.run(im)
    filename = os.path.basename(pic_path)

    if im.shape[0] == 0:
        return ''

    #Retrieves the face, resizes it, alignes it and save the color
    #Face in a file in the folder Computed
    for countf, (x1, y1, x2, y2) in enumerate(rects):
        fname = os.path.join(dirname,
                             str(countf)+"_"+filename)
        dx, dy = (x2 - x1)*.4, (y2 - y1)*.4
        x_min, x_max = max(x1 - dx, 0), min(x2 + dx, im.shape[1])
        y_min, y_max = max(y1 - dy, 0), min(y2 + dy, im.shape[0])
        roi_c = im_color[y_min:y_max, x_min:x_max, ::-1]
        roi_crs = imresize(roi_c, (250, 250), interp='cubic')
        roi_pil = Image.fromarray(roi_crs)
        roi_pil.save(fname)
        print "@ "+ fname


def main():
    parser = argparse.ArgumentParser(description=('Run face clustering on a'
                                                  ' set of pictures.'))
    parser.add_argument('files', metavar='<file path>', type=str, nargs='+',
                        help='Pictures to detect faces')
    args = parser.parse_args()
    
    face_detector = FaceDetect()
    face_detector.load()

    dirname = os.path.dirname(args.files[0])
    dirname = os.path.join(dirname, 'Computed')
    if not os.path.exists(dirname):
        os.mkdir(dirname)
   
    for f in args.files: 
        if os.path.splitext(f)[1][1:] in ['jpg', 'jpeg', 'png', 'bmp',
                'dib', 'jpe', 'jp2', 'pbm', 'ppm','tiff','tif'] and\
                os.path.exists(f):
            face_detect(f, face_detector)
            os.remove(f)

if __name__ == '__main__':
    main()
