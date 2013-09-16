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

    //uncheck blacklisted equipment
    $(this.equipmentBlackList).each(function(key, equipmentId){
        $("[id=equipment][data="+equipmentId+"]").attr('checked', false);
    });
}

Order.prototype.populatePersonalizeInputs = function()
{
    $("#formalDishes").val(this.formalDishes);
}

Order.prototype.validateGuestInput = function()
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
    if (!this.isInt(guests))
    {
        $('#guests').val(Math.ceil(guests));
    }
}

Order.prototype.isInt = function(n)
{
   return n % 1 === 0;
}

Order.prototype.validateWaiterInput = function(callback)
{
    var guestsPerWaiter = $("#guestsPerWaiter").val(); 
    var waiters = $('#waiters').val();
    var guests = $('#guests').val();
    var suggestedWaiters = Math.ceil(guests / guestsPerWaiter);
    
    if (Number(waiters) < Number(suggestedWaiters) || Number(waiters) > Number(suggestedWaiters)) {
        $('#waiters').val(suggestedWaiters);
        console.log('use suggested waiters');
    }
    
    if (callback != undefined)
    {
        callback(self);
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

Order.prototype.updateOrderTotalWithPersonalProducts = function(callback)
{
    var self = this;
    
    $.ajax({
        url: "/order/update-order-session-with-personal-products-ajax"
    }).done(function(response)
    {
        if (callback != undefined)
        {
            callback(self);
        }
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
            case 'tax':
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
                customerDetails: self.customerDetails,
                tax: self.tax,
                discount: self.discount
            }
        }
    }).done(function(response)
    {
        self.updateTotals();
        if (callback != undefined) {
            callback(self);
        }
    });
}

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
        trigger: 'manual'
    });

    $(inputId).tooltip('show');
    
    //remove the tooltip when the user clicks another input
    $('input').bind('click', function(){
        $('#guests').tooltip('hide');
        $('#waiters').tooltip('hide');
        $('form').unbind('click');
    });
}

function redirectToDetailsPage()
{
    window.location.href = "/completa-tus-datos";
}