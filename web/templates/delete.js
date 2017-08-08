function supprimernotif(id){
    swal({
        title: 'Supprimer?',
        text: "Supprimer la notification",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oui, supprimer!',
        cancelButtonText: 'Non, cancel!',
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
        buttonsStyling: false
    }).then(function () {
        $.ajax({
            type: 'get',
            url: 'http://localhost/pfa2.4/web/app_dev.php/notif/' + id,

            success: function (data) {
                // document.getElementById("rep").innerHTML="<div class='alert alert-warning alert-dismissible fade show' role='alert'> <button type='button' class='close' data-dismiss='alert' aria-label='Close'> <span aria-hidden='true'>&times;</span> </button> <strong>succès!</strong> notification effacée. </div>";
                //$("#rep").html("a<div class='alert alert-warning alert-dismissible fade show' role='alert'> <button type='button' class='close' data-dismiss='alert' aria-label='Close'> <span aria-hidden='true'>&times;</span> </button> <strong>succès!</strong> notification effacée. </div>");

                $("#tabdata").load(location.href + " #tabdata");

            }
        })
        swal(
            'Deleted!',
            'Your file has been deleted.',
            'success'
        )
    }, function (dismiss) {
        // dismiss can be 'cancel', 'overlay',
        // 'close', and 'timer'
        if (dismiss === 'cancel') {
            swal(
                'Cancelled',
                'suppression annulée',
                'error'
            )
        }
    })
};

