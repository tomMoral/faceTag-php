import flickr
import urllib
import os
import sys
import time

from urlparse import urlparse

group = {}
group['baby'] = '1081487@N21'

def get_url(id, page):
        try:
            g = flickr.Group(id)
            l = g.getPhotos(page=page)

            for p in l:
                url = p.getLarge()
                print url
        except:
            time.sleep(1)
            
if __name__ == '__main__':
    id = sys.argv[1]
    page=int(sys.argv[2])
    get_url(id,page)


