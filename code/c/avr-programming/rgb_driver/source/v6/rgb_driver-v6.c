/**
 * AVR program to provide lighting modes for an RGB LED
 * Copyright (c) 2011 Akoi Meexx (http://akoimeexx.com/)
 * 
 * Chip type: Attiny13
 * Clock frequency: Default internal clock (9.6MHz / 8 = 1.2MHz)
 *                       +--------+
 *        [        (PB5) |1*     8| (VCC)  Power ]
 *        [ Button (PB3) |2      7| (PB2)   BLUE ]
 *        [        (PB4) |3      6| (PB1)  GREEN ]
 *        [ Ground (GND) |4      5| (PB0)    RED ]
 *                       +--------+
 */

/**
 * AVR-specific defines and includes
 */
#include <avr/io.h>
#define F_CPU 1200000UL // 1.2 MHz
// Some macros that make the code more readable
#define output_low(port,pin) port &= ~(1<<pin)
#define output_high(port,pin) port |= (1<<pin)
#define set_input(portdir,pin) portdir &= ~(1<<pin)
#define set_output(portdir,pin) portdir |= (1<<pin)

/**
 * Alias our color channel pinouts
 */
#define RED PB0
#define GREEN PB1
#define BLUE PB2

/**
 * Alias our button pinout
 */
#define BUTTON PB3

/**
 * Create our rgb structure and declare it below as a global
 */
typedef struct {
	int red;
	int green;
	int blue;
} rgb;
rgb channels = { 0x00, 0x00, 0x00 };

/**
 * Define our lighting modes and declare our mode global
 */
#define DEBUG_MODE 0
#define RAINBOW_MODE 1
#define SOLID_MODE 2
#define PULSE_MODE 3
int lighting_mode;

/**
 * TODO: Optimize. This method just feels too 'clunky'.
 */
void rainbow_step(void) {
	//Fade from blue to red
	if(channels.blue > 0x00 && channels.red == 0xFF && channels.green == 0x00) {
		channels.blue--;
	}
	if(channels.blue == 0xFF && channels.red < 0xFF && channels.green == 0x00) {
		channels.red++;
	}
	
	//Fade from green to blue
	if(channels.green > 0x00 && channels.blue == 0xFF && channels.red == 0x00) {
		channels.green--;
	}
	if(channels.green == 0xFF && channels.blue < 0xFF && channels.red == 0x00) {
		channels.blue++;
	}
	
	// Fade from red to green
	if(channels.red > 0x00 && channels.green == 0xFF && channels.blue == 0x00) {
		channels.red--;
	}
	if(channels.red == 0xFF && channels.green < 0xFF && channels.blue == 0x00) {
		channels.green++;
	}
}

/**
 * TODO: Build function
 */
void solid_step(void) {
	// Do nothing, the pwm running should be more than enough
}

/**
 * TODO: Build function
 */
void pulse_step(void) {
	//
}

/**
 * Software Pulse Width Modulation for RGB
 */
void do_pwm(int r_duty, int g_duty, int b_duty, int rate) {
	int i;

	while (rate != 0) {
/*
18:03 < RikusW> IIoutput_high(PORTB, RED);IIoutput_high(PORTB, GREEN);IIoutput_high(PORTB, BLUE);
18:04 < RikusW> can be written as      PORTB |= (1<<RED) | (1<<GREEN) | (1<<BLUE);
*/
		PORTB |= (1<<RED) | (1<<GREEN) | (1<<BLUE);
/*		output_high(PORTB, RED);
		output_high(PORTB, GREEN);
		output_high(PORTB, BLUE);*/
		
		for (i=0; i < 255; i++) {
			if (i == r_duty)
				output_low(PORTB, RED);
			if (i == g_duty)
				output_low(PORTB, GREEN);
			if (i == b_duty)
				output_low(PORTB, BLUE);
		}
		rate--;
	}
}

void init(void) {
	channels.red = 0xFF;
	lighting_mode = RAINBOW_MODE;
	
	set_output(DDRB, RED);  
	set_output(DDRB, GREEN);
	set_output(DDRB, BLUE);
	
	set_input(DDRB, BUTTON);  
}

int main(void) {
	init();
	while(1) {
		switch (lighting_mode) {
			case DEBUG_MODE:
				break;
			case RAINBOW_MODE:
				rainbow_step();
				break;
			case SOLID_MODE:
				solid_step();
				break;
			case PULSE_MODE:
				pulse_step();
				break;
			default:
				lighting_mode = RAINBOW_MODE;
		}
		do_pwm(channels.red, channels.green, channels.blue, 15);
	}
	return 0;
}
