<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>GoogleSheets CRUD</title>
    </head>
<body>
<!-- Tocas UI：CSS 與元件 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tocas-ui/2.3.3/tocas.css">
<!-- Tocas JS：模塊與 JavaScript 函式 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tocas-ui/2.3.3/tocas.js"></script>
<!-- JQuery CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<div class="ts raised segment">
    <p>哈囉！這裡是用來練習PHP對Google試算表的新增、刪除、修改、查詢資料。</p>
    <p>我的部落格：<a href="https://tw-free.blogspot.com">https://tw-free.blogspot.com</a></p>
    <p>以下是測試用的試算表，已設定<mark>每5分鐘</mark>會自動做一次資料還原。</p>
    <p>因為他不會即時更新，你可以<a href="https://docs.google.com/spreadsheets/d/1R8yd6608Mux8tYlX2tNqNmOiKtrsxJDKJaxlHWRYBHU/edit#gid=1118095092" target="_blank">開啟</a>試算表查看資料變化，或按 <button class="ts pulsing button" onclick="load_google_sheets()">這裡</button> 更新試算表。</p>
</div>

<iframe id="google_sheets" src="https://docs.google.com/spreadsheets/d/1R8yd6608Mux8tYlX2tNqNmOiKtrsxJDKJaxlHWRYBHU/gviz/tq?tqx=out:html&tq&gid=0"
	width="500" height="200" marginwidth="0" marginheight="0"
	scrolling="Yes" frameborder="0"
	onload="Javascript:SetCwinHeight()">
</iframe>

<script>
function load_google_sheets(){
    var f = document.getElementById('google_sheets');
    f.src = f.src;
}

function select_uid(){
    $("#btn01").attr("class","ts loading button");
    var $uid = $.trim($("#uid").val());
    var URLs = "ajax.php?todo=select" + "&uid=" + $uid;
    $.ajax({
        url: URLs,
        type: "POST",
        dataType: 'text',
        success: function (msg) {
            alert(msg);
            $("#btn01").attr("class","ts button");
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}

function insert_uid(){
    $("#btn02").attr("class","ts loading button");
    var $uid = $.trim($("#new_uid").val());
    var $name = $.trim($("#new_name").val());
    var URLs = "ajax.php?todo=insert" + "&uid=" + $uid + "&name=" + $name;
    $.ajax({
        url: URLs,
        type: "POST",
        dataType: 'text',
        success: function (msg) {
            alert(msg);
            load_google_sheets();
            $("#btn02").attr("class","ts button");
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}

function update_uid(){
    $("#btn03").attr("class","ts loading button");
    var $uid = $.trim($("#update_uid").val());
    var $money = $.trim($("#money").val());
    var URLs = "ajax.php?todo=update" + "&uid=" + $uid + "&money=" + $money;
    $.ajax({
        url: URLs,
        type: "POST",
        dataType: 'text',
        success: function (msg) {
            alert(msg);
            load_google_sheets();
            $("#btn03").attr("class","ts warning button");
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}

function delete_uid(){
    $("#btn04").attr("class","ts loading button");
    var $uid = $.trim($("#del_uid").val());
    var URLs = "ajax.php?todo=delete" + "&uid=" + $uid;
    $.ajax({
        url: URLs,
        type: "POST",
        dataType: 'text',
        success: function (msg) {
            alert(msg);
            load_google_sheets();
            $("#btn04").attr("class","ts negative basic button");
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}
</script>


    
<div class="ts raised segment">
    <p><h3>查詢資料</h3></p>
    請輸入員工編號：
    <div class="ts input"><input type="text" id="uid" placeholder="例：a01"></div>
    <button id="btn01" class="ts button" onclick="select_uid()">查詢</button>
</div>
<hr />

<div class="ts raised segment">
    <p><h3>新增資料</h3></p>
    加入一位新成員
     編號：<div class="ts input"><input type="text" id="new_uid" placeholder="例：a11"></div>
     名稱：<div class="ts input"><input type="text" id="new_name" placeholder="例：體育老師"></div>
    <button id="btn02" class="ts button" onclick="insert_uid()">加入</button>
    <p id="text1"></p>
</div>
<hr />

<div class="ts raised segment">
    <p><h3>修改資料</h3></p>
    請輸入員工編號：
    <div class="ts input"><input type="text" id="update_uid" placeholder="例：a01"></div>
    請輸入獎金：
    <div class="ts input"><input type="text" id="money" placeholder="例：10000"></div>
    <button id="btn03" class="ts warning button" onclick="update_uid()">發大財</button>
    <p id="text1"></p>
</div>
<hr />

<div class="ts raised segment">
    <p><h3>刪除資料</h3></p>
    請輸入員工編號：
    <div class="ts input"><input type="text" id="del_uid" placeholder="例：a01"></div>
    <button id="btn04" class="ts negative basic button" onclick="delete_uid()">開除</button>
    <p id="text1"></p>
</div>
<hr />

<script type="text/javascript">
function SetCwinHeight() {
    var iframeid = document.getElementById("google_sheets"); //iframe id  
    if (document.getElementById) {
        if (iframeid && !window.opera) {
            if (iframeid.contentDocument && iframeid.contentDocument.body.offsetHeight) {
                iframeid.height = iframeid.contentDocument.body.offsetHeight;
            } else if (iframeid.Document && iframeid.Document.body.scrollHeight) {
                iframeid.height = iframeid.Document.body.scrollHeight;
            }
        }
    }
}
</script>

</body>
</html>
