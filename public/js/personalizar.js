function validateGuestInput()
{
    var minGuests = $("#minimimGuests").val();
    var guests = $('#guests').val();
    
    //if too few guests have been specified
    if (Number(guests) < Number(minGuests)) 
    {
        $('#guests').val(minGuests);
        //showToolTipMessage('guests', 'Guests modified');
        $('#guests').trigger('change');
    }
    
    //if number with a decimal point has been specified
    if (!isInt(guests))
    {
        $('#guests').val(Math.ceil(guests));
    }
}

function isInt(n)
{
   return n % 1 === 0;
}

function checkSelectedPersonalProducts()
{
    var comboId = getComboId();
    
    $.ajax({
        url: "/elegi-el-servico/get-personal-product-selection-ajax",
        data: {'comboId':comboId},
        dataType: 'json'
    }).done(function(productIds){
        $(productIds).each(function(key, productId){
            $('input[data="'+productId+'"]').attr('checked', 'checked');
        });
    });
}

function isOnPersonalProductSelectionPage()
{
    var out = false;
    var size = $('input[name="size"]').val();
    
    if (size == 'personal') {
        out = true;
    }
    
    return out;
}

function savePersonalProductSelection(order, callback)
{
    var comboId = getComboId();
    var checkedProductIds = getCheckedProductIds();

    $.ajax({
        url: "/elegi-el-servico/save-personal-product-selection-ajax",
        data: {
            'comboId':comboId,
            'checkedProductIds':checkedProductIds
        },
    }).done(function(response){
        console.log(response);
        callback(order);
    });
}

function getComboId()
{
    var comboId = $('input[name="comboId"]').val();
    return comboId;
}

function getCheckedProductIds()
{
    var out = new Array;

    $('input[type="checkbox"]:checked').each(function(key, value){
        out.push($(value).attr('data'));
    });
    
    return out;
}

//get the order options
function getOrderOptions(callback)
{
    $.ajax({
        url: "/order/get-order-session-ajax",
        dataType: "json"
    }).done(function(options){
        callback(options)
    });
}