<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Video Bantuan & Informasi Prioritas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <style>
        .header {
            background: #ec2222;
            padding: 15px;
            font-weight: bold;
            text-align: center;
            color: white;
        }

        .divider {
        background: rgb(233, 233, 233);
        padding: 10px;
        width: 100%;
        color: rgb(75, 75, 75);
        border-top: 1px solid #cacaca
        }
        #tempatVideo {
            padding-top: 20px;
        }
        .footer {
            padding-top: 100px;
        }
    </style>
</head>
<body>
<div class="header">
    Bantuan
</div>
<div class="divider">
    Video Tutorial
</div>
<div class="container">

    <div class="row" id="tempatVideo">
        
    </div>
    <p class="text-center footer">
        <a href="https://prioritas-group.com">Powered by Prioritas Group</a>
    </p>
</div>

<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

<script>

	$.get('https://content.googleapis.com/youtube/v3/search?part=id,snippet&channelId=UCvtbw3Iq_AM7a18iRFqGC7w&maxResults=5&key=AIzaSyC459mh-NcKXk2_R_SS79L-mP2KT_X9xnQ', [], res=> {
	    res.items.forEach(item => {
	    	if(item.id.videoId){
		        $("#tempatVideo").append(`
				<div class="col-lg-4 col-md-6 col-12">
                    <div class="card">
                        <div class="video">
						<iframe src="https://www.youtube.com/embed/`+item.id.videoId+`" width="100%" height="200px" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                        </div>
                        <div class="card-body" width="100%">
							<p class="card-text">`+item.snippet.title+`</p>
						</div>
					</div>
				</div>`
				)
		    }
	    })
	})

</script>


</body>
</html>