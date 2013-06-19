import os
import argparse
import csv
import numpy as np

from iqface.detect import FaceDetect
from iqfeat.aligner import ALIGNER
from iqfeat.lbp import LBP


def face_detect(pic_path, face_detector, align, lbp_generator, saveFile):
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
    if not os.path.exists(dirname):
        os.mkdir(dirname)

    #Detect Faces with the face Detector of CV2
    im = cv2.imread(pic_path, cv2.CV_LOAD_IMAGE_GRAYSCALE)
    im_color = cv2.imread(pic_path)
 

    if im == None:
        return ''

    
    rects = face_detector.run(im)
    filename = os.path.basename(pic_path)
    res = []
  
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
        roi = im[y_min:y_max, x_min:x_max]
        roi_rs = cv2.resize(roi, dsize=(250, 250),
                            interpolation=cv2.INTER_CUBIC)
        roi_rs.shape += (1,)
        roi_aligned = align.compute(np.tile(roi_rs, (1, 1, 3)))
        h, w = roi_aligned.shape[0], roi_aligned.shape[1]
        h_crop, w_crop = 115, 110
        hs = (h - h_crop)
        ws = (w - w_crop)
        fim = roi_aligned[hs/2:hs/2+h_crop, ws/2:ws/2+w_crop]
        row = [fname]
        row.extend(lbp_generator.compute(fim))
        roi_c = im_color[y_min:y_max, x_min:x_max, ::-1]
        roi_crs = imresize(roi_c, (250, 250))
        roi_pil = Image.fromarray(roi_crs)
        roi_pil.save(fname)
        res.append(fname)
        saveFile.writerow(row)
    return res


def main():
    parser = argparse.ArgumentParser(description=('Run face clustering on a'
                                                  ' set of pictures.'))
    parser.add_argument('files', metavar='<file path>', type=str, nargs='+',
                        help='Pictures to detect faces')
    parser.add_argument('-r', action='store_true',
                        help='Pictures to detect faces')
    args = parser.parse_args()
    
    face_detector = FaceDetect()
    face_detector.load()
    align = ALIGNER("./model_face")
    align.load()
    lbpGen = LBP()

    dirname = os.path.dirname(args.files[0])
    dirname = os.path.join(dirname, 'Computed')
    savename = os.path.join(dirname, "data.csv")
    saveCSV = csv.writer(open(savename, "a"))
   
    res = []
    for f in args.files: 
        if os.path.splitext(f)[1][1:] in \
          ['jpg', 'jpeg', 'png', 'bmp', 'dib', 'jpe', 
           'jp2', 'pbm', 'ppm','tiff','tif'] and \
          os.path.exists(f):
            print f
            res.extend(face_detect(f, face_detector, align, lbpGen, saveCSV))
            os.remove(f)
    if args.r:
        print "::".join(res)

if __name__ == '__main__':
    main()
