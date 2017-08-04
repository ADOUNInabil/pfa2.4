var arrow_down = "{{ asset('templates/loading.gif') }}";
    function fonctiondetail(id){
        $.ajax({
            type:'get' ,
            url : 'http://localhost/pfa2.4/web/app_dev.php/machine/'+ id ,
            beforeSend : function () {

                var arrow_down = "{{ asset('templates/loading.gif') }}";
                document.getElementById("in").innerHTML="<img src=\"https://labmusiceducation.gr/sites/all/themes/bootstrap_subtheme/images/lab-loading.gif\" width=\"100px\" height=\"100px\" style='position: absolute;margin: auto;top: 0;right: 0;bottom: 0;left: 0;'>";




            },
            success : function (data) {
                setTimeout(function(){


                document.getElementById("in").innerHTML='<h3 align="center">'+data.nom+'</h3><br><iframe src='+data.lien+' frameborder="0" scrolling="no" marginheight="0" marginwidth="0" width="300" height="326" allowfullscreen></iframe>';
                }, 2000);
            }
        })
    };