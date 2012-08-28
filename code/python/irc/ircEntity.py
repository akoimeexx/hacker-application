import random
import socket


# Define channel object and its functions
class Channel:
	# Define channel name
	channel = ''
	# Define channel buffer
	buffer = []
	# Define netbind handle
	netbind = None
	
	# Initalize Channel items
	def __init__(self, netbind, channel):
		"""
		Requires a socket connection object (netbind)
		and a channel name (channel).
		"""
		self.buffer = []
		self.netbind = netbind
		self.channel = channel
		message = "JOIN " + self.channel + "\r\n"
		self.__send_raw_data(message)
	
	def __del__(self):
		message = "PART " + self.channel + "\r\n"
		self.__send_raw_data(message)
	
	# Send message to channel
	def send_message(self, string=''):
		message = "PRIVMSG " + self.channel + " :" + string
		self.__send_raw_data(message)
		return True
	
	# Grant op status to user
	def op(self, user=''):
		message = "MODE " + self.channel + " +o " + user
		ret = self.__send_raw_data(message)
		return ret
	
	# Revoke op status to user
	def deop(self, user=''):
		message = "MODE " + self.channel + " -o " + user
		ret = self.__send_raw_data(message)
		return ret
	
	# Grant voice status to user
	def voice(self, user=''):
		message = "MODE " + self.channel + " +v " + user
		ret = self.__send_raw_data(message)
		return ret
	
	# Revoke voice status to user
	def devoice(self, user=''):
		message = "MODE " + self.channel + " -v " + user
		ret = self.__send_raw_data(message)
		return ret
	
	# Kick user from channel
	def kick(self, user='', reason=''):
		message = "KICK " + self.channel + " " + user + reason
		ret = self.__send_raw_data(message)
		return ret
	
	# Send raw data
	def __send_raw_data(self, message):
		self.netbind.send(message + "\r\n")
		return True


# Define configuration object and its functions
class Configuration:
	# Initalize connection details
	host = ''
	port = 6667
	channel = ''
	
	# Initalize identification details
	nick = ''
	ident = ''
	realname = ''
	owners = ''

	def __init__(self, host='', port=6667, channel='', nick='', ident='', 
				 realname='', owners=[]):
		self.host = host
		self.port = port
		self.channel = channel
		
		self.nick = nick
		self.ident = ident
		self.realname = realname
		self.owners = owners
	

# Send raw message to server
class ircEntity:
	channels = {}
	Configuration = Configuration()
	messagebuffer = []
	netbind = None
	
	# Initalize ircEntity and configure settings
	def __init__(self, host='', port=6667, channel='', nick='', ident='', 
				 realname='', owners=[]):
		self.Configuration.host = host
		self.Configuration.port = port
		self.Configuration.channel = channel
		
		self.Configuration.nick = nick
		self.Configuration.ident = ident
		self.Configuration.realname = realname
		self.Configuration.owners = owners
	
	# Connect to the irc server
	# returns true
	def connect(self):
		self.netbind = socket.socket()
		self.netbind.connect((self.Configuration.host, self.Configuration.port))
		
		self.send_nick(self.Configuration.nick)
		self.send_ident(self.Configuration.ident)
		return True	
	
	# Listen in for commands and act appropriately
	# returns a socket instance on loop completion
	def listen(self, constant=True):
		# Listen to messages from the server until constant is flagged as False
		while constant == True:
			# Receive messages from the server, output to terminal
			server_message = self.netbind.recv(1024)
			print server_message
			
			self.messagebuffer += server_message + "\r\n"
			
			# Look for our welcome message and send a /join channel
			# request to the default channel
			if (server_message.find('Welcome to the ') != -1 
				and self.Configuration.channel != ''):
				self.channels[self.Configuration.channel] = Channel(self.netbind, self.Configuration.channel)
			
			# Look for private messages, evaluate the message,
			# and perform the associated functions
			if server_message.find('PRIVMSG ' + self.Configuration.nick) != -1:
				constant = self.__eval_message(server_message)
			
			# Send a PONG response to a server's PING request
			if server_message.find('PING :' + self.Configuration.host) != -1:
				print "PONG " + self.Configuration.host + "\r\n"
				self.netbind.send("PONG " + self.Configuration.host + "\r\n")
		return constant
	
	# Disconnect from the irc server
	def disconnect(self, log_output_to_file=False):
		self.__send_raw_data("QUIT")
		self.netbind.close()
	
	
	# Evaluate trigger messages
	def __eval_message(self, message):
		data = message.partition(' PRIVMSG ' + self.Configuration.nick + ' :')
		sender = data[0][data[0].find(':')+1:data[0].find('!')]
		
		# If an owner sent the message
		try:
			if self.is_owner(sender):
				command_data = data[2].partition(' ')
				cargs = command_data[2].strip().partition(' ')
				if command_data[0] == "@join":
					self.join_channel(command_data[2].strip())
					response = 'JOIN\'d channel ' + command_data[2].strip() + '.'
					self.send_message(sender, response)
				elif command_data[0] == "@leave" or command_data[0] == "@part":
					self.part_channel(command_data[2].strip())
					response = 'PART\'d channel ' + command_data[2].strip() + '.'
					self.send_message(sender, response)
				elif command_data[0] == "@op":
					self.channels[cargs[2]].op(cargs[0])
					response = 'User ' + cargs[0] + ' op\'d on ' + cargs[2] + '.'
					self.send_message(sender, response)
				elif command_data[0] == "@deop":
					self.channels[cargs[2]].deop(cargs[0])
					response = 'User ' + cargs[0] + ' deop\'d on ' + cargs[2] + '.'
					self.send_message(sender, response)
				elif command_data[0] == "@voice":
					self.channels[cargs[2]].voice(cargs[0])
					response = 'User ' + cargs[0] + ' voice\'d on ' + cargs[2] + '.'
					self.send_message(sender, response)
				elif command_data[0] == "@devoice":
					self.channels[cargs[2]].devoice(cargs[0])
					response = 'User ' + cargs[0] + ' devoice\'d on ' + cargs[2] + '.'
					self.send_message(sender, response)
				elif command_data[0] == "@kick":
					# cargs = command_data[2].partition(' ')
					#self.channels[CHANNAME].kick(USERNAME, REASON)
					pass
				elif command_data[0] == "@raw":
					self.__send_raw_data(command_data[2])
			
				# Zero-param(0) commands
				if data[2].strip() == "@close":
					return False
			# otherwise, jibber back
			else:
				pass
		except:
			response = 'Error understanding dataset.'
			self.send_message(sender, response)
		
		return True
	
	# Send message to user
	def send_message(self, user, string=''):
		message = "PRIVMSG " + user + " :" + string
		self.__send_raw_data(message)
		return True
	
	# Send NICK request
	def send_nick(self, nick):
		if nick.strip() == '':
			nick = self.Configuration.nick
		message = "NICK " + nick + "\r\n"
		self.__send_raw_data(message)
		self.Configuration.nick = nick
		return True
	
	# Send USER identification request
	def send_ident(self, ident):
		if ident.strip() == '':
			ident = self.Configuration.ident
		message = ("USER " + ident + " " + self.Configuration.host + " +iw :" + 
				   self.Configuration.realname + "\r\n")
		self.__send_raw_data(message)
		self.Configuration.ident = ident
		return True
	
	# Join a channel	
	def join_channel(self, channel):
		self.channels[channel] = Channel(self.netbind, channel)
		
	#Leave a channel
	def part_channel(self, channel):
		del self.channels[channel]
		
	# Add bot owner to owners[]
	def add_owner(self, user):
		ret = self.Configuration.owners.append(user)
		if ret == -1:
			return False
		return True
	
	# Remove bot owner from owners[]
	def remove_owner(self, user):
		ret = self.Configuration.owners.remove(user)
		if ret == -1:
			return False
		return True
	
	# Check if user is an owner
	def is_owner(self, user):
		ret = self.Configuration.owners.index(user)
		if ret == -1:
			return False
		return True
	
	def __send_raw_data(self, message):
		self.netbind.send(message + "\r\n")
		return True


# settings
host = 'random.irc.server'
port = 6667
default_room = '##~/'
nick = 'FordPrefect'
ident = 'PythonBot'
rname = 'Ford Prefect, of Betelgeuse'
owners = ['akoimeexx', 'joker',]

# initalizationersions
bot = ircEntity(host, port, default_room, nick, ident, rname, owners)
bot.connect()
bot.listen()
bot.disconnect()
print bot.messagebuffer
