 <form id="cf-contact_form" method="post" action="<?=esc_url($_SERVER['REQUEST_URI'])?>">
        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" style="color: white;">
                            Objednávkový formulár
                        </h3>
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
                                    <input id="id-control_sum" name="cf-control_sum" type="hidden">
                                </div>
                            </div>
                            <div class="row custom" style="margin-top: 16px;">
                                <div class="col-lg-10 col-md-10 col-sm-9 col-xs-9">
                                   <label style="margin-left: 15px; color: white;" for="check-conditions" >Prečítal(a) som si a súhlasím s <a href="http://tvrdene-skla.smartzero.sk/obchodne-podmienky">Obchodnými podmienkami</a> </label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
                                   <input id='check-conditions' type="checkbox" required="required" />
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer" style="text-align: center;">
                        <button type="button" class="ut-btn  theme-btn medium round" data-dismiss="modal" style="margin: 0px;">
                            Zavrieť
                        </button>
                        <button class="ut-btn  theme-btn medium round"  name="cf-submitted" formmethod = "post" form="cf-contact_form"  type="submit" value="odoslant" style="margin: 0px;">
                            Odoslať
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- LightBox -->
        <div class="backdrop"></div>
        <div class="box">
        <div class="close">X</div>
            <img class="device-picture" src="/wp-content/plugins/contact_form/img/no-image-available.jpg" alt="telefphone">
        </div>

        <!-- DropDows and Button -->
        <div class="container-contact-form-sz">
                <div id="devicePicker-0" class="row">
                    <div class="col-lg-3 col-md-3">
                        <div class="form-group">
                            <label>
                                Značka
                            </label>
                            <div> <!-- POTREBNY DIV PRE JS LOOP --> 
                                <?php echo  get_product_type_parse(); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3">
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
                    <div class="col-lg-3 col-md-3">
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
                    <div class="col-lg-3 col-md-3">
                        <div class="thumbnail-head">Vybrané zariadenie</div>
                        <div class = "light-box">
                        <div class="thumbnail-form text-center-sm text-center-xs">
                            <span>Kliknite pre zväčšenie</span>
                            <img id="thumbnail-0" class="thumbnail-device" src="/wp-content/plugins/contact_form/img/no-image-available.jpg" alt="model"></img>
                        </div>
                        </div>
                    </div>
                </div>
                
                <div class="validation"></div>
                <div class="row">
                	<div class="col-lg-6 col-lg-offset-0" >
                		<p style=" margin: 0px; color: #00bbff;">Pridať ďaľšie sklo</p>
               		</div>
                    <div class="col-lg-7 col-lg-offset-0" style=" margin-top: 15px;">

                    	<button id="addDropDown" class="ut-btn  theme-btn medium round" type="button"  style="float: left;">
                    		<svg style="vertical-align: middle;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" width="12px" height="12px" viewBox="-0.328 0 512 512" enable-background="new -0.328 0 512 512" xml:space="preserve">
							<path d="M467.534,211.863H299.81V44.138C299.81,19.763,280.032,0,255.672,0c-24.375,0-44.138,19.77-44.138,44.138v167.725H43.81  c-24.375,0-44.138,19.77-44.138,44.138s19.763,44.138,44.138,44.138h167.725v167.725c0,24.375,19.763,44.138,44.138,44.138  c24.368,0,44.138-19.763,44.138-44.138V300.138h167.725c24.375,0,44.138-19.777,44.138-44.138  C511.672,231.618,491.909,211.863,467.534,211.863z" fill="#FFFFFF"/>
							</svg>
                    	</button>
	        			<button id="removeDropDown" class="ut-btn  theme-btn medium round" type="button" style="float: left;">
	        				<svg style="vertical-align: middle;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" width="12px" height="12px" viewBox="0.328 0 512 94.278" enable-background="new 0.328 0 512 94.278" xml:space="preserve">
							<g>
								<path d="M465.189,94.278H47.467c-26.034,0-47.139-21.104-47.139-47.139S21.433,0,47.467,0h417.722   c26.034,0,47.139,21.105,47.139,47.14S491.223,94.278,465.189,94.278z" fill="#FFFFFF"/>
							</g>
							</svg>
	        			</button>
		        		
	                    <button id="button_order-sz" type="button" class="ut-btn  theme-btn medium round" style="float: right;">Objednať</button>
                    </div>
                </div>
        </div>
</form>