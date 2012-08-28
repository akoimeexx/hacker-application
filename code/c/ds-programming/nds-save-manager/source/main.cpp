/**
 * NDS Save Manager, v.0.1.1
 *     written by Akoi Meexx
 * 
 * http://akoimeexx.com/projects/
 */

// Include standard header files from devkitARM
#include <nds.h>

// Include global structures
#include "globals.h"

// Include images
#include "screenimage-logo.h"
#include "screenimage-background.h"
#include "buttons.h"
#include "file-icons.h"
#include "selector-green.h"
#include "terminal.h"


int main(void) {
	int APPLICATION_STATE = STARTUP;
	
	// Set video mode on both screens to two text/extended bg layers
 	videoSetMode(MODE_5_2D);
	videoSetModeSub(MODE_3_2D);
	
	// Assign memory banks for the backgrounds
	vramSetBankA(VRAM_A_MAIN_BG);
	vramSetBankC(VRAM_C_SUB_BG);
	
	// Initialize background instances
	int bg = bgInit(3, BgType_Bmp8, BgSize_B8_256x256, 0,0);
	int bgSub = bgInitSub(3, BgType_Bmp8, BgSize_B8_256x256, 0,0);
	
	// Load logo screenimage
	decompress(screenimage_logoBitmap, bgGetGfxPtr(bg),  LZ77Vram);
	dmaCopy(screenimage_logoPal, BG_PALETTE, screenimage_logoPalLen);
	
	// Load background screenimage
	decompress(screenimage_backgroundBitmap, bgGetGfxPtr(bgSub), LZ77Vram);
	dmaCopy(screenimage_backgroundPal, BG_PALETTE_SUB, screenimage_backgroundPalLen);	
	
	
	while(APPLICATION_STATE != SHUTDOWN) {
		swiWaitForVBlank();
		
		switch (APPLICATION_STATE) {
			case STARTUP:
				
				APPLICATION_STATE = MAINMENU;
				break;
			case MAINMENU:
				// Draw the screen, then wait for input
				break;
			case BACKUPMENU:
				break;
			case RESTOREMENU:
				break;
			case DELETEMENU:
				break;
			case LOADINGMEDIA:
				break;
			case TRANSFERDIALOG:
				break;
			case COMPLETEDTRANSFER:
				break;
			default:
				break;
		}
	}
}
