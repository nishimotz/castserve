/*
 * $Id: mmmshape.cpp,v 1.1 2009/03/30 07:02:05 nishi Exp $
 *
 * mmmshape ver.0.1 by nishimotz
 *
 * Usage:
 * ~/bin/ffmpeg -i hoge.mp3 hoge.wav
 * ~/bin/sox hoge.wav -r 8000 -c 1 -t sw - | ./mmmshape > hoge.shape
 *
 * Output:
 *  (nFrame) = number of frames
 *  (max value of each frame) * nFrame
 * 
 * mmmshape 0.1
 * nFrame 1204
 * 0 -930 857
 * 1 -1169 1388
 * 2 -1427 1244
 * 3 -1830 2974
 * 4 -2339 4013
 * ...
 * 1203 -2339 4013
 * 
 */
#include <vector>
#include <stdio.h>
#include <math.h>
#include <assert.h>

using namespace std;

void read_sw_file(vector<short> *pdata)
{
  	short val;
  	int n;
  	while ((n = fread(&val, sizeof(short), 1, stdin)) != 0) {
    	pdata->push_back(val);
  	}
}

int main(int argc, char **argv)
{
  	const int FRAME_SIZE  =  800;  // 10ms (8kHz)
  	const int FRAME_SHIFT =  800;  // 10ms (8kHz)

	vector<short> data;
	read_sw_file(&data);
	
  	int size = data.size();
  	
  	int nFrame = size / FRAME_SHIFT;
  	printf("mmmshape 0.1\n");
  	printf("nFrame %d\n", nFrame);

	short sampleMin = 0;
	short sampleMax = 0;
  	for (int i = 0; i < nFrame; i++) {
    	sampleMin = 0;
    	sampleMax = 0;
    	for (int j = 0; j < FRAME_SIZE; j++) {
      		int pos = i * FRAME_SHIFT + j;
      		int val = data[pos];
      		if (val < sampleMin) {
      			sampleMin = val;
      		} else if (sampleMax < val) {
      			sampleMax = val;
      		}
    	}
    	printf("%d %d %d\n",i, sampleMin, sampleMax); 
  	}

  	return 0;
}
