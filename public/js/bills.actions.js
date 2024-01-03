$('#createVendor').on('show.bs.modal', function (event) {
    $("#createVendor input").val("");
    $("#createVendor textarea").val("");
    $("#createVendor select").val("");
    $("#createVendor input[type='checkbox']").prop('checked', false).change();

    $('#createVendor #cs_company').css('border','2px solid rgb(238, 238, 238)');
    $('#createVendor #cs_phone').css('border','2px solid rgb(238, 238, 238)');
    $('#createVendor #cs_billto').css('border','2px solid rgb(238, 238, 238)');
});

function showToast() {
    var toastElList = [].slice.call(document.querySelectorAll('.toast'))
    var toastList = toastElList.map(function(toastEl) {
        return new bootstrap.Toast(toastEl)
    })
    toastList.forEach(toast => toast.show())    
}


function addRow() {
    let count = $('#tb_items #tr_items').length;
    let url = "/elements/bill/row";
        $.ajax({
            type: 'GET',
            url: url,
            dataType: 'html',
            async: 'false',
            data:{count:count},
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
            },
            success : function(data){
               $('#tb_items').append(data)
            }
        });
}

function delRow(object) {
    var filas = $('#tb_items #tr_items').length;
    if(filas > 1){
        $(object).parent().parent().next().remove();
        $(object).closest('tr').remove();
        calcular();
    }
}

function calcular() {
    var subtotal = 0;

    $("#tb_items #amt").each(function(){
        if($(this).val()){
            subtotal = subtotal + (parseFloat($(this).val().replace(',','')) * 1);
        }     
    })

    var resultado = new Intl.NumberFormat('en-US', {style: 'currency', currency: 'USD',}).format(subtotal);
    $('#bill_total').val(resultado);
}

function changeItem(objeto, items, types) {
    let div_next = $(objeto).parent().parent().next();
    let code = $(objeto).val();
    let tr = $(objeto).parent().parent();
    var colors;
    var sizes;

    $.ajax({
        type: 'GET',
        url: '/operations/colors',
        dataType: 'json',
        async: false,
        data:{},
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        },
        success: function (color) {
           if(color){
                colors = color;
           }
        }
    });

    $.ajax({
        type: 'GET',
        url: '/operations/sizes',
        dataType: 'json',
        async: false,
        data:{},
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        },
        success: function (size) {
           if(size){
                sizes = size;
           }
        }
    });


    let url = "/operations/item/code/"+code;
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
                tr.find('#description').val(data['sales_description']);
                tr.find('#unit').val(data['id_unit_measure']);
                tr.find('#price').val(data['price']);
                $(div_next).find('#collapse_container div').remove();
                $(div_next).find('#collapse_container hr').remove();
                
                if(data['id_process']){
                    url = "/operations/process/condition/"+data['id_process'];
                    $.ajax({
                        type: 'GET',
                        url: url,
                        dataType: 'json',
                        async: false,
                        data:{},
                        error: function (xhr, status, error) {
                            console.log(xhr.responseText);
                        },
                        success : function(data){
                            let td_button = $(objeto).parent().prev().prev();
                            let td_false = $(objeto).parent().prev().prev().prev();

                            $(td_false).attr('hidden', true);
                            $(td_button).html("+");
                            $(td_button).addClass("btnplus");
                            $(td_button).removeAttr('hidden');

                            for (let index = 0; index < data.length; index++) {
                                let element = data[index];
                                let message_yes = element['Condition']['message_yes'];
                                let message_no = element['Condition']['message_no'];

                                if(element['stage']['has_attachment_customer'] == 1){                         
                                    let checkbox = '<div class="form-check form-switch">'+
                                                        '<input class="form-check-input" type="checkbox" onchange="showOptions(this, 1);" id="attach_check" data-message="'+ message_yes +'-'+ message_no +'">'+
                                                        '<label class="form-check-label">'+ element['Condition']['question'] +'</label>'+
                                                    '</div>';
                                    
                                    let option = '<div class="row" id="row_attach" hidden>'+
                                                        '<div class="col-md-10">'+
                                                            '<div class="input-group">'+
                                                                '<label for="formFileMultiple" class="col-sm-2 col-form-label form-label">Customer Files:</label>'+
                                                                '<input name="customer_files[]" class="form-control" type="file" id="formFileMultiple" style="width: 500px" tabindex="6" multiple>'+
                                                            '</div>'+
                                                        '</div>'+
                                                 '</div>';

                                    $(div_next).find('#collapse_container').append(checkbox);
                                    $(div_next).find('#collapse_container').append(option);
                                }
                                
                                if(element['stage']['has_inventory_received'] == 1){
                                    let checkbox = '<br><div class="form-check form-switch">'+
                                                        '<input class="form-check-input" type="checkbox" onchange="showOptions(this, 2);" id="inventory_check" data-message="'+ message_yes +'-'+ message_no +'">'+
                                                        '<label class="form-check-label">'+ element['Condition']['question'] +'</label>'+
                                                    '</div>';

                                    let option = '<hr id="hr_inventory" hidden>'+
                                                 '<div class="row" id="row_inventory" hidden>'+
                                                        '<table class="table table-sm table-hover bg-dark">'+
                                                            '<thead>'+
                                                                '<tr>'+
                                                                    '<th width="4%"></th>'+
                                                                    '<th width="15%">Code</th>'+
                                                                    '<th>Description</th>'+
                                                                    '<th>Size</th>'+
                                                                    '<th>Color</th>'+
                                                                    '<th width="10%">Qty</th>'+
                                                                '</tr>'+
                                                            '</thead>'+
                                                            '<tbody id="tb_inventory">'+
                                                                '<tr>'+
                                                                    '<td><button onclick ="addRowInventory(this);" type="button" class="btn btn-sm btn-primary"><i class="fa-solid fa-plus"></i></button></td>'+
                                                                    '<td>'+
                                                                        '<input onchange="changeItem2(this, this.value)" id="code_inv" name="code_inv[]" type="text" autocomplete="off" class="form-control form-control-sm" width="300px" list="itemsListFilter">'+
                                                                        '<datalist id="itemsListFilter">';                                                                      
                                                                           for(let index = 0; index < items.length; index++) {
                                                                                if (items[index]['id_type'] == types["id"]){
                                                                                  option += '<option value="' + items[index]["item_name"] +'"></option>';
                                                                                }                                                          
                                                                           }
                                        option +=                       '</datalist>'+
                                                                    '</td>'+
                                                                    '<td><input id="description_inv" name="description_inv[]" type="text" class="form-control form-control-sm"></td>'+
                                                                    '<td>'+
                                                                        '<select id="size_inv" name="size_inv[]" class="form-select form-select-sm" aria-label=".form-select-sm">'+
                                                                               '<option value="0" selected disabled>Choose an option</option>';
                                                                           for (let index = 0; index < sizes.length; index++) {
                                                                                option += '<option value="' + sizes[index]["id"] +'">'+ sizes[index]["description"] +'</option>';                
                                                                           }
                                        option +=                       '</select>'+
                                                                    '</td>'+
                                                                    '<td>'+
                                                                        '<select id="color_inv" name="color_inv[]" class="form-select form-select-sm" aria-label=".form-select-sm">'+
                                                                                '<option value="0" selected disabled>Choose an option</option>';
                                                                            for (let index = 0; index < colors.length; index++) {
                                                                                option += '<option value="' + colors[index]["id"] +'">'+ colors[index]["description"] +'</option>';                
                                                                            }
                                        option +=                       '</select>'+
                                                                    '</td>'+
                                                                    '<td><input onkeyup="changeQty(this);" id="qty_inv" name="qty_inv[]" type="text" class="form-control form-control-sm"></td>'+
                                                                '</tr>'+
                                                            '</tbody>'+
                                                        '</table>'+
                                                    '</div>';

                                    $(div_next).find('#collapse_container').append(checkbox);
                                    $(div_next).find('#collapse_container').append(option);
                                }
                            }
                        }
                    });
                }
                else{
                    if(data['id_type'] == 2){
                            let td_button = $(objeto).parent().prev().prev();
                            let td_false = $(objeto).parent().prev().prev().prev();

                            $(td_false).attr('hidden', true);
                            $(td_button).html("+");
                            $(td_button).addClass("btnplus");
                            $(td_button).removeAttr('hidden');

                            let title = '<div class="row">'+
                                            '<h3>Additional Information</h3>'+
                                        '</div><hr>';

                            let option = '<div class="row">'+
                                            '<div class="col-md-10">'+
                                                '<div class="input-group">'+
                                                    '<label class="col-sm-2 col-form-label form-label">Item Size</label>'+
                                                         '<select id="select_size" name="select_size[]" class="form-select form-select-sm" aria-label=".form-select-sm">'+
                                                                '<option value="0" selected>Choose an option</option>';
                                                                for (let index = 0; index < sizes.length; index++) {
                                                                    option += '<option value="' + sizes[index]["id"] +'">'+ sizes[index]["description"] +'</option>';                
                                                                }
                                option +=                '</select>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div><br>';

                                option += '<div class="row">'+
                                            '<div class="col-md-10">'+
                                                '<div class="input-group">'+
                                                    '<label class="col-sm-2 col-form-label form-label">Item Color</label>'+
                                                        '<select id="select_color" name="select_color[]" class="form-select form-select-sm" aria-label=".form-select-sm">'+
                                                                '<option value="0" selected>Choose an option</option>';
                                                                for (let index = 0; index < colors.length; index++) {
                                                                    option += '<option value="' + colors[index]["id"] +'">'+ colors[index]["description"] +'</option>';                
                                                                }
                                option +=               '</select>'+
                                                '</div>'+
                                            '</div>'+
                                    '</div>';

                            $(div_next).find('#collapse_container').append(title);
                            $(div_next).find('#collapse_container').append(option);
                    }
                }
            }
        });
    }
    else{
        let td_button = $(objeto).parent().prev().prev();
        let td_false = $(objeto).parent().prev().prev().prev();
        $(td_false).removeAttr('hidden');
        $(td_button).attr('hidden', true);
        $(div_next).collapse("hide");
        $(td_button).removeClass("btnminus");
        $(td_button).addClass("btnplus");

        tr.find('#description').val(" ");
        tr.find('#qty').val(" ");
        tr.find('#unit').val(" ");
        tr.find('#price').val(" ");
        tr.find('#amt').val(" ");
    }
}

function changePrice(objeto) {
    let tr = $(objeto).parent().parent();
    let price = parseFloat($(objeto).val()) * 1;
    let qty = parseFloat(tr.find('#qty').val()) * 1;
    let subtotal = 0;
    if(price && qty){
        subtotal = qty * price;
        tr.find('#amt').val(subtotal);
        calcular();
    }
    else{
        tr.find('#amt').val("0.00");
        calcular();
    }
}

function changeQty(objeto) {
    let tr = $(objeto).parent().parent();
    let qty = parseFloat($(objeto).val()) * 1;
    let price = parseFloat(tr.find('#price').val()) * 1;
    let subtotal = 0;
    if(qty && price){
        subtotal = qty * price;
        tr.find('#amt').val(subtotal);
        calcular();
    }
    else{
        tr.find('#amt').val("0.00");
        calcular();
    }

}

function newVendor() {
    var seleccion = $('#select_vendor').val();
    if(seleccion == "------------(New)------------"){
        $("#createVendor").modal("show");
        $('#select_vendor').val(" ");
    }
    else{
        selectVendors();
    }
}

function newTerm() {
    var seleccion = $('#select_term option:selected').html();
    if(seleccion == "------------(New)------------"){
        $("#createTerms").modal("show");
        document.getElementById("select_term").options[0].selected = "selected";
    }
}

function salir() {
    Swal.fire({
        title: 'Do you want to exit the form?',
        showDenyButton: true,
        confirmButtonText: 'Exit',
        denyButtonText: `Cancelar`,
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "/bills";
            }
        })
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
            $('#select_term').append($('<option>', {
                value: data['id'],
                text: data['name']
            }));
            const selected = document.querySelector('#select_term');
            selected.value = data['id'];
            showToast();
        }
    });
}

function selectVendors() {
    let vendor = $('#select_vendor').val();

    if (vendor) {
        var opt = $('option[value="'+vendor+'"]');
        let id = opt.attr('id');

        if(id){
            let url = "/operations/vendor/"+id;
            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'json',
                async: 'false',
                data:{},
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                },
                success : function(data){
                    if(data['billto_street'] == null){
                        data['billto_street'] = "";
                    }
    
                    if(data['billto_company'] == null){
                        data['billto_company'] = "";
                    }
    
                    if(data['billto_city'] == null){
                        data['billto_city'] = "";
                    }
    
                    if(data['billto_postal'] == null){
                        data['billto_postal'] = "";
                    }
    
                    if(data['billto_state'] == null){
                        data['billto_state'] = "";
                    }

                    $('#billto').val(data['billto_street']+"\n"+data['billto_company']+"\n"+data['billto_city']+"\n"+data['billto_postal']+"\n"+data['billto_state']);
                    $('#phone').val(data['phone']);
                    $('#email').val(data['email']);
                    const selected = document.querySelector('#select_term');
                    selected.value = data['id_terms'];
                }
            });
        }
        else{
            let url = "/operations/vendor/name/"+vendor;
            let id = 0;
            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'json',
                async: 'false',
                data:{},
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                },
                success : function(data){
                    if(data['billto_street'] == null){
                        data['billto_street'] = "";
                    }
    
                    if(data['billto_company'] == null){
                        data['billto_company'] = "";
                    }
    
                    if(data['billto_city'] == null){
                        data['billto_city'] = "";
                    }
    
                    if(data['billto_postal'] == null){
                        data['billto_postal'] = "";
                    }
    
                    if(data['billto_state'] == null){
                        data['billto_state'] = "";
                    }

                    $('#billto').val(data['billto_street']+"\n"+data['billto_company']+"\n"+data['billto_city']+"\n"+data['billto_postal']+"\n"+data['billto_state']);
                    $('#phone').val(data['phone']);
                    $('#email').val(data['email']);
                    id = data['id'];
                    const selected = document.querySelector('#select_term');
                    selected.value = data['id_terms'];
                }
            });
        }
    } 
    else {
        $('#billto').val(" ");
        $('#phone').val(" ");
        $('#email').val(" ");
        const selected = document.querySelector('#select_term');
        selected.value = "";
    }
}

  