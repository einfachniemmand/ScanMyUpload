<?php
    $type = "file";
    if (isset($_GET['file'])) {
        $code = $_GET['file'];
        $data = json_decode(file_get_contents(__DIR__ . '/../data.json'), true);
        if (isset($data[$code]) && isset($data[$code]['type'])) {
            $type = htmlspecialchars($data[$code]['type']);
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="ScanMyUpload is an easy-to-use free online file system which is used for creating qr codes or links from files.">
        <meta name="keyword" content="scanmyupload, qr, qrcode, file, createfromqr, file to qr, qr code, scan my upload, file sharing, share files">
        <meta name="author" content="ScanMyUpload">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Share File | ScanMyUpload</title>
        <link rel="icon" type="image/png" href="https://static.alwaysdata.com/aldjango/img/favicon.png">
        <link rel="stylesheet" href="/static/styles/global.css">
        <link rel="stylesheet" href="/static/styles/switch.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,200..800&display=swap" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/qr-code-styling@1.5.0/lib/qr-code-styling.js"></script>
    </head>
    <body>
        <div class="logo-design" onclick="setTimeout(()=>{location.href='/'},100)">
            <i class="fa-solid fa-qrcode"></i>
            ScanMyUpload
        </div>
        <div class="content" style="opacity: 0;">
            <span class="header sgl">Share your <?php echo $type; ?></span>
            <div class="share-all">
                <div id="qr"></div>
                <div class="urltocopy">
                    <div class="url"></div>
                    <i class="fa-solid fa-copy" onclick="app.copy(document.querySelector('.url').textContent)"></i>
                </div>
                <div class="shorten-url">
                    <label class="switch">
                        <input type="checkbox" id="shortenSwitch" onchange="app.events.shorten(this)">
                        <span class="slider"></span>
                    </label>
                    <span style="margin-left:8px;">Shorten URL</span>
                </div>
            </div>
          <label for="file-select" class="share-1" onclick="setTimeout(()=>{location.href='/'},150)">Upload another file</label>
        </div>
        <script>
            const app = {
                events : {
                    shorten : (target)=>{
                        app.file.url= target.checked ? `smu.ct.ws/${app.file.code()}` : `${location.hostname}/${app.file.code()}`;
                        app.qr.create(app.file.url,target.checked?225:250,app.e.qr);
                        if(target.checked){
                            document.querySelector('#qr').classList.add('shorten')
                        }else{
                            document.querySelector('#qr').classList.remove('shorten')
                        } 
                    }
                },
                qr : {
                    create : (content,size,e)=>{
                        e.innerHTML=""
                        const qrCode = new QRCodeStyling({
                            width: size,
                            height: size,
                            data: content,
                            dotsOptions: {
                                color: "#ffffff",
                                type: "rounded"
                            },
                            backgroundOptions: {
                                color: "#00000000"
                            }
                        });
                        qrCode.append(e);
                        document.querySelector(".url").textContent =content;
                    }
                },
                file : {
                    code : ()=>{
                        const url = new URL(window.location.href);
                        const params = new URLSearchParams(url.search);
                        return params.get("file");
                    }
                },
                copy:(text)=>{
                    navigator.clipboard.writeText(text).then(() => {
                        const icon = document.querySelector('.fa-copy');
                        if (icon) {
                            icon.classList.remove('fa-copy');
                            icon.classList.add('fa-check');
                            setTimeout(() => {
                                icon.classList.remove('fa-check');
                                icon.classList.add('fa-copy');
                            }, 1500);
                        }
                    });
                },
                e:{
                    qr:document.querySelector("#qr")
                }
            }
            window.onload = ()=>{
                app.file.url = `${location.hostname}/${app.file.code()}`
                app.qr.create(app.file.url,250,app.e.qr)
                document.body.querySelector("div.content").style.opacity = "1";
            }
        </script>
    </body>
</html>