// Include UI structures
#include "globals.h"



UIRegion UIbutton(int width, int height, int x, int y) {
	UIArea region = {
		x, 
		y, 
		x + width, 
		y + height
	};
	UIRegion button = {
		width, 
		height, 
		x, 
		y,
		region
	};
	
	return button;
}
