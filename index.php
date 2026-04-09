<?php 
session_start(); 
if(isset($_POST['submit'])){ 
    $_SESSION['name'] = $_POST['name']; 
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ratchet Chat</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .chat-wrap { width: 100%; max-width: 480px; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.1); }
        .chat-header { padding: 14px 18px; background: #534AB7; display: flex; align-items: center; gap: 10px; }
        .avatar { width: 38px; height: 38px; border-radius: 50%; background: #CECBF6; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: bold; color: #3C3489; }
        .chat-title { color: #fff; font-size: 15px; font-weight: bold; }
        .chat-sub { color: #CECBF6; font-size: 12px; }
        .online-dot { width: 8px; height: 8px; border-radius: 50%; background: #1D9E75; margin-left: auto; }
        #msg_box { height: 380px; overflow-y: auto; padding: 16px; background: #f0f2f5; display: flex; flex-direction: column; gap: 10px; }
        .msg { display: flex; flex-direction: column; max-width: 75%; }
        .msg.me { align-self: flex-end; align-items: flex-end; }
        .msg.other { align-self: flex-start; align-items: flex-start; }
        .msg-name { font-size: 11px; color: #888; margin-bottom: 3px; padding: 0 4px; }
        .msg-bubble { padding: 9px 13px; border-radius: 16px; font-size: 14px; line-height: 1.5; }
        .msg.me .msg-bubble { background: #534AB7; color: #fff; border-bottom-right-radius: 4px; }
        .msg.other .msg-bubble { background: #fff; color: #333; border: 1px solid #e0e0e0; border-bottom-left-radius: 4px; }
        .msg-time { font-size: 10px; color: #aaa; margin-top: 3px; padding: 0 4px; }
        .input-row { display: flex; align-items: center; gap: 8px; padding: 12px 14px; border-top: 1px solid #eee; background: #fff; }
        #msg { flex: 1; border: 1px solid #ddd; border-radius: 20px; padding: 9px 14px; font-size: 14px; outline: none; background: #f5f5f5; }
        #msg:focus { border-color: #534AB7; }
        #btn { width: 38px; height: 38px; border-radius: 50%; background: #534AB7; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; }
        #btn:hover { background: #3C3489; }
        #btn svg { width: 16px; height: 16px; fill: white; }
        .name-wrap { padding: 2.5rem 2rem; display: flex; flex-direction: column; gap: 14px; }
        .name-wrap h2 { font-size: 20px; color: #333; }
        .name-wrap p { font-size: 14px; color: #888; }
        #name { border: 1px solid #ddd; border-radius: 10px; padding: 10px 14px; font-size: 14px; outline: none; width: 100%; }
        #name:focus { border-color: #534AB7; }
        .join-btn { padding: 11px; border-radius: 10px; background: #534AB7; color: #fff; border: none; font-size: 15px; font-weight: bold; cursor: pointer; width: 100%; }
        .join-btn:hover { background: #3C3489; }
    </style>
</head>
<body>

<?php if(!isset($_SESSION['name'])): ?>

<div class="chat-wrap">
    <div class="chat-header">
        <div class="avatar">RC</div>
        <div><div class="chat-title">Ratchet Chat</div><div class="chat-sub">PHP WebSocket</div></div>
    </div>
    <div class="name-wrap">
        <h2>Join the chat</h2>
        <p>Enter your name to get started</p>
        <form method="post" action="">
            <input type="text" id="name" name="name" placeholder="Your name..." required />
            <br><br>
            <input type="submit" name="submit" value="Join Chat" class="join-btn" />
        </form>
    </div>
</div>

<?php else: ?>

<div class="chat-wrap">
    <div class="chat-header">
        <div class="avatar"><?php echo strtoupper(substr($_SESSION['name'], 0, 2)); ?></div>
        <div>
            <div class="chat-title">Ratchet Chat</div>
            <div class="chat-sub"><?php echo htmlspecialchars($_SESSION['name']); ?></div>
        </div>
        <div class="online-dot"></div>
    </div>

    <div id="msg_box"></div>

    <div class="input-row">
        <input type="text" id="msg" placeholder="Type a message..." />
        <button id="btn">
            <svg viewBox="0 0 24 24"><path d="M2 21l21-9L2 3v7l15 2-15 2z"/></svg>
        </button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    var conn = new WebSocket('ws://localhost:8081');
    var myName = "<?php echo $_SESSION['name']; ?>";

    conn.onopen = function(e) {
        addMessage('system', '', 'You joined the chat');
    };

    conn.onmessage = function(e) {
        var data = jQuery.parseJSON(e.data);
        if(data.name !== myName) {
            addMessage('other', data.name, data.msg);
        }
    };

    jQuery('#btn').click(function() { sendMsg(); });

    jQuery('#msg').keypress(function(e) {
        if(e.key === 'Enter') sendMsg();
    });

    function sendMsg() {
        var msg = jQuery('#msg').val().trim();
        if(msg === '') return;
        var content = { msg: msg, name: myName };
        conn.send(JSON.stringify(content));
        addMessage('me', myName, msg);
        jQuery('#msg').val('');
    }

    function addMessage(type, sender, text) {
        var now = new Date().toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
        var html = '';
        if(type === 'system') {
            html = '<div style="text-align:center;font-size:11px;color:#aaa;padding:4px 0;">'+text+'</div>';
        } else {
            html = '<div class="msg '+type+'">' +
                (type==='other' ? '<span class="msg-name">'+sender+'</span>' : '') +
                '<div class="msg-bubble">'+text+'</div>' +
                '<span class="msg-time">'+now+'</span>' +
            '</div>';
        }
        jQuery('#msg_box').append(html);
        jQuery('#msg_box').scrollTop(jQuery('#msg_box')[0].scrollHeight);
    }
</script>

<?php endif; ?>

</body>
</html>