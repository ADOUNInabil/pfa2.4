var arrow_down = "{{ asset('templates/loading.gif') }}";
    function fonctiondetail(id){
        $.ajax({
            type:'get' ,
            url : 'http://localhost/pfa2.4/web/app_dev.php/machine/'+ id ,
            beforeSend : function () {

                var arrow_down = "{{ asset('templates/loading.gif') }}";
                document.getElementById("in").innerHTML='<img src="'+arrow_down+'"/>';




            },
            success : function (data) {
                setTimeout(function(){


                document.getElementById("in").innerHTML='<h3 align="center">'+data.nom+'</h3><br><iframe src='+data.lien+' frameborder="0" scrolling="no" marginheight="0" marginwidth="0" width="300" height="326" allowfullscreen></iframe>';
                }, 2000);
            }
        })
    };