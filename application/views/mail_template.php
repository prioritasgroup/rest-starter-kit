<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>

* {
	margin: 0;
	padding: 0;
	box-sizing: border-box;
}

html, body {
	font-family: Helvetica, Arial, sans-serif;
	font-weight: 300;
	color: #333333;
	background: #f2f2f2;
}

html {
	font-size: 18px;
}

body {
	padding-left: 24px;
	padding-right: 24px;
	
	line-height: 1.428;
}


h3 {
	margin-bottom: 0.5em;
	font-family: Helvetica,Arial,sans-serif;
	font-size: 1.2em;
    font-weight: normal;
}

p {
	margin-bottom: 1em;
}

p:last-child {
	margin: 0;
}

a {
	color: #3688be;
}

.mail {
	max-width: 720px;
	
	margin: 32px auto;
	padding: 40px 32px;
	
	background: white;
	
	border: 1px solid #ddd;
	border-top: 4px solid #4ae;
	/* box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), inset 0 4px #4ae; */
}

.signature {
	margin-top: 2em;
	padding-left: 1em;
	
	font-size: 0.9em;
	
	color: #888;
	
	border-left: 4px solid #aaa;
}

.signature p {
	margin-bottom: 0;
}

.signature .name {
	text-style: italic;
}

.btn {
    background: #44AAEE;
    padding: 10px;
    border-radius: 5px;
    color: white;
    border: none;
    text-decoration: none;
}
</style>
</head>
<body>
    <div class="mail">
        <div class="body">
            <h3>Hello <strong><?=ucwords(strtolower($user->fullname));?></strong></h3>
            <p>
            Sepertinya kami telah menerima permintaan pengaturan ulang kata sandi untuk akun <b><i>PREDATOR</i></b> yang terkait dengan email ini. Untuk mengatur ulang kata sandi Anda, cukup klik tautan di bawah ini!
            </p>
            <p>Jika Anda tidak merasa melakukan permintaan ini, Anda dapat mengabaikan email ini.</p>

            <br>
            <center>
            <a class="btn" href="<?=files_url('auth/reset_password/'. $forgotten_password_code);?>">Ganti Password</a>
            <center>
        </div>
        <div class="signature">
            <p><i>Prioritas Group</i></p>
            <p style="font-size: 10px"><i>Predator Team</i></p>
            <p style="font-size: 10px"><i>Jl. Raden Kan'an No. 50 Tanah Baru - Bogor Utara, Kota Bogor</i></p>
            <p><i><a href="https://prioritas-group.com/">www.prioritas-group.com</a></i></p>
        </div>
    </div>
</body>
</html>