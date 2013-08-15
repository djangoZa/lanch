var Order = function(options)
{
    this.comboId = options.comboId;
    this.guests = options.guests;
    this.waiters = options.waiters;
    this.formalDishes = options.formalDishes;
    this.time = options.time;
    this.date = options.date;
    this.address = options.address;
    this.notes = options.notes;
    this.extraEquipment = options.extraEquipment;
    this.equipmentBlackList = options.equipmentBlackList;
    this.pricePerPerson = options.pricePerPerson;
    this.customerDetails = options.customerDetails;
    this.total = options.total;
    this.discount = options.discount;
}

Order.prototype.populateInputs = function()
{
    $('#customerDetails').val(this.customerDetails);
    $("#formalDishes").val(this.formalDishes);
    $("#time").val(this.time);
    $('#date').val(this.date);
    $('#address').val(this.address);
    $('#notes').val(this.notes);
    $('#extraEquipment').val(this.extraEquipment);
    $('#pricePerPerson').val(this.pricePerPerson);
    $('#total').val(this.total);

    //uncheck blacklisted equipment
    $(this.equipmentBlackList).each(function(key, equipmentId){
        $("[id=equipment][data="+equipmentId+"]").attr('checked', false);
    });
}

Order.prototype.changeAmountOfGuests = function()
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

Order.prototype.updateTotals = function()
{
    $.ajax({
        url: "/order/get-order-session-ajax",
        dataType:'json'
    }).done(function(order){
        $('#total').val(Math.round(order.total * 100) / 100);
        $('#pricePerPerson').val(Math.round(order.pricePerPerson * 100) / 100);
    });
}

Order.prototype.saveOrderParams = function(callback)
{
    self = this;

    $('.order-input').each(function(key, input)
    {
        inputId = $(input).attr('id');
        inputValue = $('#'+inputId).val();
        
        switch(inputId)
        {
            case 'guests':
                self.changeAmountOfGuests();
                self[inputId] = $(input).val();
                break;
            case 'comboId':
            case 'size':
            case 'waiters':
            case 'formalDishes':
            case 'time':
            case 'date':
            case 'address':
            case 'notes':
            case 'extraEquipment':
            case 'pricePerPerson':
            case 'customerDetails':
            case 'total':
            case 'discount':
                self[inputId] = inputValue;
            case 'equipment':
                var blacklist = [];
            
                $("[id^=equipment]").each(function(key, checkbox)
                {
                    var equipmentId = $(checkbox).attr('data');

                    if($(checkbox).is(':checked') == false)
                    {
                        blacklist.push(equipmentId);
                    }
                });
            
                self.equipmentBlackList = blacklist;
                break;
        }
        
    });
 
    //persist the order
    $.ajax({
        url: "/order/set-order-session-ajax",
        data: {
            'order': 
            {
                comboId:self.comboId,
                size:self.size,
                guests: self.guests,
                waiters: self.waiters,
                formalDishes: self.formalDishes,
                time: self.time,
                date: self.date,
                address: self.address,
                notes: self.notes,
                extraEquipment: self.extraEquipment,
                equipmentBlackList: self.equipmentBlackList,
                pricePerPerson: self.pricePerPerson,
                customerDetails: self.customerDetails,
                total: self.total,
                discount: self.discount
            }
        }
    }).done(function(response)
    {
        self.updateTotals();
        
        if (callback != undefined)
        {
            callback();
        }
    });
}

$(document).ready(function()
{
    getOrderOptions(function(options)
    {
        var thisOrder = new Order(options);
        
        //populate inputs
        thisOrder.populateInputs();
        
        //save params
        thisOrder.saveOrderParams();
        
        //update the guests and waiters
        thisOrder.changeAmountOfGuests();
        
        
        $('.order-input').change(function()
        {
            thisOrder.saveOrderParams();
        });

        $('#confirmalo').bind('click', function()
        {
            thisOrder.saveOrderParams(function(){
                redirectToDetailsPage();
            });
        });
    });
});

function getOrderOptions(callback)
{
    $.ajax({
        url: "/order/get-order-session-ajax",
        dataType: "json"
    }).done(function(options){
        callback(options)
    });
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

function redirectToDetailsPage()
{
    window.location.href = "/completa-tus-datos";
}