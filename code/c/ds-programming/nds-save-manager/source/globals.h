// Define Application States
enum CODE_STATE {
	SHUTDOWN = -1, 
	STARTUP = 0, 
	MAINMENU = 1, 
	BACKUPMENU = 2, 
	RESTOREMENU = 3, 
	DELETEMENU = 4, 
	LOADINGMEDIA = 5, 
	TRANSFERDIALOG = 6, 
	COMPLETEDTRANSFER = 7, 
	
	/* Remove this STATE when finished */
	DEBUGGING = 42
};

// Define test string constant, 256 characters in length
const char TEST_STRING[256] = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ\n1234567890-=~!@#$%%^&*()_+[]{}\\|;':\",.<>/?\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas tincidunt consequat sem eu sagittis. Aenean felis mauris, imperdiet nec dictum cras amet.";
// Define credits string constant
const char CREDITS[241] = "NDS Save Manager v0.1.1\n  written by Akoi Meexx\n\nSpecial thanks to mtheall, zeromus, cyborg_ar, and everyone at irc.blitzed.org/#dsdev; as well as pokedoc for inspiring me to write my own version of his program.";


// Tilemap definitions for buttons
const int TILEMAP_WIDTH = 8;		// Tile width in pixels.
const int BUTTON_WIDTH = 224 / 8;	// Button width in tiles.
const int BUTTON_HEIGHT = 48 / 8;	// Button height in tiles.

const int BACKGROUND_WIDTH = 256 / 8;	// Hardware map width in tiles.
const int BACKGROUND_HEIGHT = 256 / 8;	// Hardware map height in tiles.

const int SCREEN_WIDTH = 256 / 8;	// Screen width in tiles.
const int SCREEN_HEIGHT = 192 / 8;	// Screen height in tiles.


// UI structures
typedef struct {
	int x1;
	int y1;
	int x2;
	int y2;
}UIArea;

typedef struct {
	int width;
	int height;
	int x;
	int y;
	UIArea region;
}UIRegion;

typedef struct {
//	u16* gfx;
	int width;
	int height;
	int x;
	int y;
	UIArea region;
}UIButton;
