<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wonderwoman</title>
</head>
<style>
    *{
        font-family: Arial, Helvetica, sans-serif;
    }
    body{
        background-color: #f0fff0;
    }
    #center{
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%,-50%);
        border: 3px solid black;
        padding: 30px;
        border-radius: 20px;
    }
    .inputTitle{
        display: block;
        margin-bottom: 5px;
        margin-top: 25px;
        font-size: 16px;
        font-weight: bold;
    }
    small{
        font-size: 12px;
    }
    #startBtn{
        display: block;
        margin-top: 25px;
        
    }
</style>
<body>
    <div id="center">
        <span class="inputTitle">X-RapidAPI-Key</span>
        <input type="text" id="rapidApiKey" placeholder="Key">
        <span class="inputTitle">Referrer</span>
        <input type="text" id="referrer" placeholder="Referrer">
        <span class="inputTitle">Websites (TXT file)</span>
        <input type="file" accept=".txt" id="websitesInput"><br>
        <small>seperated by new line</small>
        <button id="startBtn">Start</button>
    </div>
</body>

<script>
    let startBtn = document.getElementById("startBtn")
    let websitesInput = document.getElementById("websitesInput")
    let rapidApiKey = document.getElementById("rapidApiKey")
    rapidApiKey.value = localStorage.getItem("rapidApiKey") || ""
    let referrer = document.getElementById("referrer").value
    let fileName;
    let websites = [];
    let current = 0;
    startBtn.addEventListener("click", () => {
        if(websitesInput.files.length > 0 && rapidApiKey.value){
            fileName = websitesInput.files[0].name;
            current = Number(localStorage.getItem(fileName) || 0)
            var reader = new FileReader();
            reader.onload = function() {
                websites = reader.result.split("\n");
                startCampaign()
                document.getElementById("center").innerHTML = "Open Console (F12 or CTRL+SHIFT+I)"
                localStorage.setItem(fileName,current)
                localStorage.setItem("rapidApiKey",rapidApiKey.value)
            };
            reader.readAsText(websitesInput.files[0]);
        }
    })
    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }
    let startCampaign = async () => {
        for(let a = current; a < websites.length; a++){
            await sleep(1300)
            current = a;
            localStorage.setItem(fileName,current)
            sendTraffic()
        }
    }
    let sendTraffic = () => {
        const options = {
            method: 'GET',
            headers: {
                'X-RapidAPI-Key': rapidApiKey.value, 
                'X-RapidAPI-Host': 'web-traffic-generator.p.rapidapi.com'
            }
        };
        fetch(`https://web-traffic-generator.p.rapidapi.com/?website=${encodeURI("https://"+websites[current])}&referrer=${encodeURI(referrer)}&device=mobile`, options)
        .then(response => response.json())
        .then(response => console.log(response))
        .catch(err => console.error(err));
    }
</script>
</html>
