💬 PHP WebSocket Chat App
A simple real-time chat app built with PHP and Ratchet WebSockets. You type a message, your friend sees it instantly. That's it!

What's this?
This started as a learning project to understand how WebSockets work in PHP. Instead of refreshing the page to see new messages (like old-school PHP), WebSockets keep a live connection open so messages appear in real time — just like WhatsApp or Slack.

What you'll need

XAMPP (for Apache + PHP)
PHP 8.x
Composer (PHP package manager)
A terminal / command prompt


Project structure
Project/
├── bin/
│   └── chat-server.php   ← run this to start the server
├── src/
│   └── Chat.php          ← handles connections and messages
├── vendor/               ← Ratchet and its dependencies
├── composer.json
└── index.php             ← the chat UI in your browser

Getting started
1. Clone or download the project
Put it inside your XAMPP htdocs folder:
C:\xampp\htdocs\Project\
2. Install dependencies
Open a terminal inside the project folder and run:
bashcomposer install
3. Enable PHP sockets
Open C:\xampp\php\php.ini, find this line:
;extension=sockets
Remove the ; so it becomes:
extension=sockets
Then restart Apache in XAMPP.
4. Start the WebSocket server
Open a terminal and run:
bashphp bin/chat-server.php
You should see:
Server running on port 8081...
Keep this terminal open — it needs to stay running.
5. Open the chat in your browser
http://127.0.0.1/Project/index.php
Enter your name, hit Join Chat, and start messaging!

How to test it
Open the same URL in two different browser tabs (or two different browsers). Type a message in one tab — it should appear in the other one instantly.

How it works (simply)

The browser connects to the PHP server via WebSocket
You type a message and hit send
The server receives it and broadcasts it to everyone connected
All connected browsers display the message in real time

No page refresh. No polling. Just a live, open connection.

Common problems
ProblemFixCall to undefined function socket_create()Enable extension=sockets in php.iniFailed to listen on port 8081Run the terminal as AdministratorCould not connect to socketMake sure the server is running firstPHP code showing as plain textOpen via http:// not file://Name not showing after loginMake sure session_start() is the very first line

Built with

PHP
Ratchet — PHP WebSocket library
ReactPHP — async event loop (used by Ratchet)
jQuery — for the frontend


What I learned

How TCP sockets work at a low level
The difference between raw sockets and proper WebSockets
How Ratchet handles connections, messages, and disconnects
Why session_start() must always be the first line in PHP 😅


License
MIT — do whatever you want with it.
