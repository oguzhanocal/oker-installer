<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $steps = [];
    function step($msg) {
        echo json_encode(['step'=>$msg]) . "\n";
        ob_flush(); flush();
    }

    $dbHost = $_POST['db_host'] ?? 'localhost';
    $dbName = $_POST['db_name'] ?? 'okerdb';
    $dbUser = $_POST['db_user'] ?? 'root';
    $dbPass = $_POST['db_pass'] ?? '';

    $project = 'member_site';
    $cmds = [
        "composer create-project laravel/laravel $project",
        "unzip overlay.zip -d $project",
        "cd $project && cp .env.example .env",
        "cd $project && php artisan key:generate",
        "cd $project && sed -i 's/DB_CONNECTION=.*/DB_CONNECTION=mysql/' .env",
        "cd $project && sed -i 's/DB_HOST=.*/DB_HOST=$dbHost/' .env",
        "cd $project && sed -i 's/DB_DATABASE=.*/DB_DATABASE=$dbName/' .env",
        "cd $project && sed -i 's/DB_USERNAME=.*/DB_USERNAME=$dbUser/' .env",
        "cd $project && sed -i 's/DB_PASSWORD=.*/DB_PASSWORD=$dbPass/' .env",
        "cd $project && php artisan migrate --force",
        "cd $project && php artisan db:seed --force",
        "cd $project && php artisan storage:link",
    ];
    $i = 0;
    foreach ($cmds as $c) {
        $i++;
        step("Adım $i: $c");
        shell_exec($c . " 2>&1");
    }
    echo json_encode(['done'=>true]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Oker Laravel Kurulum</title>
<style>
body{font-family:Arial;background:#f4f4f4;color:#333;padding:20px;}
.card{background:#fff;border-radius:12px;padding:20px;max-width:600px;margin:50px auto;box-shadow:0 2px 10px rgba(0,0,0,0.1);}
input,button{padding:8px;margin:5px 0;width:100%;font-size:16px;}
.progress{width:100%;background:#eee;border-radius:8px;overflow:hidden;margin-top:15px;}
.bar{height:18px;background:#4caf50;width:0%;transition:width .3s;}
</style>
</head>
<body>
<div class="card">
<h2>Oker Laravel Kurulum Sihirbazı</h2>
<form id="f">
<label>MySQL Sunucu:</label><input name="db_host" value="localhost">
<label>Veritabanı Adı:</label><input name="db_name" value="okerdb">
<label>Kullanıcı Adı:</label><input name="db_user" value="root">
<label>Şifre:</label><input name="db_pass" type="password">
<button type="submit">Kurulumu Başlat</button>
</form>
<div id="log" style="white-space:pre-line;font-family:monospace;margin-top:10px;"></div>
<div class="progress"><div class="bar" id="bar"></div></div>
</div>
<script>
const f=document.getElementById('f'),log=document.getElementById('log'),bar=document.getElementById('bar');
f.onsubmit=async e=>{
 e.preventDefault();log.textContent='Kurulum başlatılıyor...';bar.style.width='0%';
 let fd=new FormData(f);
 const res=await fetch('',{method:'POST',body:fd});
 const reader=res.body.getReader();let done=0,total=11;
 while(true){const {done:valueDone,value}=await reader.read();if(value){const txt=new TextDecoder().decode(value);const jsons=txt.trim().split('\n').filter(Boolean);
 for(const j of jsons){const data=JSON.parse(j);if(data.step){done++;bar.style.width=(done/total*100)+'%';log.textContent+=`\n${data.step}`;}
 if(data.done){bar.style.width='100%';log.textContent+=`\n✅ Kurulum tamamlandı!\nAdmin panel: /admin\nKullanıcı: admin@example.com\nŞifre: ChangeMe123!`;}}}
 if(valueDone)break;}
};
</script>
</body>
</html>
