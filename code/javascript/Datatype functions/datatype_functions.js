/**
 * JavaScript datatype functions
 *   adds additional functions to datatypes that are generally considered 
 *   essential in other programming languages
 * 
 *   written by Akoi Meexx on April 13th, 2010 
 */
	
	
	/**
	 * String functions
	 */
	
	// Add string trimming
	String.prototype.trim = function ()
	{
		return this.replace(/^\s*/, "").replace(/\s*$/, "");
	}
	
	
	/**
	 * Array functions
	 */
	
	// Clarify adding an index item to an array
	Array.prototype.add = function (value, index)
	{
		// if no index is passed in(undefined) set it to the array length
		if(index === undefined)
		{
			index = this.length;
		}
		return this.splice(index, 0, value);
	}

	// Clarify removal of an index item from an array
	Array.prototype.remove = function (index)
	{
		return this.splice(index, 1);
	}
