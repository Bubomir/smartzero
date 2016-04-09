 <form method="post">
        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" style="color: white;">
                            Objednávkový formulár
                        </h3>
                        <p>
                            Vyplňte objednávkový formulár a závazne odošlite Vašu
                            <span>
                                objednávku
                            </span>
                            .
                            <br/>
                            O priebehu spracovania objednávky Vás budeme informovať na
                            <span>
                                email
                            </span>
                            zadaný v objednávke.
                        </p>
                    </div>
                    <div class="modal-body">
                            <div class="row custom">
                                <div class="contact-form-section-sz">
                                    <div class="input-group-sz">
                                        <input name="cf-firstname" type="text" autocomplete="off" required/>
                                        <label>
                                            <span data-content="Meno">
                                                Meno
                                            </span>
                                        </label>
                                    </div>
                                     <div id='id-mail' class="input-group-sz">
                                        <input name="cf-email" type="text" autocomplete="off" required/>
                                        <label>
                                            <span data-content="Emailová adresa">
                                                Emailová adresa
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="contact-form-section-sz">
                                      <div class="input-group-sz">
                                        <input name="cf-surname" type="text" autocomplete="off" required/>
                                        <label>
                                            <span data-content="Priezvisko">
                                                Priezvisko
                                            </span>
                                        </label>
                                    </div>
                                    <div class="input-group-sz">
                                        <input name="cf-phone" type="text" autocomplete="off" required/>
                                        <label>
                                            <span data-content="Telefónne číslo">
                                                Telefónne číslo
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                </div>
                                <div class="row custom"  style="margin-top: 16px;">
                                <div class="contact-form-section-sz">
                                <div class="input-group-sz">
                                     <select id="country-sz" class='form-group' name='cf-country' autocomplete='off' required>
                                        <option value="" selected disabled="disabled"></option>
                                        <option value="Slovenská republika">Slovenská republika</option>
                                        <option value="Česká republika">Česká republika</option>
                                     </select>
                                     <label>
                                         <span data-content="Štát">
                                                Štát
                                         </span>
                                     </label>
                                    </div>
                                    <div class="input-group-sz">
                                        <input name="cf-street" type="text" autocomplete="off" required/>
                                        <label>
                                            <span data-content="Ulica">
                                                Ulica
                                            </span>
                                        </label>
                                    </div>
                                    </div>
                                    <div class="contact-form-section-sz">
                                    <div class="input-group-sz">
                                        <input name="cf-city" type="text" autocomplete="off" required/>
                                        <label>
                                            <span data-content="Mesto">
                                                Mesto
                                            </span>
                                        </label>
                                    </div>
                                    <div class="input-group-sz">
                                        <input name="cf-zip" type="text" pattern="[0-9 ]+" autocomplete="off" required/>
                                        <label>
                                            <span data-content="PSČ">
                                                PSČ
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row custom" style="margin-top: 16px;">
                                <div id='id_contact-section-sz-0' class="contact-section-sz">
                                    <div id="bill-device-name-sz-0" class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    </div>
                                    <div id="bill-quantity-sz-0" class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="text-align: center;">
                                    </div>
                                    <div id="bill-price-sz-0" class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="text-align: right;">
                                    </div>
                                </div>
                            </div>
                            <div class="row custom" style="margin-top: 16px;">
                                <div class="contact-section-sz">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        Doprava:
                                    </div>
                                    <div id="bill-transport-sz" class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="text-align: right;">
                                    </div>
                                </div>
                            </div>
                            <div class="row custom" style="margin-top: 16px;">
                                <div class="contact-section-sz price-sz">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        Celková cena:
                                    </div>
                                    <div id="bill-summary-price-sz" class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="text-align: right;">
                                    </div>
                                    <input id="bill-total_price" name="cf-total_price" type="hidden">
                                    <input id="id_counter_dropDropdowns_elements" name="cf-counter" type="hidden">
                                </div>
                            </div>
                            <div class="row custom" style="margin-top: 16px;">
                                <div class="">
                                   <label style="float: left; margin-left: 30px; color: white; text-align: justify;">Prečítal(a) som si a súhlasím s <a href="http://tvrdene-skla.smartzero.sk/obchodne-podmienky">Obchodnými podmienkami</a> </label>
                                   <input id='check-conditions' style="float: right; margin-right: 30px;" type="checkbox" required="required" />
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer" style="text-align: center;">
                        <button type="button" class="ut-btn  theme-btn medium round" data-dismiss="modal" style="margin: 0px;">
                            Zavrieť
                        </button>
                        <button  name="cf-submitted"  type="submit" class="ut-btn  theme-btn medium round" style="margin: 0px;">
                            Odoslať
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- DropDows and Button -->
        <div class="container-contact-form-sz">
        
        <button class="ut-btn  theme-btn medium round"  id="addDropDown" type="button">+</button>
        <button class="ut-btn  theme-btn medium round"  id="removeDropDown" type="button">-</button>

                <div id="devicePicker-0" class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>
                                Značka
                            </label>
                            <div> <!-- POTREBNY DIV PRE JS LOOP --> 
                                <?php echo  get_product_type_parse(); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                           
                                <label>
                                    Model zariadenia
                                </label>
                                <div ><!-- POTREBNY DIV PRE JS LOOP --> 
                                    <select id='id-device_model-0' name='cf-device_model-0' class='form-control' required autocomplete='off'>
                                    <option value="null" >-- Vyberte si jednu z možností --</option>

                                    </select>
                                </div>
                            
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="form-group">
                            <label>
                                Množstvo
                            </label>
                            <div class="input-group">
                                <input id='id-device_quantity-0' onblur="if (this.placeholder == '') {this.placeholder = '0';}"  onkeypress="return AllowOnlyNumbers(event);" name="cf-device_quantity-0" type="text" class="form-control" autocomplete = "off" onfocus="this.placeholder = '';" value="0" placeholder="0" />
                                <div id="button_decrement-sz-0" class="input-group-addon" type="button">
                                    -
                                </div>
                                <div id="button_increment-sz-0" class="input-group-addon" type="button">
                                    +
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="validation"></div>
                <div class="row">
                    <div class="col-lg-4 col-lg-offset-4" style="text-align: center;">
                        <button id="button_order-sz" type="button" class="ut-btn  theme-btn medium round" style="margin-top: 15px;">
                            Objednať
                        </button>
                    </div>
                </div>
        </div>
</form>
