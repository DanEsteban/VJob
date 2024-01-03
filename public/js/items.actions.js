function showToast() {
    var toastElList = [].slice.call(document.querySelectorAll('.toast'))
    var toastList = toastElList.map(function(toastEl) {
        return new bootstrap.Toast(toastEl)
    })
    toastList.forEach(toast => toast.show())    
}

function changeItem(objeto, items) {
    let code = $(objeto).val();

    function isMatch(item) {
        return item.item_name === code;
      }
    
    code = items.find(isMatch);
    let tr = $(objeto).parent().parent();

    let url = "/operations/item/code/"+code['id'];
    if(code){
        $.ajax({
            type: 'GET',
            url: url,
            dataType: 'json',
            async: false,
            data:{},
            error: function (xhr, status, error) {
                console.log(xhr.error);
            },
            success : function(data){
                tr.find('#description_production').val(data['sales_description']);
            }
        });
    }
    else{
        tr.find('#description_production').val(" ");
        tr.find('#qty').val(" ");
    }
}

function changeType() {
    var seleccion = $('#select_type option:selected').html();
    if(seleccion == "Service"){
        $('#div_numpart').attr('hidden', true);
        $('#br_unit').attr('hidden', true);
        $('#div_unit').attr('hidden', true);
        $('#div_description_p').attr('hidden', true);
        $('#lb_description_s').html('Description:');
        $('#div_costo').attr('hidden', true);
        $('#br_margin').attr('hidden', true);
        $('#div_margin').attr('hidden', true);
        $('#br_maxmin').attr('hidden', true);
        $('#div_maxmin').attr('hidden', true);
        $('#br_size').attr('hidden', true);
        $('#div_size').attr('hidden', true);
        $('#br_color').attr('hidden', true);
        $('#div_color').attr('hidden', true);
        $('#hr_assambly').attr('hidden', true);
        $('#div_assambly').attr('hidden', true);
        $('#br_assambly').attr('hidden', true);
        $('#btn_assambly').attr('hidden', true);
        $('#hr_codebar').attr('hidden', true);
        $('#div_codebar').attr('hidden', true);
        $('#div_process').removeAttr('hidden');
    }
    else if(seleccion == "Inventory Part"){
        $('#div_numpart').removeAttr('hidden');
        $('#br_unit').removeAttr('hidden');
        $('#div_unit').removeAttr('hidden');
        $('#div_description_p').removeAttr('hidden');
        $('#lb_description_s').html('Sales Description:');
        $('#div_costo').removeAttr('hidden');
        $('#br_margin').removeAttr('hidden');
        $('#div_margin').removeAttr('hidden');
        $('#br_maxmin').removeAttr('hidden');
        $('#div_maxmin').removeAttr('hidden');
        $('#br_size').removeAttr('hidden');
        $('#div_size').removeAttr('hidden');
        $('#br_color').removeAttr('hidden');
        $('#div_color').removeAttr('hidden');
        $('#div_process').attr('hidden', true);
        $('#hr_assambly').attr('hidden', true);
        $('#div_assambly').attr('hidden', true);
        $('#br_assambly').attr('hidden', true);
        $('#btn_assambly').attr('hidden', true);
        $('#hr_codebar').removeAttr('hidden');
        $('#div_codebar').removeAttr('hidden');
    }
    else if(seleccion == "Assembly Item"){
        $('#div_numpart').attr('hidden', true);
        $('#br_unit').attr('hidden', true);
        $('#div_unit').attr('hidden', true);
        $('#div_description_p').attr('hidden', true);
        $('#lb_description_s').html('Description:');
        $('#div_costo').attr('hidden', true);
        $('#br_margin').attr('hidden', true);
        $('#div_margin').attr('hidden', true);
        $('#br_maxmin').attr('hidden', true);
        $('#div_maxmin').attr('hidden', true);
        $('#br_size').attr('hidden', true);
        $('#div_size').attr('hidden', true);
        $('#br_color').attr('hidden', true);
        $('#div_color').attr('hidden', true);
        $('#div_process').attr('hidden', true);
        $('#hr_assambly').removeAttr('hidden');
        $('#div_assambly').removeAttr('hidden');
        $('#br_assambly').removeAttr('hidden');
        $('#btn_assambly').removeAttr('hidden');
        $('#hr_codebar').removeAttr('hidden');
        $('#div_codebar').removeAttr('hidden');
    }
    else{
        $('#div_numpart').removeAttr('hidden');
        $('#br_unit').removeAttr('hidden');
        $('#div_unit').removeAttr('hidden');
        $('#div_description_p').attr('hidden', true);
        $('#lb_description_s').html('Description:');
        $('#div_costo').attr('hidden', true);
        $('#br_margin').attr('hidden', true);
        $('#div_margin').attr('hidden', true);
        $('#br_maxmin').attr('hidden', true);
        $('#div_maxmin').attr('hidden', true);
        $('#br_size').attr('hidden', true);
        $('#div_size').attr('hidden', true);
        $('#br_color').attr('hidden', true);
        $('#div_color').attr('hidden', true);
        $('#div_process').attr('hidden', true);
        $('#hr_assambly').attr('hidden', true);
        $('#div_assambly').attr('hidden', true);
        $('#br_assambly').attr('hidden', true);
        $('#btn_assambly').attr('hidden', true);
        $('#hr_codebar').attr('hidden', true);
        $('#div_codebar').attr('hidden', true);
    }
}

function newGroup() {
    var seleccion = $('#select_group option:selected').html();
    if(seleccion == "------------(New)------------"){
        $("#createGroup").modal("show");
        document.getElementById("select_group").options[0].selected = "selected";
    }
}

function newUnit() {
    var seleccion = $('#select_unity option:selected').html();
    if(seleccion == "------------(New)------------"){
        $("#createUnit").modal("show");
        document.getElementById("select_unity").options[0].selected = "selected";
    }
}

function saveGroup() {
    let group = $('#createGroup #group_name').val();
    let url = '/operations/group/' + group;
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
            $('#select_group').append($('<option>', {
                value: data['id'],
                text: data['name']
            }));
            const selected = document.querySelector('#select_group');
            selected.value = data['id'];
            showToast();
        }
    });
}

function salir() {
    Swal.fire({
    title: 'Do you want to exit the form?',
    showDenyButton: true,
    confirmButtonText: 'Exit',
    denyButtonText: `Cancelar`,
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "/inventories";
        }
    })
}