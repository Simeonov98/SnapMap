#!C:\\Users\sunko\anaconda3\python.exe
import math

import sys
import os
import shutil
from PIL import Image
from datetime import datetime
from urllib import request


class MapDownloader(object):
    def __init__(self, lat_start, lng_start, lat_end, lng_end, zoom=12, tile_size=256):

        self.tile_server = 'http://tile.openstreetmap.org/{z}/{x}/{y}.png'


        self.lat_start = lat_start
        self.lng_start = lng_start
        self.lat_end = lat_end
        self.lng_end = lng_end
        self.zoom = zoom
        self.tile_size = tile_size
        
        self.queue = []

        self._generate_xy_point()

    def _generate_xy_point(self):
        self._x_start, self._y_start = self._convert_latlon_to_xy(self.lat_start, self.lng_start)
        self._x_end, self._y_end = self._convert_latlon_to_xy(self.lat_end, self.lng_end)

    def _convert_latlon_to_xy(self, lat, lng):
        tiles_count = 1 << self.zoom

        point_x = (self.tile_size / 2 + lng * self.tile_size / 360.0) * tiles_count // self.tile_size
        sin_y = math.sin(lat * (math.pi / 180.0))
        point_y = ((self.tile_size / 2) + 0.5 * math.log((1 + sin_y) / (1 - sin_y)) *
                   -(self.tile_size / (2 * math.pi))) * tiles_count // self.tile_size

        return int(point_x), int(point_y)

    def _fetch_worker(self):
        while True:
            item = self.q.get()
            if item is None:
                break

            idx, url, current_tile = item
            print('Fetching #{} of {}: {}'.format(idx, len(self.queue), url))
            request.urlretrieve(url, current_tile)

            self.q.task_done()

    def write_into(self, filename):
        # create temp dir
        directory = os.path.abspath('{name}/{}'.format(datetime.now().strftime("%Y-%m-%d_%H-%M-%S"),name = sys.argv[7]))
        if not os.path.exists(directory):
            os.makedirs(directory)

        # generate source list
        idx = 1
        for x in range(0, self._x_end + 1 - self._x_start):
            for y in range(0, self._y_end + 1 - self._y_start):
                temp_list = []
                url = self.tile_server.format(
                    x=str(self._x_start + x), y=str(self._y_start + y), z=str(self.zoom))
                current_tile = os.path.join(directory, 'tile-{}_{}_{}.png'.format(
                    str(self._x_start + x), str(self._y_start + y), str(self.zoom)))
                temp_list.append(idx)
                temp_list.append(url)
                temp_list.append(current_tile)
                self.queue.append(temp_list)
                idx += 1


        for index, elt in enumerate(self.queue):
            idx = elt[0]
            url = elt[1]
            current_tile = elt[2]
            print('Fetching #{} : {}'.format(idx, url))
            request.urlretrieve(url, current_tile)


        # combine image into single
        width, height = 256 * (self._x_end + 1 - self._x_start), 256 * (self._y_end + 1 - self._y_start)
        map_img = Image.new('RGB', (width, height))

        for x in range(0, self._x_end + 1 - self._x_start):
            for y in range(0, self._y_end + 1 - self._y_start):
                current_tile = os.path.join(directory, 'tile-{}_{}_{}.png'.format(
                    str(self._x_start + x), str(self._y_start + y), str(self.zoom)))
                im = Image.open(current_tile)
                map_img.paste(im, (x * 256, y * 256))

        map_img.save(filename)
        source = "{name} {number}.png".format(number=sys.argv[6],name = sys.argv[7])
        destination = sys.argv[7]
        shutil.move(source,destination)



def main():

    md = MapDownloader(float(sys.argv[1]), float(sys.argv[2]), float(sys.argv[3]), float(sys.argv[4]), zoom=int(sys.argv[5]))

    md.write_into('{name} {number}.png'.format(number = sys.argv[6],name = sys.argv[7]))

    print("The map has successfully been created")



if __name__ == '__main__':
    main()
