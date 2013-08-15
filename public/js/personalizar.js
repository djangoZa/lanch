$(document).ready(function(){

    //When the user lands on the personal product selection page, check the selected products
    if (isOnPersonalProductSelectionPage()) {
        checkSelectedPersonalProducts();
    }

    //When the user clicks on an input, handle accordingly
    $('input[type="checkbox"]').bind('click', function()
    {
        savePersonalProductSelection();
    });
});

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

function savePersonalProductSelection()
{
    var comboId = getComboId();
    var checkedProductIds = getCheckedProductIds();

    $.ajax({
        url: "/elegi-el-servico/save-personal-product-selection-ajax",
        data: {
            'comboId':comboId,
            'checkedProductIds':checkedProductIds
        },
    }).done(function(){
        if (!isOnPersonalProductSelectionPage()) {
            var comboId = getComboId();
            window.location.href = '/elegi-el-servico/personalizar?comboId='+comboId+'&size=personal';
        }
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

function changeAmountOfGuests()
{
    var minGuests = $("#minimimGuests").val();
    var guests = $('#guests').val();
    
    if (Number(guests) < Number(minGuests)) 
    {
        showToolTipMessage('guests', 'We changed the amount of guests because this combo requires a minimum of '+minGuests);
        $('#guests').val(minGuests);
        $('#guests').trigger('change');
    }
}

function showToolTipMessage(inputName, message)
{
    inputId = "#" + inputName;
    
    $(inputId).tooltip(
    {
        title: message,
        animation: true,
        placement: 'top',
        trigger: 'manual',
    });

    $(inputId).tooltip('show');
}

function saveGuests()
{
    var guests = $('#guests').val();
    $.ajax({
        url: "/order/set-order-guests-ajax",
        data: {
            'guests':guests
        },
    });
}