<?php
include('system_export-chat.php');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>Export chat</title>
</head>
<body>
<div class="container">
    <div class="row" style="margin-top: 5%">
        <div class="col"></div>
        <div class="col-8">
            <h2 class="mb-4">Выгрузка сообщений из чата</h2>
            <form>
                <div class="mb-4">
                    <label for="id" class="form-label">Идентификатор вашего виджета</label>
                    <input type="number" class="form-control" id="id" aria-describedby="idHelp">
                    <div id="idHelp" class="form-text">Меню "Установить", вкладка "CMS Платформа", выбрать WordPress, строка "Site ID".
                    </div>
                </div>
                <div class="mb-4">
                    <label for="accountApiKey" class="form-label">Секретный ключ аккаунта</label>
                    <input type="text" class="form-control" id="accountApiKey" aria-describedby="accountApiKeyHelp">
                    <div id="accountApiKeyHelp" class="form-text">Меню "Установить", вкладка "CMS Платформа", выбрать WordPress, строка "Account API Key".
                    </div>
                </div>
                <div class="mb-4">
                    <label for="siteApiKey" class="form-label">Секретный ключ виджета</label>
                    <input type="text" class="form-control" id="siteApiKey" aria-describedby="siteApiKeyHelp">
                    <div id="siteApiKeyHelp" class="form-text">Меню "Установить", вкладка "CMS Платформа", выбрать WordPress, строка "Site API Key".
                    </div>
                </div>
                <div class="mb-4">
                    <label for="chan" class="form-label">Идентификатор страницы</label>
                    <input type="text" class="form-control" id="chan" aria-describedby="chanHelp">
                    <div id="chanHelp" class="form-text">Меню "Страницы", столбец "Канал".
                    </div>
                </div>
                <div class="mb-4">
                    <div class="alert alert-danger" id="alertBox" role="alert" style="display: none">
                        Заполните все поля
                    </div>
                </div>
                <div class="alert alert-success" id="successBox" role="alert" style="display: none">
                    Скоро начнется скачивание таблицы
                </div>
                <button type="button" class="btn btn-primary" onclick="sendGetData()">Скачать</button>
            </form>
        </div>
        <div class="col"></div>
    </div>
</div>
</body>
<script>
    function sendGetData() {
        let id = $('#id').val();
        let siteApiKey = $('#siteApiKey').val();
        let accountApiKey = $('#accountApiKey').val();
        let chan = $('#chan').val();
        if (id === '' || siteApiKey === '' || accountApiKey === '' || chan === '') {
            $('#alertBox').show();
        } else {
            $('#alertBox').hide();
            $.ajax({
                url: 'system_export-chat.php',
                method: 'post',
                data: {
                    'id': id,
                    'siteApiKey': siteApiKey,
                    'accountApiKey': accountApiKey,
                    'chan': chan,
                    'action': 'export'
                },
                success: function () {
                    $('#successBox').show();
                    location.replace('export_xls.php?table=' + chan);
                }
            });
        }
    }
</script>
</html>
