# 💬 PHP WebSocket Chat App

A simple real-time chat app built with PHP and Ratchet WebSockets. You type a message, your friend sees it instantly. That's it!

---

## What's this?

This started as a learning project to understand how WebSockets work in PHP. Instead of refreshing the page to see new messages (like old-school PHP), WebSockets keep a live connection open so messages appear in real time — just like WhatsApp or Slack.

---

## What you'll need

- [XAMPP](https://www.apachefriends.org) (for Apache + PHP)
- PHP 8.x
- [Composer](https://getcomposer.org) (PHP package manager)
- A terminal / command prompt

---

## Project structure
Project/
├── bin/
│   └── chat-server.php   ← run this to start the server
├── src/
│   └── Chat.php          ← handles connections and messages
├── vendor/               ← Ratchet and its dependencies
├── composer.json
└── index.php             ← the chat UI in your browser
