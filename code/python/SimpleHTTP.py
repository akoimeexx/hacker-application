import httplib
import re
import urllib

"""
@name: SimpleHTTP
@author: Johnathan McKnight, aka Akoi Meexx
@license: Creative Commons Attribution-Share Alike 3.0
          United States License (CC-SA)

@param: responseStatus Numeric representation of RFC 2616 section 10
@param: responseReason Textual representation of RFC 2616 section 10
        <http://tools.ietf.org/html/rfc2616#section-10>

@function: DELETE(url, data)
@function: GET(url, data)
@function: POST(url, data)
@function: PUT(url, data)

"""
class SimpleHTTPResponse:
	Status = 0
	Reason = ''
	Read = ''

class SimpleHTTP:
	# Initalization function
	def __init__(self):
		pass
	
	# Private internal functions
	@staticmethod
	def __getWebServer(url):
		# Match the webserver TLD from url, e.g. http://www.google.com/
		# returns www.google.com
		regexResult = re.search(r"(://\b)([-_:%@a-zA-Z0-9.]+)", url)
		return regexResult.group(0)[3:]
	
	@staticmethod
	def __getWebFilePath(url):
		# Match the web file path from url, e.g. http://www.google.com/
		# returns /
		regexResult = re.search(r"\b/\b[-_\./%a-zA-Z0-9]*", url)
		try:
			return regexResult.group(0)
		except:
			return '/'
			 
	
	
	# CRUD functions
	# (Create => POST, Read => GET, Update => PUT, Destroy => DELETE)
	@staticmethod
	def DELETE(url, data=''):
		# Sends a DELETE request with supplemental data
		# Returns connectionResponse.read() ouput
		feedback = SimpleHTTPResponse()
		
		# Transmute data into a DELETE-friendly scheme
		deleteURL = SimpleHTTP._SimpleHTTP__getWebFilePath(url)
		deleteData = urllib.urlencode(data)
		deleteHeaders = {"Content-type": "application/x-www-form-urlencoded", "Accept": "text/plain"}
		
		# Establish an HTTPLib connection and send the DELETE request
		# to the file path.
		connection = httplib.HTTPConnection(SimpleHTTP._SimpleHTTP__getWebServer(url))
		connection.request("DELETE", deleteURL, deleteData, deleteHeaders)
		
		# Retrieve the response and set our class response variables
		# Return the read() output to the calling function and
		# close the connection
		connectionResponse = connection.getresponse()
		feedback.Status = connectionResponse.status
		feedback.Reason = connectionResponse.reason
		feedback.Read = connectionResponse.read()
		connection.close()
		return feedback

	@staticmethod
	def GET(url, data=''):
		# Sends a GET request with supplemental data encoded
		# as GET variables
		# Returns connectionResponse.read() ouput
		feedback = SimpleHTTPResponse()
		
		# Transmute data into a URI-friendly scheme
		getURL = SimpleHTTP._SimpleHTTP__getWebFilePath(url)
		if not cmp(data, '') == 1:
			getURL += "?"
			getURL += urllib.urlencode(data)
		
		# Establish an HTTPLib connection and send the GET request
		# to the file path.
		connection = httplib.HTTPConnection(SimpleHTTP._SimpleHTTP__getWebServer(url))
		connection.request("GET", getURL)
		
		# Retrieve the response and set our class response variables
		# Return the read() output to the calling function and
		# close the connection
		connectionResponse = connection.getresponse()
		feedback.Status = connectionResponse.status
		feedback.Reason = connectionResponse.reason
		feedback.Read = connectionResponse.read()
		connection.close()
		return feedback

	@staticmethod
	def POST(url, data=''):
		# Sends a POST request with supplemental data
		# Returns connectionResponse.read() ouput
		feedback = SimpleHTTPResponse()
		
		# Transmute data into a POST-friendly scheme
		postURL = SimpleHTTP._SimpleHTTP__getWebFilePath(url)
		postData = urllib.urlencode(data)
		postHeaders = {"Content-type": "application/x-www-form-urlencoded", 
					   "Accept": "text/plain"}
		
		# Establish an HTTPLib connection and send the POST request
		# to the file path.
		connection = httplib.HTTPConnection(SimpleHTTP._SimpleHTTP__getWebServer(url))
		connection.request("POST", postURL, postData, postHeaders)
		
		# Retrieve the response and set our class response variables
		# Return the read() output to the calling function and
		# close the connection
		connectionResponse = connection.getresponse()
		feedback.Status = connectionResponse.status
		feedback.Reason = connectionResponse.reason
		feedback.Read = connectionResponse.read()
		connection.close()
		return feedback
	
	@staticmethod
	def PUT(url, data=''):
		# Sends a PUT request with supplemental data
		# Returns connectionResponse.read() ouput
		feedback = SimpleHTTPResponse()
		
		# Transmute data into a PUT-friendly scheme
		putURL = SimpleHTTP._SimpleHTTP__getWebFilePath(url)
		putData = urllib.urlencode(data)
		putHeaders = {"Content-type": "application/x-www-form-urlencoded", 
					  "Accept": "text/plain"}
		
		# Establish an HTTPLib connection and send the PUT request
		# to the file path.
		connection = httplib.HTTPConnection(SimpleHTTP._SimpleHTTP__getWebServer(url))
		connection.request("PUT", putURL, putData, putHeaders)
		
		# Retrieve the response and set our class response variables
		# Return the read() output to the calling function and
		# close the connection
		connectionResponse = connection.getresponse()
		feedback.Status = connectionResponse.status
		feedback.Reason = connectionResponse.reason
		feedback.Read = connectionResponse.read()
		connection.close()
		return feedback