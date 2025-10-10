$(function () {
    var invalidChars = ["-", "e", "+", "E"];

    $("input[type='number']").on("keydown", function(e){
        if(invalidChars.includes(e.key)){
            e.preventDefault();
        }
    });
});

function msgBox(result,message){
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
    Toast.fire({
        icon: result,
        title: message
    });
}

function isDelete(callback) {
    Swal.fire({
        title: 'Are you sure you want to delete this record?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((confirmed) => {
        callback(confirmed && confirmed.value == true);
    });
}
