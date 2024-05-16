$('#createCustomer').on('show.bs.modal', function (event) {
    $("#createCustomer input").val("");
    $("#createCustomer textarea").val("");
    $("#createCustomer select").val("");
    $("#createCustomer input[type='checkbox']").prop('checked', false).change();

    $('#createCustomer #cs_company').css('border','2px solid rgb(238, 238, 238)');
    $('#createCustomer #cs_phone').css('border','2px solid rgb(238, 238, 238)');
    $('#createCustomer #cs_billto').css('border','2px solid rgb(238, 238, 238)');
    $('#createCustomer #cs_shipto').css('border','2px solid rgb(238, 238, 238)');
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
    let url = "/elements/order/row";
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

function addRow2() {
    let count = $('#tb_items #tr_items').length;
    let url = "/elements/vendor/order/row";
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

function addRow3() {
    let count = $('#tb_items #tr_items').length;
    let url = "/elements/order/row/invoice";
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


function addRowInventory(objeto) {
    let table = $(objeto).parent().parent().parent();
    let tr = $(objeto).parent().parent();
    const clone = $(tr).clone();
    $(clone).children().find('button').remove();
    let button = '<button onclick ="delRowInventory(this);" type="button" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash-can"></i></button>';
    
    $(clone).children()[0].innerHTML = button;
    $(clone).find('td input').each(function () {
        $(this)[0].defaultValue = " ";
    });
    $(table).append($(clone)[0].outerHTML);
}

function deleteFile(id, params) {
    let tr = $(params).parent().parent();
        
    Swal.fire({
        title: 'Do you want to delete this file?',
        showDenyButton: true,
        confirmButtonText: 'Delete',
        denyButtonText: `Cancelar`,
        }).then((result) => {

            if (result.isConfirmed) {
                $.ajax({
                    type: "GET",
                    url: "/operations/customer/file/delete/"+id,
                    data:{},
                    error: function (xhr, status, error) {
                        console.log(xhr.responseText);
                    },
                    success: function (data) {
                        Swal.fire('Deleted!', '', 'success')
                        tr.closest('tr').remove();
                    }
                });
            }
    })
}

function delRow(object) {
    var filas = $('#tb_items #tr_items').length;
    if(filas > 1){
        $(object).parent().parent().next().remove();
        $(object).closest('tr').remove();
        calcular();
    }
}

function delRowInventory(objeto) {
    $(objeto).parent().parent().remove();
}

function calcular() {
    var subtotal = 0;
    var tax = 0;

    $("#tb_items #amt").each(function(){
        if($(this).val()){
            subtotal = subtotal + (parseFloat($(this).val().replace(',','')) * 1);
        }     
    })

    if ($('#order_tax').length) {
        let texto = $('#select_tax').find("option:selected").text();
        if(texto != "Choose Taxes"){
            let valor = texto.split('-')[1];
            valor =  parseFloat(valor.replace('%', ' ')) / 100;
            tax = valor * subtotal;
            $('#order_tax').val("$"+tax.toFixed(2));
            let total = subtotal + tax;
            $('#order_total').val("$"+total.toFixed(2));
        }
    }

    var resultado = new Intl.NumberFormat('en-US', {style: 'currency', currency: 'USD',}).format(subtotal);
    let total = 0;
    $('#order_subtotal').val(resultado);
    if ($('#order_tax').length) {
        total = subtotal + tax;
    }
    $('#order_total').val(total.toFixed(2));

    if($("#div_payment").length){
        $('#payment_total').val(total.toFixed(2));
    }
}

function cancelar() {
    let url = "/operations/shipto/delete";
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
            
        }
    });
}

function changeItem(objeto, items) {
    let div_next = $(objeto).parent().parent().next();
    let code = $(objeto).val();
    var selectedWarehouse = $('#select_warehouse option:selected').val();

    function isMatch(item) {
        return item.item_name === code;
    }
    
    code = items.find(isMatch);
    let tr = $(objeto).parent().parent();
    console.log(code);
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

    if(code){
        let url = "/operations/item/code/" + code['id'];        
        $.ajax({
            type: 'GET',
            url: url,
            dataType: 'json',
            async: false,
            data:{selectedWarehouse:selectedWarehouse},
            error: function (xhr, status, error) {
                console.log(xhr.error);
            },
            success : function(data){
                console.log(data);
                tr.find('#description').val(data['sales_description']);
                tr.find('#unit').val(data['id_unit_measure']);
                tr.find('#existencia').val(data['qty']);
                $("#stock").val(data['qty']);
                tr.find('#qty').val("1");
                tr.find('#price_real').val(data['price']);

                let porcentage = parseFloat($("#porcentaje").val())/(100);
                if (isNaN(porcentage)) {
                    
                    tr.find('#price').val(data['price']);
                    tr.find('#amt').val(data['price']);

                } else{

                    let price = parseFloat(data['price']);
                    let total = price+(price*porcentage);
                    tr.find('#price').val(total.toFixed(2));
                    tr.find('#amt').val(total.toFixed(2));
                }
            
                $(div_next).find('#collapse_container div').remove();
                $(div_next).find('#collapse_container hr').remove();
                

                calcular();  
            }
        });
    }
    else{
        code = $(objeto).val();
        if(code){
            $.ajax({
                type:'GET',
                dataType:'json',
                url:'/operations/item/codebar/' +  code,
                async:false,
                data:{},
                error: function (xhr, status, error) {
                    console.log(xhr.error);
                },
                success : function(any){
                    tr.find('#description').val(any['sales_description']);
                    tr.find('#unit').val(any['id_unit_measure']);
                    tr.find('#existencia').val(data['qty']);
                    $("#stock").val(data['qty']);
                    tr.find('#qty').val("1");
                    tr.find('#price_real').val(any['price']);
                    tr.find('#price').val(any['price']);
                    tr.find('#amt').val(any['price']);
                    $(div_next).find('#collapse_container div').remove();
                    $(div_next).find('#collapse_container hr').remove();
                    let porcentage = parseFloat($("#porcentaje").val())/(100);
                    if (isNaN(porcentage)) {
                    
                        tr.find('#price').val(data['price']);
                        tr.find('#amt').val(data['price']);

                    } else{

                        let price = parseFloat(data['price']);
                        let total = price+(price*porcentage);
                        tr.find('#price').val(total.toFixed(2));
                        tr.find('#amt').val(total.toFixed(2));
                    }
                    calcular();
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
            tr.find('#price_real').val(" ");
            tr.find('#price').val(" ");
            tr.find('#amt').val(" ");
            calcular();
        }
    }
}

function changeItem2(objeto, items) {
    let div_next = $(objeto).parent().parent().next();
    let code = $(objeto).val();
    var selectedWarehouse = $('#select_warehouse option:selected').val();
    
    function isMatch(item) {
        return item.item_name === code;
    }
    
    code = items.find(isMatch);
    let tr = $(objeto).parent().parent();

    if(code){
        let url = "/operations/item/code/" + code['id'];        
        $.ajax({
            type: 'GET',
            url: url,
            dataType: 'json',
            async: false,
            data:{selectedWarehouse:selectedWarehouse},
            error: function (xhr, status, error) {
                console.log(xhr.error);
            },
            success : function(data){
                tr.find('#description').val(data['sales_description']);
                tr.find('#unit').val(data['id_unit_measure']);
                tr.find('#qty').val("1");
                $(div_next).find('#collapse_container div').remove();
                $(div_next).find('#collapse_container hr').remove();
                calcular();
            }
        });
    }
    else{
        code = $(objeto).val();
        if(code){
            $.ajax({
                type:'GET',
                dataType:'json',
                url:'/operations/item/codebar/' +  code,
                async:false,
                data:{},
                error: function (xhr, status, error) {
                    console.log(xhr.error);
                },
                success : function(any){
                    tr.find('#description').val(any['sales_description']);
                    tr.find('#unit').val(any['id_unit_measure']);
                    tr.find('#qty').val("1");
                    tr.find('#price').val(any['price']);
                    tr.find('#amt').val(any['price']);
                    $(div_next).find('#collapse_container div').remove();
                    $(div_next).find('#collapse_container hr').remove();
                    calcular();
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
}

function porcentage(){
    let porcent = $("#porcentaje").val();
    let cellsreal = $("td #price_real");
    let cells = $("td #price");
    
    console.log(porcent)

    // Iterar a través de las celdas y reemplazar los valores
    cellsreal.each(function(){
        let currentValue = $(this).val();

    });
    cells.each(function() {

        let tr = $(this).parent().parent();
        let currentValue = tr.find('#price_real').val();
        console.log(tr);
        let qty = parseFloat(tr.find('#qty').val()) * 1;
        let subtotal = 0;

        if(currentValue != ""){
            if (porcent === "") {
                let newprice = $(this).val((parseFloat(currentValue)).toFixed(2));
                subtotal = qty * newprice.val();
                tr.find('#amt').val((parseFloat(subtotal)).toFixed(2));
            }
            else{
                let precioFinal= ((parseFloat(porcent)+100)/100)*parseFloat(currentValue);
                let newValue = currentValue.replace(currentValue, precioFinal);
                let newprice = $(this).val((parseFloat(newValue)).toFixed(2));
                subtotal = qty * newprice.val();
                tr.find('#amt').val((parseFloat(subtotal)).toFixed(2));
            }
        }     
    });

    calcular();
}

function precioReal(objeto) {
    //let tr = $(objeto).parent().parent();
    $("#tb_items #tr_items").each(function(){
        $('#price_real').val(objeto);  
    });

    calcular();

}

function changePrice(objeto) {
    let tr = $(objeto).parent().parent();
    let price = parseFloat($(objeto).val()) * 1;
    let qty = parseFloat(tr.find('#qty').val()) * 1;
    let subtotal = 0;
    if(price && qty){
        subtotal = qty * price;
        tr.find('#amt').val(subtotal.toFixed(3));
        calcular();
    }
    else{
        tr.find('#amt').val("0.00");
        calcular();
    }
}

// function changeQty(objeto) {
//     let tr = $(objeto).parent().parent();
//     let qty = parseFloat($(objeto).val()) * 1;
//     let price = parseFloat(tr.find('#price').val()) * 1;
//     let subtotal = 0;
//     if(qty && price){
//         subtotal = qty * price;
//         tr.find('#amt').val(subtotal.toFixed(2));
//         calcular();
//     }
//     else{
//         tr.find('#amt').val("0.00");
//         calcular();
//     }

// }

function newCustomer() {
    var seleccion = $('#select_customer').val();
    if(seleccion == "------------(New)------------"){
        $("#createCustomer").modal("show");
        $('#select_customer').val(" ");
    }
    else{
        selectCustomers();
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
                if(data['address'] == null){
                    data['address'] = "";
                }

                if(data['company'] == null){
                    data['company'] = "";
                }

                if(data['city'] == null){
                    data['city'] = "";
                }

                if(data['postal'] == null){
                    data['postal'] = "";
                }

                if(data['state'] == null){
                    data['state'] = "";
                }

                const selected = document.querySelector('#shipto');
                selected.value = data['address']+"\n"+ data['company']+"\n"+ data['city']+"\n"+ data['postal']+"\n"+ data['state'];
            }
        });
    }
}

function newShipToModal() {
    var seleccion = $('#createCustomer #select_shipto_model option:selected').html();
    let id = $('#createCustomer #select_shipto_model option:selected').val();
    if(seleccion == "------------(New)------------"){
        $("#createShipToModal").modal("show");
        document.getElementById("select_shipto_model").options[0].selected = "selected";
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
                const selected = document.querySelector('#createCustomer #cs_shipto');
                selected.value = data['address'];
            }
        });
    }
}

function newTerm() {
    var seleccion = $('#select_term option:selected').html();
    if(seleccion == "------------(New)------------"){
        $("#createTerms").modal("show");
        document.getElementById("select_term").options[0].selected = "selected";
    }

    if (seleccion == "Cash") {
        $("#payment_amount").removeAttr('disabled');
        $("#payment_total").removeAttr('disabled');
    }
    else{
        $("#payment_amount").attr('disabled', true);
        $("#payment_total").attr('disabled', true);
        $("#payment_amount").val('');
        $("#payment_total").val('$0.00');
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
                window.location.href = "/orders";
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

function selectCustomers() {
    $('#select_shipto option').remove();
    $('#select_shipto').append($('<option>', {
        value: '',
        text: ''
    }));

    $('#select_shipto').append($('<option>', {
        value: '0',
        text: '------------(New)------------'
    }));

    let customer = $('#select_customer').val();

    if (customer) {
        var opt = $('option[value="'+customer+'"]');
        let id = opt.attr('id');

        if(id){
            let url = "/operations/customer/"+id;
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
        
            url = "/operations/shipto/"+id;
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
                    if(data.length > 0){
                        data.forEach(element => {
                            $('#select_shipto').append($('<option>', {
                                value: element['id'],
                                text: element['name']
                            }));
                        });
                        const selected = document.querySelector('#select_shipto');
                        selected.value = data[0]['id'];
                        if(data[0]['address'] == null){
                            data[0]['address'] = "";
                        }
        
                        if(data[0]['company'] == null){
                            data[0]['company'] = "";
                        }
        
                        if(data[0]['city'] == null){
                            data[0]['city'] = "";
                        }
        
                        if(data[0]['postal'] == null){
                            data[0]['postal'] = "";
                        }
        
                        if(data[0]['state'] == null){
                            data[0]['state'] = "";
                        }
                        $('#shipto').val(data[0]['address'] +"\n"+ data[0]['company'] +"\n"+ data[0]['city'] +"\n"+ data[0]['postal'] +"\n"+ data[0]['state']);
                    }
                }
            });
        }
        else{
            let url = "/operations/customer/name/"+customer;
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
                    if (data != null) {
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
                }
            });

            url = "/operations/shipto/"+id;
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
                    if(data.length > 0){
                        data.forEach(element => {
                            $('#select_shipto').append($('<option>', {
                                value: element['id'],
                                text: element['name']
                            }));
                        });
                        const selected = document.querySelector('#select_shipto');
                        selected.value = data[0]['id'];
                        if(data[0]['address'] == null){
                            data[0]['address'] = "";
                        }
        
                        if(data[0]['company'] == null){
                            data[0]['company'] = "";
                        }
        
                        if(data[0]['city'] == null){
                            data[0]['city'] = "";
                        }
        
                        if(data[0]['postal'] == null){
                            data[0]['postal'] = "";
                        }
        
                        if(data[0]['state'] == null){
                            data[0]['state'] = "";
                        }
                        $('#shipto').val(data[0]['address'] +"\n"+ data[0]['company'] +"\n"+ data[0]['city'] +"\n"+ data[0]['postal'] +"\n"+ data[0]['state']);
                    }              
                }
            });
        }
    } 
    else {
        $('#billto').val(" ");
        $('#shipto').val(" ");
        $('#phone').val(" ");
        $('#email').val(" ");
        const selected = document.querySelector('#select_term');
        selected.value = "";

        $('#select_shipto option').remove();
        $('#select_shipto').append($('<option>', {
            value: '',
            text: ''
        }));
    }
}

function showOptions(objeto, option) {
    if(option == 1){
        if(objeto.checked){
            $(objeto).parent().next().removeAttr('hidden');
            let message = $(objeto).attr('data-message');
            const ArrayMessage = message.split('-');
            if(ArrayMessage[0] != "null"){
                Swal.fire('Information', ArrayMessage[0], 'info')
            }
        }
        else{
            $(objeto).parent().next().attr('hidden', true);
            $(objeto).parent().next().find('input').val(null);

            let message = $(objeto).attr('data-message');
            const ArrayMessage = message.split('-');
            if(ArrayMessage[1] != "null"){
                Swal.fire('Information', ArrayMessage[1], 'info')
            }
        }
    }
    else{
        if(objeto.checked){
            $(objeto).parent().next().removeAttr('hidden');
            $(objeto).parent().next().next().removeAttr('hidden');

            let message = $(objeto).attr('data-message');
            const ArrayMessage = message.split('-');
            if(ArrayMessage[0] != "null"){
                Swal.fire('Information', ArrayMessage[0], 'info')
            }
        }
        else{
            $(objeto).parent().next().attr('hidden', true);
            $(objeto).parent().next().next().attr('hidden', true);
            let table = $(objeto).parent().next().next().children().children()[1];
            $(table).find('tr:gt(0)').remove();
            $(table).find('tr input').val(null);

            let message = $(objeto).attr('data-message');
            const ArrayMessage = message.split('-');
            if(ArrayMessage[1] != "null"){
                Swal.fire('Information', ArrayMessage[1], 'info')
            }
        }
    }
}

function taxes(objeto) {
    let texto = $(objeto).find("option:selected").text();
    
    if(texto != "Choose Taxes"){
        let valor = texto.split('-')[1];
        valor =  parseFloat(valor.replace('%', ' ')) / 100;
        console.log(valor);
        let subtotal = parseFloat($('#order_subtotal').val().replace(',', '').replace('$', ' '));
        console.log(subtotal);
        let tax = valor * subtotal;
        $('#order_tax').val("$"+tax.toFixed(2));
        let total = subtotal + tax;
        $('#order_total').val("$"+total.toFixed(2));
    }
    else{
        let subtotal = parseFloat($('#order_subtotal').val().replace('$', ' '));
        $('#order_tax').val("$0.00");
        $('#order_total').val("$"+subtotal);
    }
}


function validateCliente() {
    let cliente = $('#select_customer').val();
    let rows = $('#tb_items tr').length;
    if(cliente){
        $.ajax({
            type:'GET',
            dataType:'json',
            url:'/operations/customer/name/' + cliente,
            async:false,
            data:{},
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
            },
            success : function(data){
               if(data == null){
                Swal.fire(
                    'Warning',
                    'Select a valid customer',
                    'warning'
                  )
               }
               else if(rows == 0){
                Swal.fire(
                    'Warning',
                    'Please choose products and add them to the list',
                    'warning'
                  )
               }
               else{
                $('#doc_form').submit();
               }
            }
        });
    }
}
