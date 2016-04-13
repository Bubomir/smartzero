//jQuery('#myModal').modal('hide');
var ID_NULL = 'null';
//Inicialization function must run every time when page is laoded
(function() {
    

    //conter for iteration ID, add dropdown element 
    var counterElements = 0;
    //it will get all value from txt document
    getElementValue(counterElements, counterElements);
    document.getElementById('button_order-sz').addEventListener('click', function() {checkIfFilled(counterElements);});
    //document.getElementById("country-sz").selectedIndex = -1;
    // trim polyfill : https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/Trim
    if (!String.prototype.trim) {
        (function() {
            // Make sure we trim BOM and NBSP
            var rtrim = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;
            String.prototype.trim = function() {
                return this.replace(rtrim, '');
            };
        })();
    }
    //custom validation message
   /* document.getElementById('check-conditions').oninvalid = function(e) {
        e.target.setCustomValidity("");
        if (!e.target.validity.valid) {
            e.target.setCustomValidity("Musí");
        }
    }; */
    //for inputs
    [].slice.call(document.querySelectorAll('.input-group-sz input')).forEach(function(inputEl) {
        // in case the input is already filled..
        if (inputEl.value.trim() !== '') {
            inputEl.parentNode.classList.add('input-filled-sz');
        }
        // events:
        inputEl.addEventListener('focus', onInputFocus);
        inputEl.addEventListener('blur', onInputBlur);
    });
    //for select
    [].slice.call(document.querySelectorAll('.input-group-sz select')).forEach(function(inputEl) {
        // in case the input is already filled..
        if (inputEl.value.trim() !== '') {
            inputEl.parentNode.classList.add('input-filled-sz');
        }
        // events:
        inputEl.addEventListener('focus', onInputFocus);
        inputEl.addEventListener('blur', onInputBlur);
    });
    //Part for Custom Input of type number
    var actualNumber, newNumber;
    //Adding listeners for click event
    
    var dropDownElement =  document.getElementById('devicePicker-0').cloneNode(true);
    var billLinesElement = document.getElementById('id_contact-section-sz-0').cloneNode(true);  

    document.getElementById('addDropDown').addEventListener('click', function(){ 
        counterElements = addDropDown(dropDownElement, billLinesElement, counterElements);
    });
    document.getElementById('removeDropDown').addEventListener('click', function(){
        counterElements = removeDropDown(counterElements);
    });

    addListernerDropDown(counterElements);


})();

function getElementValue(product_id,counterElements) {
    var ID_NULL = 0;
    var ID_VYBERTE_MOZNOST = '-- Vyberte si jednu z možností --';
    
        var dropdown_object = document.getElementById('id-device_model-'+(counterElements));
        if (product_id != ID_NULL) {
            
            //ajax for getting data 
            jQuery.ajax({
                url: 'wp-content/plugins/contact_form/ajax_device_model.php',
                data: {
                    categoryID: product_id
                },
                type: 'post',
                dataType: 'json',
                success: function(output) {
                    getDevicesModel(output);
                }
            });

            function getDevicesModel (data){
                //remove previout option element

                for (var i = dropdown_object.length - 1; i >= 0; i--) {
                    dropdown_object.remove(i);
                }
                //create new element by parsing from .txt -> php
                for (var i = data.length - 1; i >= 0; i--) {
                    
                    var option = document.createElement("option");
                    option.value = data[i].product_id;
                    option.text = data[i].model;
                    option.setAttribute('data-price', data[i].price);
                    dropdown_object.add(option);
                }
            }
        }
        else {
            //remove previout option element
            for (var i = dropdown_object.length - 1; i >= 0; i--) {
                dropdown_object.remove(i);
            }
            //create defalut element
            var option = document.createElement("option");
            option.text = ID_VYBERTE_MOZNOST;
            option.value = 'null';
            dropdown_object.add(option);
        }
    
}
//registering only numbers pushed on keyboard
function AllowOnlyNumbers(e) {
    e = (e) ? e : window.event;
    var key = null;
    var charsKeys = [
        97, // a  Ctrl + a Select All
        65, // A Ctrl + A Select All
        99, // c Ctrl + c Copy
        67, // C Ctrl + C Copy
        118, // v Ctrl + v paste
        86, // V Ctrl + V paste
        115, // s Ctrl + s save
        83, // S Ctrl + S save
        112, // p Ctrl + p print
        80 // P Ctrl + P print
    ];
    var specialKeys = [
        8, // backspace
        9, // tab
        27, // escape
        13, // enter
        35, // Home & shiftKey +  #
        36, // End & shiftKey + $
        37, // left arrow &  shiftKey + %
        39, //right arrow & '
        46, // delete & .
        45 //Ins &  -
    ];
    key = e.keyCode ? e.keyCode : e.which ? e.which : e.charCode;
    //console.log("e.charCode: " + e.charCode + ", " + "e.which: " + e.which + ", " + "e.keyCode: " + e.keyCode);
    //console.log(String.fromCharCode(key));
    // check if pressed key is not number 
    if (key && key < 48 || key > 57) {
        //Allow: Ctrl + char for action save, print, copy, ...etc
        if ((e.ctrlKey && charsKeys.indexOf(key) != -1) ||
            //Fix Issue: f1 : f12 Or Ctrl + f1 : f12, in Firefox browser
            (navigator.userAgent.indexOf("Firefox") != -1 && ((e.ctrlKey && e.keyCode && e.keyCode > 0 && key >= 112 && key <= 123) || (e.keyCode && e.keyCode > 0 && key && key >= 112 && key <= 123)))) {
            return true
        }
        // Allow: Special Keys
        else if (specialKeys.indexOf(key) != -1) {
            //Fix Issue: right arrow & Delete & ins in FireFox
            if ((key == 39 || key == 45 || key == 46)) {
                return (navigator.userAgent.indexOf("Firefox") != -1 && e.keyCode != undefined && e.keyCode > 0);
            }
            //DisAllow : "#" & "$" & "%"
            else if (e.shiftKey && (key == 35 || key == 36 || key == 37)) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    } else {
        return true;
    }
}
//Function for incrementing a number in input by 1 in custom input number
function numberIncrement(ev) {
    if (ev.target.parentNode.firstChild.nextSibling.value == '') {
        ev.target.parentNode.firstChild.nextSibling.value = 0;
    }
    actualNumber = parseInt(ev.target.parentNode.firstChild.nextSibling.value);
    if(actualNumber < 10){
        newNumber = actualNumber + 1;
        ev.target.parentNode.firstChild.nextSibling.value = newNumber;
    }
}
//Function for decrementing a number in input by 1 in custom input number
function numberDecrement(ev) {
    actualNumber = parseInt(ev.target.parentNode.firstChild.nextSibling.value);
    //Cannot be decremented under 0
    if (actualNumber > 0) {
        newNumber = actualNumber - 1;
        ev.target.parentNode.firstChild.nextSibling.value = newNumber;
    }
}
//Function for custom inputs type of text
function onInputFocus(ev) {
    ev.target.parentNode.classList.add('input-filled-sz');
}

function onInputBlur(ev) {
    if (ev.target.value.trim() === '') {
        ev.target.parentNode.classList.remove('input-filled-sz')
    }
}
//Callculating all action in form
function showBill(devictTypVal, deviceModelVal, quantityVal, devicePrice, counterElements, i, TotalPrice) {
    var dropdownCountry = document.getElementById('country-sz');
    var billTransport = document.getElementById('bill-transport-sz');
    var billFinalPrice = document.getElementById('bill-summary-price-sz');
      
    if(i == 0){
        document.getElementById('bill-device-name-sz-'+(i)).innerHTML = 'Model: <br>' + devictTypVal + " " + deviceModelVal;
        document.getElementById('bill-quantity-sz-'+(i)).innerHTML = 'Počet kusov: <br>' + quantityVal + " ks";
        document.getElementById('bill-price-sz-'+(i)).innerHTML = 'Cena: <br>' + (quantityVal * devicePrice) + " €";
        TotalPrice[i] = (quantityVal * devicePrice);
    }
    else{
        document.getElementById('bill-device-name-sz-'+(i)).innerHTML = devictTypVal + " " + deviceModelVal;
        document.getElementById('bill-quantity-sz-'+(i)).innerHTML = quantityVal + " ks";
        document.getElementById('bill-price-sz-'+(i)).innerHTML = (quantityVal * devicePrice) + " €";
        TotalPrice[i] = (quantityVal * devicePrice);
    }
     
    var sum = TotalPrice.reduce(function(a, b) { return a + b; }, 0);
    var inputTotalPrice = document.getElementById('bill-total_price');
    //Listener for country 
    dropdownCountry.addEventListener('change', function() {
        switch (dropdownCountry.value) {
            case "":
                //Unspecified country
                billTransport.innerHTML = ""
                billFinalPrice.innerHTML = "";
                break;
            case "Slovenská republika":
                billTransport.innerHTML = "ZADARMO";
                //Final price for SK
                billFinalPrice.innerHTML = Number(sum).toFixed(2) + " €";
                inputTotalPrice.value = Number(sum).toFixed(2);
                break;
            case "Česká republika":
                billTransport.innerHTML = "+2 €";
                //Final price for CZ
                inputTotalPrice.value = Number(sum).toFixed(2);
                billFinalPrice.innerHTML = Number(sum+2).toFixed(2) + " €";
                break;
        }
    });
}
//Check if fields are filled
function checkIfFilled(counterElements) {

    canShow = false;
    var TotalPrice = new Array();
    for (var i = 0; i <= counterElements; i++) {
        var deviceType = jQuery('select[name=cf-device_type-'+(i)+'] option:selected');
        var deviceModel = jQuery('select[name=cf-device_model-'+(i)+'] option:selected');
        var quantity = jQuery('input[name=cf-device_quantity-'+(i)+']');
        var ID_ZERO = '0';
        var ID_EMPTY = '';
        

        if (deviceType[0].value != ID_NULL && deviceModel[0].value != ID_NULL && quantity[0].value != ID_ZERO && quantity[0].value != ID_EMPTY) {
            showBill(deviceType[0].text, deviceModel[0].text, quantity[0].value, deviceModel[0].getAttribute('data-price'), counterElements, i, TotalPrice);
            canShow = true;
        }
        else{
            canShow = false;
        } 
    }
    if(canShow){
        document.getElementsByClassName('validation')[0].innerHTML = "";
        var counterForPHP = document.getElementById('id_counter_dropDropdowns_elements');
        counterForPHP.value = counterElements;
        var test = jQuery('#myModal').modal('show');

        if(jQuery('#myModal').length){
            document.getElementById("bill-transport-sz").childNodes[0].data = "";
            document.getElementById("bill-summary-price-sz").childNodes[0].data = "";
            document.getElementById("bill-total_price").value = "";
            var selectCountry = document.getElementById("country-sz");

            if (selectCountry.selectedIndex != 0) {
                selectCountry.selectedIndex = 0;
            }
        }
    }
    else{
        document.getElementsByClassName('validation')[0].innerHTML = "Všetky polia musia byť vyplnené!!!";
    }

}
function addBillLines(billLinesElement, counterElements){
    billLinesElement.id = 'id_contact-section-sz-'+(counterElements)
    billLinesElement.childNodes[1].id ='bill-device-name-sz-'+(counterElements);
    billLinesElement.childNodes[3].id ='bill-quantity-sz-'+(counterElements);
    billLinesElement.childNodes[5].id ='bill-price-sz-'+(counterElements);

    //Ak existuje už nevytvorý novy
    if(!jQuery('#id_contact-section-sz-'+(counterElements)).length && counterElements < 5){
        jQuery(billLinesElement.outerHTML).insertAfter('#id_contact-section-sz-'+(counterElements-1));
    } 
}

function addDropDown(dropDownElement, billLinesElement, counterElements){

    dropDownElement.id = 'devicePicker-'+(counterElements+1);
        //vytvorí ho ked stalčí hocičo okrem iné
                
    dropDownElement.childNodes[1].childNodes[1].childNodes[3].childNodes[3].name = "cf-device_type-"+(counterElements+1);
    dropDownElement.childNodes[1].childNodes[1].childNodes[3].childNodes[3].id = 'id-device_type-'+(counterElements+1);
    dropDownElement.childNodes[3].childNodes[1].childNodes[3].childNodes[2].name = "cf-device_model-"+(counterElements+1);
    dropDownElement.childNodes[3].childNodes[1].childNodes[3].childNodes[2].id = 'id-device_model-'+(counterElements+1);
    dropDownElement.childNodes[5].childNodes[1].childNodes[3].childNodes[1].name = "cf-device_quantity-"+(counterElements+1);
    dropDownElement.childNodes[5].childNodes[1].childNodes[3].childNodes[1].id = 'id-device_quantity-'+(counterElements+1);
    dropDownElement.childNodes[5].childNodes[1].childNodes[3].childNodes[3].id = 'button_decrement-sz-'+(counterElements+1);
    dropDownElement.childNodes[5].childNodes[1].childNodes[3].childNodes[5].id = 'button_increment-sz-'+(counterElements+1);

    //Ak existuje už nevytvorý novy
    if(!jQuery('#devicePicker-'+(counterElements+1)).length && counterElements < 4){
        jQuery(dropDownElement.outerHTML).insertAfter('#devicePicker-'+counterElements);
        counterElements++;   
        addBillLines(billLinesElement,counterElements);
        addListernerDropDown(counterElements);
    } 
    return  counterElements;    
}
function removeDropDown(lastDropdownId){

    //delete dropdown menu
    if(jQuery('#devicePicker-'+lastDropdownId).length && lastDropdownId > 0){
       jQuery('#devicePicker-'+lastDropdownId).remove();
       jQuery('#id_contact-section-sz-'+lastDropdownId).remove();
       lastDropdownId--;
    }

    return lastDropdownId;
}

function addListernerDropDown(counterElements) {
    document.getElementById('button_increment-sz-'+(counterElements)).addEventListener('click', numberIncrement);
    document.getElementById('button_decrement-sz-'+(counterElements)).addEventListener('click', numberDecrement);

    var dropdownDeviceType = document.getElementById('id-device_type-'+(counterElements));


    dropdownDeviceType.addEventListener('change', function(){
        getElementValue(dropdownDeviceType.value, counterElements);
    });
    
}