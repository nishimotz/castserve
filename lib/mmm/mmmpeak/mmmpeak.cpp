/*
 * $Id: mmmpeak.cpp,v 1.1 2009/03/30 07:02:05 nishi Exp $
 *
 * mmmpeak ver.0.1 by nishimotz
 *
 * Usage:
 *  ~/bin/sox hoge.wav -t sw - | ./mmmpeak 
 */
#include <stdio.h>

int main(int argc, char **argv)
{
	const char *version = "0.1";
  	const short thres = 32000;
  	const float sample_rate = 8000.0; // samples/sec
  	const int over_samples_init = 60; // samples
  	const int curr_over_thres = 2;
  	const int over_pos_margin = 8000; // samples

  	short val;
  	int n;
  	int n_over = 0;
	int n_curr_over = 0;
 	int n_samples = 0;
  	int count_over = 0;

  	int over_samples = 0;
  	int curr_over_pos = 0;
  	int prev_over_pos = -99999999;
  	int state = 0;

  	printf("<mmmPeak ver=\"%s\" thres=\"%d\" sampleRate=\"%.0f\">\n",
	 version, thres, sample_rate);

  	//
  	// power:  ...--*^*...**^^^**^^^**........
  	// state:  0000002000000222332223333330000
  	// over :                ------------- 
  
  	while ((n = fread(&val, sizeof(short), 1, stdin)) != 0) {
    	switch (state) {
    	case 0:
      		if (val < -thres || thres < val) {
				state = 2;
				n_over ++;
				n_curr_over = 1;
				curr_over_pos = n_samples;
      		}
      		break;
    	case 2:
      		if (val < -thres || thres < val) {
				state = 2;
				n_over ++;
				n_curr_over ++;
      		} else {
				state = 3;
				over_samples = over_samples_init;
      		}
      		break;
    	case 3:
      		if (val < -thres || thres < val) {
				state = 2;
				n_over ++;
				n_curr_over ++;
      		} else {
				over_samples --;
				if (over_samples <= 0) {
	  				if (n_curr_over > curr_over_thres) {
	    				if (prev_over_pos + over_pos_margin <= curr_over_pos) {
	      					count_over ++;
	      					printf("<clip>%f</clip>\n", (float)curr_over_pos / sample_rate);
	      					prev_over_pos = curr_over_pos;
	    				}
	  				}
	  				state = 0;
				}
      		}
      		break;
    	}
    	n_samples ++;
  	}
  
  	float duration = (float) n_samples / sample_rate;
  	float clip_rate = (float) count_over / duration;
  	printf("<clipCount>%d</clipCount>\n", count_over);
  	printf("<mediaTime>%f</mediaTime>\n", duration);
  	printf("<clipPerSec>%f</clipPerSec>\n", clip_rate);
  	printf("</mmmPeak>\n");
  	return 0;
}
