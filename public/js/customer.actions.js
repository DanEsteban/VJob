function showToast() {
    var toastElList = [].slice.call(document.querySelectorAll('.toast'))
    var toastList = toastElList.map(function(toastEl) {
        return new bootstrap.Toast(toastEl)
    })
    toastList.forEach(toast => toast.show())    
}

function salir() {
    Swal.fire({
    title: 'Do you want to exit the form?',
    showDenyButton: true,
    confirmButtonText: 'Exit',
    denyButtonText: `Cancelar`,
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "/customers";
        }
    })
}

function newTerm() {
    var seleccion = $('#select_payment option:selected').html();
    if(seleccion == "------------(New)------------"){
        $("#createTerms").modal("show");
        document.getElementById("select_payment").options[0].selected = "selected";
    }
}

function newDelivery() {
    var seleccion = $('#select_delivery option:selected').html();
    if(seleccion == "------------(New)------------"){
        $("#createDelivery").modal("show");
        document.getElementById("select_delivery").options[0].selected = "selected";
    }
}

function newShipTo() {
    var seleccion = $('#select_shipto option:selected').html();
    let id = $('#select_shipto option:selected').val();
    if(seleccion == "------------(New)------------"){
        $("#createShipTo").modal("show");
        document.getElementById("select_shipto").options[0].selected = "selected";
    }
    else{  
        let url = "/operations/shipto/address/"+id;
        $.ajax({
            type:'GET',
            url: url,
            dataType: 'json',
            async: "false",
            data: {},
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
            },
            success : function(data){
                $('#street_shipto').val(data['address']);
                $('#company_shipto').val(data['company']);
                $('#city_shipto').val(data['city']);
                $('#postal_shipto').val(data['postal']);
                $('#state_shipto').val(data['state']);
            }
        });
    }
}

function saveTerm() {
    let term = $('#createTerms #terms_name').val();
    let url = '/operations/term/' + term;
    $.ajax({
        type:'GET',
        url: url,
        dataType: 'json',
        async: "false",
        data: {},
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        },
        success : function(data){
            $('#select_payment').append($('<option>', {
                value: data['id'],
                text: data['name']
            }));
            const selected = document.querySelector('#select_payment');
            selected.value = data['id'];
            showToast();
        }
    });
}

function saveDelivery() {
    let delivery = $('#createDelivery #delivery_name').val();
    let url = '/operations/delivery/' + delivery;
    $.ajax({
        type:'GET',
        url: url,
        dataType: 'json',
        async: "false",
        data: {},
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        },
        success : function(data){
            $('#select_delivery').append($('<option>', {
                value: data['id'],
                text: data['name']
            }));
            const selected = document.querySelector('#select_delivery');
            selected.value = data['id'];
            showToast();
        }
    });
}
