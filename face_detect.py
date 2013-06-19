import os
import argparse


def face_detect(pic_path):
    '''Face detector, save in a Conputed directory the faces it detects

    Parameters
    ----------
        pic_path : str
            path of the picture

    '''

    import cv2
    import Image
    from scipy.misc import imresize
    from iqface.detect import FaceDetect

    #The detected faces will be stored in the Computed directory
    #If it does not exist, it will be created here
    dirname = os.path.dirname(pic_path)
    dirname = os.path.join(dirname, 'Computed')
    if not os.path.exists(dirname):
        os.mkdir(dirname)

    #Detect Faces with the face Detector of CV2
    images, images_c = [], []
    face_detector = FaceDetect()
    face_detector.load()
    im = cv2.imread(pic_path, cv2.CV_LOAD_IMAGE_GRAYSCALE)
    im_color = cv2.imread(pic_path)
    rects = face_detector.run(im)
    filename = os.path.basename(pic_path)
    res = []

    #Retrieves the face, resizes it, alignes it and save the color
    #Face in a file in the folder Computed
    for countf, (x1, y1, x2, y2) in enumerate(rects):
        dx, dy = (x2 - x1)*.4, (y2 - y1)*.4
        x_min, x_max = max(x1 - dx, 0), min(x2 + dx, im.shape[1])
        y_min, y_max = max(y1 - dy, 0), min(y2 + dy, im.shape[0])
        roi_c = im_color[y_min:y_max, x_min:x_max, ::-1]
        roi_crs = imresize(roi_c, (250, 250))
        fname = os.path.join(dirname,
                             str(countf)+"_"+filename)
        roi_pil = Image.fromarray(roi_crs)
        roi_pil.save(fname)
        res.append(fname)
    return res


def main():
    parser = argparse.ArgumentParser(description=('Run face clustering on a'
                                                  ' set of pictures.'))
    parser.add_argument('files', metavar='<file path>', type=str, nargs='+',
                        help='Pictures to detect faces')
    parser.add_argument('-r', action='store_true',
                        help='Pictures to detect faces')
    args = parser.parse_args()
    res = []
    for f in args.files: 
        if os.path.splitext(f)[1][1:] in ['jpg','jpeg','png','bmp','dib','jpe','jp2','pbm','ppm','tiff','tif'] and os.path.exists(f):
            res.extend(face_detect(f))
    if args.r:
        print "::".join(res)

if __name__ == '__main__':
    main()
