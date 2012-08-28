import re
import socket
import sys

class Server():
	"""Define a Server object, its artifacts and functions."""
	
	def __init__(self, server_host="", server_port=""):
		"""Set up server configuration settings and initalize the socket.
		
		Return True on success, False on fail.
		
		"""
		
		# Attributes
		self.server_host = u"irc.freenode.net"
		self.server_log = []
		self.server_port = u"6667"
		
		# Set our connection information if it's passed in.
		# Server host validation.
		if not re.match(r"^[a-zA-Z0-9]+(\.[a-zA-Z0-9]+)*$", server_host) == None:
			self.server_host = server_host
		else:
			self.server_log.append(u"[NOTICE]: No valid server address passed in, using default(%s).\r\n" % self.server_host)
		
		# Server port validation.
		if server_port.isdigit():
			self.server_port = server_port
		else:
			self.server_log.append(u"[NOTICE]: No valid server port passed in, using default(%s).\r\n" % (self.server_port))
		
		# Attempt to create a socket for our connection.
		try:
			self.socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
		except:
			self.server_log.append(u"[ERROR]: There was an error initializing the connection socket! No connection can be made.\r\n")
	
	def connect(self):
		"""Connect to the server.
		
		Return True on success, False on fail.
		
		"""
		
		# Attempt to connect with our socket.
		try:
			self.socket.connect((self.Configuration.host, self.Configuration.port))
		except:
			self.server_log.append(u"[ERROR]: There was an error connecting to host %s on port %s!\r\n" % (self.server_host, self.server_port))
			return False
		
		return True	
	
	def send(self, data):
		"""Send a message to the server.
		
		Return True on success, False on fail.
		
		"""
		
		# Attempt to send a server string with our socket.
		try:
			self.socket.send(data)
		except:
			self.server_log.append(u"[ERROR]: There was an error sending \"%s\" to host %s on port %s!\r\n" % (data, self.server_host, self.server_port))
			return False
		
		return True
	
	def disconnect(self, log_output_to_file=False):
		"""Disconnect from the server."""
		
		self.send(u"QUIT\r\n")
		self.socket.close()
	

class User():
	"""Define a User object, its artifacts and functions."""
	
	def __init__(self, user_nick, user_host=None, user_ident=None, user_password=None, user_real_name=None):
		"""Set up user artifacts."""
		
		# Attributes
		# self.user_attributes = []		# Undefined for now, not used in code.
		self.user_host = u"i.am.linux.borg"
		self.user_ident = u"PyBot"
		self.user_log = []
		self.user_password = u""
		self.user_real_name = u"PyBot, of the clan MacBot"
		
		# User nick validation.
		if not user_nick == None:
			self.user_nick = user_nick
		else:
			self.user_log.append(u"[ERROR]: No valid user nick passed in!\r\n")
			return False
		
		# User host validation.
		if not re.match(r"^[a-zA-Z0-9]+(\.[a-zA-Z0-9]+)*$", user_host) == None:
			self.user_host = user_host
		else:
			self.user_log.append(u"[NOTICE]: No valid user host passed in, using default(%s).\r\n" % self.user_host)
		
		# User ident validation.
		if not user_ident == None:
			self.user_ident = user_ident
		else:
			self.user_log.append(u"[NOTICE]: No valid user ident passed in, using default(%s).\r\n" % self.user_ident)
		
		# User password validation. Used for NickSERV/IDENTIY services.
		if not user_password == None:
			self.user_password = user_password
		
		# User real name validation.
		if not user_real_name == None:
			self.user_real_name = user_real_name
		else:
			self.user_log.append(u"[NOTICE]: No valid user real name passed in, using default(%s).\r\n" % self.user_real_name)
		
		return True
	
	def nick(self):
		"""Return a server string to NICK the user"""
		
		return u"NICK %s\r\n" % (self.user_nick)
	
	def user(self):
		"""Return a server string to USER the user"""
		
		return u"USER %s %s +iw : %s\r\n" % (self.user_ident, self.user_host, self.user_real_name)
	
	def query(self, string):
		"""Return a server string to QUERY/PRIVMSG the user"""
		
		return u"QUERY %s :%s\r\n" % (self.user_nick, string)
	
	def whois(self):
		"""Return a server string to WHOIS the user"""
		
		# TODO: Make it work.
		pass

class Bot(User):
	"""Define a Bot object, its artifacts and functions.
	
	Extended by class User.
	
	"""
	
	def __init__(self, user_nick, user_host="", user_ident="", user_password="", user_real_name="", bot_global_owners=""):
		"""Set up bot artifacts."""
		
		# Attributes.
		self.bot_global_owners = []
		
		# Bot global owner validation.
		if not bot_global_owners == None:
			self.bot_global_owners = bot_global_owners
		else:
			self.user_log.append(u"[WARNING]: No global bot owners have been passed in! Your bot may be uncontrollable!\r\n")
		
		User.__init__(self, user_nick, user_host, user_ident, user_password, user_real_name)
	
	def add_global_owner(self, username):
		"""Add an owner to the global bot owners"""
		
		# Username validation.
		if not username == '' or not username == None:
			self.bot_global_owners.append(username)
		else:
			self.user_log.append(u"[NOTICE]: Could not add \"%s\" to bot global owners.\r\n" % (username))
			return False
		
		return True
	
	def remove_global_owner(self, username):
		"""Remove a username from the global bot owners"""
		
		# Username validation.
		if not username == '' or not username == None:
			try:
				self.bot_global_owners.remove(username)
			except:
				self.user_log.append(u"[NOTICE]: \"%s\" is not in bot global owners.\r\n" % (username))
				return False
		else:
			self.user_log.append(u"[NOTICE]: Could not remove \"%s\" from bot global owners.\r\n" % (username))
			return False
		
		return True
	
	def is_owner(self, user):
		if self.bot_global_owners.index(user) == -1:
			return False
		return True

class Channel():
	"""Define a channel object, its artifacts and functions"""
	
	# Functions
	def __init__(self, channel):
		"""Set up channel artifacts"""
		
		self.channel_log = []
		self.channel_name = u"%s" % (channel)
		self.channel_topic = u""
		self.channel_user_list = []
	
	def join():
		"""Return a server string to JOIN the channel"""
		
		return u"JOIN %s\r\n" % (self.channel_name)
	
	def part():
		"""Return a server string to PART the channel"""
		
		return u"PART %s\r\n" % (self.channel_name)
	
	def topic(self, string=None):
		""" Return a server string to TOPIC the channel"""
		
		return u"TOPIC %s :%s"
	
	def message(self, string):
		"""Return a server string to PRIVMSG the channel"""
		
		return u"PRIVMSG %s :%s\r\n" % (self.channel_name, string)
	
	def op(self, user):
		"""Return a server string to OP a user"""
		
		return u"MODE %s +o %s\r\n" % (self.channel_name, user)
	
	def deop(self, user):
		"""Return a server string to DEOP a user"""
		
		return u"MODE %s -o %s\r\n" % (self.channel_name, user)
	
	def halfop(self, user):
		"""Return a server string to HALFOP a user"""
		
		return u"MODE %s +h %s\r\n" % (self.channel_name, user)
	
	def dehalfop(self, user):
		"""Return a server string to DEHALFOP a user"""
		
		return u"MODE %s -h %s\r\n" % (self.channel_name, user)
	
	def ban(self, user):
		pass
	
	def kickban(self, user):
		pass
	
	def unban(self, user):
		pass
	
	def voice(self, user):
		"""Return a server string to VOICE a user"""
		
		return u"MODE %s +v %s\r\n" % (self.channel_name, user)
	
	def devoice(self, user):
		"""Return a server string to DEVOICE a user"""
		
		return u"MODE %s -v %s\r\n" % (self.channel_name, user)
	
	def kick(self, user, reason=''):
		"""Return a server string to KICK a user, with optional reason"""
		
		return u"KICK %s %s %s\r\n" % (self.channel_name, user, reason)



# Experimentation block

def main():
	
#	# parse command line options
#	try:
#		opts, args = getopt.getopt(sys.argv[1:], "h", ["help"])
#	except getopt.error, msg:
#		print msg
#		print "for help use --help"
#		sys.exit(2)
#	# process options
#	for o, a in opts:
#		if o in ("-h", "--help"):
#			print __doc__
#			sys.exit(0)
#	# process arguments
#	for arg in args:
#		process(arg) # process() is defined elsewhere
	pass

if __name__ == "__main__":
	print """
	                    -/shmNMMMMMMNmhs/-
	                `+hNMmyo/:......:/oymMNh+`
	              :hMMh/. .:oshhhhhhso:. ./hMMh:
	            -dMNs` :ymMMMMMMMMMMMMMMmy: `sNMd-
	           oMMs``omMNMMMMMMMMMMMMMMMMMMmo``sMMo
	          yMm- :mo.```:odNMMMMMMMMMMMMMMMm: -mMy
	         sMm. oM/        `sNMMMMMMMMMMMMMMMo .mMs
	        :MM. +MN.          -MMMMMMMMMmmmNMMM+ .MM:
	        dMs .NMMMNh+`       yMo/:-``     `hMN. sMd
	        MM: +MMMMMMMNs`     Mh  -+`      :mMM+ :MM
	        MM: +MMMMMMMMMm.   :MdyNdo.    :dNodM+ :MM
	        MM: +MMMMMMMMMMm`   /+:`      smo` -M+ :MM
	        dMy `NMMMMMMMMMd                  `hN` yMd
	        -MM. +MMMMMMMm/                  sMM+ .MM:
	         yMm. oMMMNy-                    oMo .mMs
	          yMm: :h+`                   `/dm: :mMy
	           +MM:             .:.-::/+ymMmo``sMMo
	            .`             oMMMMMMMMms: `sNMd-
	                          :hhhhhso:. .+hMMh:
	                        .:......:/oymMNh+`
	                       .hNMMMMMMNmhs/-

	==========================================================
	                        Don't Panic!
	==========================================================

	  IRC PyBot script written by Akoi Meexx.

	  If you experience any technical issues*, please review
	  the "Hitchhikers Guide to the Galaxy" for detailed
	  instructions on how to not panic.


	==========================================================
	                       Usage Details
	==========================================================

	  python irc_pybot.py <server> <port> <nick> <owner>

	  <server> defaults to irc.freenode.net
	  <port>   defaults to 6667
	  <nick>   defaults to pybot
	  <owner>  defaults to Null


	  * Technical issues include, but are not limited to:
	      Vogon poetry readings, hyperspace bypasses,
	      falling whales, server crashes, and pesky
	      pangalactic gargleblaster hangovers.

"""
	print "Executing main()..."
	main()