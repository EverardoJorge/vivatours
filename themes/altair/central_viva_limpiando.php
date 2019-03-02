<?php

  else
	{
?>
<!-- Begin content -->
<div id="page_content_wrapper" class="<?php if(!empty($pp_page_bg)) { ?>hasbg<?php } ?> <?php if(!empty($pp_page_bg) && !empty($global_pp_topbar)) { ?>withtopbar<?php } ?>">
    <div class="inner">
    	<!-- Begin main content -->
    	<div class="inner_wrapper">
    		<div class="sidebar_content full_width">



            <?php


            if ($User) {
              ?>
            <form action="guardar-bloqueo" method="post" name="forma_carrito_1" id="forma_carrito_1"  class="feup-pure-form feup-pure-form-aligned">

                <table width="100%"  rules="none" align="center" cellpadding="0" cellspacing="0" class="minimo">
                    

                    <tr id="habitaciones1" style="display:none;">
                      <td>

                          

                          <table width="100%" border="0" cellspacing="3" cellpadding="3" class="central">
                            <tr>
                              <td colspan="2">
                              <label for="Tipo" class="">Habitación</label>
                              <select name="Tipo" id="Tipo"  class="ewd-feup-select">
                                <option value="0">Elije una Opcion</option>
                                <option value="1">SGL</option>
                                <option value="2">DBL</option>
                                <option value="3">TRP</option>
                              </select>
                              </td>

                              <td width="33%">
                                <label>Categoria:</label>
                                  <select name="Categoria" id="Categoria" onchange="activa_boton(this,this.form.Submit2)"  class="ewd-feup-select" style="width:80%;">
                                    <option value="Unica">Unica</option>
                                  </select>
                              </td>
                            </tr>

                            <tr>
                              <td width="33%" align="left">Nombre Pax:</td>
                              <td width="33%" align="left">Descuento:</td>
                              <td width="33%" align="left">Fecha de Nacimiento:</td>
                            </tr>

                            <tr id="div_pax1" style="display:none;">
                              <td align="center" width="33%"><input name="pax1" type="text" id="pax1" size="30"  class=""  required style="width:100%;"/></td>
                              <td align="center" width="33%"><select name="Edad1" id="Edad1"  class="ewd-feup-select" style="width:80%;">
                              <option value="Sin Descuento" selected="selected"> Sin Descuento </option>
                              <option value="Menor">Menor</option>
                              <option value="Mayor">3ra. Edad (+65)</option>
                              </select>
                              </td>
                              <td align="center" width="33%">
                                         <select name="pax1_dia" class="ewd-feup-select" style="width:30%;"><?php foreach($dias as $dkey => $diass){ ?>
                                           <option value="<?php echo $diass;?>" <?php echo (01 == $dkey) ? " selected" : ""; ?>><?php echo $diass; ?></option><?php  }  ?>
                                         </select>

                                          <select name="pax1_mes" class="ewd-feup-select" style="width:30%;">
                                          <?php foreach($meses as $mkey => $mes){?>
                                          <option value="<?php echo $mkey;?>" <?php echo (1 == $mkey) ? " selected" : ""; ?>><?php echo $mes; ?></option><?php  }  ?>
                                          </select>

                                          <select name="pax1_anio" class="ewd-feup-select" style="width:30%;">
                                          <?php  foreach($anios as $akey => $anio)  { ?>
                                            <option value="<?php echo $anio;?>" <?php echo (1980 == $akey) ? " selected" : ""; ?>><?php echo $anio; ?></option>
                                          <?php } ?>
                                          </select>
                              </td>
                              </tr>

                               <tr id="div_pax2" style="display:none;">
                                    <td align="center" width="33%"><input name="pax2" type="text"  class="" id="pax2" size="30"  style="width:100%;"/></td>
                                    <td align="center" width="33%"><select name="Edad2" id="Edad2"  class="ewd-feup-select"  style="width:80%;">
                                      <option value="Sin Descuento" selected="selected"> Sin Descuento </option>
                                      <option value="Menor">Menor</option>
                                      <option value="Mayor">3ra. Edad (+65)</option>
                                    </select></td>

                                    <td align="center" width="33%">

                                   <select name="pax2_dia" class="ewd-feup-select" style="width:30%;"><?php foreach($dias as $dkey => $diass){ ?>
                                   <option value="<?php echo $diass;?>" <?php echo (01 == $dkey) ? " selected" : ""; ?>><?php echo $diass; ?></option><?php  }  ?></select>
                                   <select name="pax2_mes" class="ewd-feup-select"  style="width:30%;"><?php foreach($meses as $mkey => $mes){?>
                                   <option value="<?php echo $mkey;?>" <?php echo (1 == $mkey) ? " selected" : ""; ?>><?php echo $mes; ?></option><?php  }  ?></select>
                                   <select name="pax2_anio" class="ewd-feup-select"  style="width:30%;">
                                   <?php  foreach($anios as $akey => $anio)  { ?>
                                      <option value="<?php echo $anio;?>" <?php echo (1980 == $akey) ? " selected" : ""; ?>><?php echo $anio; ?></option>
                                  <?php } ?>
                                      </select>
                               </td>
                              </tr>

                              <tr id="div_pax3" style="display:none;">
                                <td align="center" width="33%"><input name="pax3" type="text" id="pax3" size="30"  style="width:100%;"/></td>
                                <td align="center" width="33%"><select name="Edad3" id="Edad3" class="ewd-feup-select"  style="width:80%;">
                                   <option value="Sin Descuento" selected="selected"> Sin Descuento </option>
                                  <option value="Menor">Menor</option>
                                  <option value="Mayor">3ra. Edad (+65)</option>
                                </select>
                              </td>
                                <td align="center" width="33%">

                               <select name="pax3_dia" class="ewd-feup-select" style="width:30%;">
                                  <?php
                    		     foreach($dias as $dkey => $diass)
                                   { ?>
                                 <option value="<?php echo $diass;?>" <?php echo (01 == $dkey) ? " selected" : ""; ?>><?php echo $diass; ?></option>
                                 <?php  }  ?>

                               </select>

                               <select name="pax3_mes" class="ewd-feup-select" style="width:30%;">
                                <?php foreach($meses as $mkey => $mes){?>
                                <option value="<?php echo $mkey;?>" <?php echo (1 == $mkey) ? " selected" : ""; ?>><?php echo $mes; ?></option>
                                <?php  }  ?>
                               </select>

                               <select name="pax3_anio" class="ewd-feup-select" style="width:30%;">
                               <?php  foreach($anios as $akey => $anio)  { ?>
                                  <option value="<?php echo $anio;?>" <?php echo (1980 == $akey) ? " selected" : ""; ?>><?php echo $anio; ?></option>
                              <?php } ?>
                                  </select>
                            </td>
                                </tr>
                        </table>
                     </td>
                   </tr>

                  <tr id="habitaciones2" style="display:none;">
                    <td>
                        <script type="text/javascript">
                    		jQuery(document).ready(function(){
                    			jQuery("select[name=Tipo2]").change(function(){

                    		if (jQuery('select[name=Tipo2]').val() == '1') {
                                jQuery("#div_pax1_2").hide();
                    		    jQuery("#div_pax2_2").hide();

                    		   jQuery("#div_pax1_2").fadeToggle(2000);
                    		   jQuery("#pax1_2").attr('required', 'true');
                    		   jQuery("#pax2_2").removeAttr('required');
                    		   	jQuery("#pax2_2").attr('disabled', 'true');
                    		   }

                    		if (jQuery('select[name=Tipo2]').val() == '2') {
                               jQuery("#div_pax1_2").hide();
                    		    jQuery("#div_pax2_2").hide();

                    		   jQuery("#div_pax1_2").fadeToggle(2000);
                    		    jQuery("#div_pax2_2").fadeToggle(2000);
                    			jQuery("#pax1_2").attr('required', 'true');
                    			jQuery("#pax2_2").attr('required', 'true');
                    			 jQuery("#pax2_2").removeAttr('disabled');
                    		   }


                    			});
                    		});
                    		</script>

    <table width="100%" border="0" cellspacing="3" cellpadding="3" class="central">
        <tr>
    <td colspan="2">
        <label >Habitación</label>
       <select name="Tipo2" id="Tipo2"  class="ewd-feup-select">
  <option value="0">Elije una Opcion</option>
  <option value="1">SGL</option>
  <option value="2">DBL</option>
        </select>

    </td>
    <td width="33%" >
    <label> Categoria: </label>
       <select name="Categoria2" id="Categoria2"  class="ewd-feup-select" style="width:80%;">
            <option value="Unica" >Unica</option>
          </select>

    </td> </tr>

  <tr>
    <td width="33%" align="left" >Nombre Pax:</td>
    <td width="33%" align="left" >Descuento:</td>
    <td width="33%" align="left" >Fecha de Nacimiento:</td>
  </tr>

  <tr id="div_pax1_2" style="display:none;">
            <td align="center" width="33%"><input name="pax1_2" type="text"  class="" id="pax1_2"  style="width:100%;"/></td>
            <td align="center" width="33%"><select name="Edad1_2" id="Edad1_2"  class="ewd-feup-select" style="width:80%;">
              <option value="Sin Descuento" selected="selected"  class="form-control input-sm"> Sin Descuento </option>
           <option value="Sin Descuento" selected="selected"> Sin Descuento </option>
              <option value="Menor">Menor</option>
             <option value="Mayor">3ra. Edad (+65)</option>
            </select></td>

            <td align="center" width="33%">

           <select name="pax1_2dia" class="ewd-feup-select" style="width:30%;">
              <?php
		     foreach($dias as $dkey => $diass)
               { ?>
             <option value="<?php echo $diass;?>" <?php echo (01 == $dkey) ? " selected" : ""; ?>><?php echo $diass; ?></option>
             <?php  }  ?>

           </select>

           <select name="pax1_2mes" class="ewd-feup-select"  style="width:30%;">
            <?php foreach($meses as $mkey => $mes){?>
            <option value="<?php echo $mkey;?>" <?php echo (1 == $mkey) ? " selected" : ""; ?>><?php echo $mes; ?></option>
            <?php  }  ?>
           </select>

           <select name="pax1_2anio" class="ewd-feup-select"  style="width:30%;">
           <?php  foreach($anios as $akey => $anio)  { ?>
              <option value="<?php echo $anio;?>" <?php echo (1980 == $akey) ? " selected" : ""; ?>><?php echo $anio; ?></option>
          <?php } ?>
              </select>
        </td>

          </tr>

          <tr id="div_pax2_2" style="display:none;">
            <td align="center" width="33%"><input name="pax2_2" type="text" class="" id="pax2_2" style="width:100%;"/></td>
            
            <!--*********************HASTA AQUÍ ME QUEDE*********************-->
            <td align="center" width="33%"><select name="Edad2_2" id="Edad2_2"  class="ewd-feup-select"  style="width:80%;">
              <option value="Sin Descuento" selected="selected"  class=""> Sin Descuento </option>
            <option value="Sin Descuento" selected="selected"> Sin Descuento </option>
              <option value="Menor">Menor</option>
              <option value="Mayor">3ra. Edad (+65)</option>
            </select></td>
            <td align="center" width="33%">

           <select name="pax2_2dia" class="ewd-feup-select"  style="width:30%;">
              <?
		     foreach($dias as $dkey => $diass)
               { ?>
             <option value="<?php echo $diass;?>" <?php echo (01 == $dkey) ? " selected" : ""; ?>><?php echo $diass; ?></option>
             <?php  }  ?>

           </select>

           <select name="pax2_2mes" class="ewd-feup-select"  style="width:30%;" >
            <?php foreach($meses as $mkey => $mes){?>
            <option value="<?php echo $mkey;?>" <?php echo (1 == $mkey) ? " selected" : ""; ?>><?php echo $mes; ?></option>
            <?php  }  ?>
           </select>

           <select name="pax2_2anio" class="ewd-feup-select"  style="width:30%;">
           <?php  foreach($anios as $akey => $anio)  { ?>
              <option value="<?php echo $anio;?>" <?php echo (1980 == $akey) ? " selected" : ""; ?>><?php echo $anio; ?></option>
          <?php } ?>
              </select>

          </td>
   </tr>
   </table>
     </td>
   </tr>

     <tr id="habitaciones3" style="display:none;">
      <td>

            <script type="text/javascript">
        		jQuery(document).ready(function(){
        			jQuery("select[name=Tipo3]").change(function(){

        		if (jQuery('select[name=Tipo3]').val() == '1') {
                    jQuery("#div_pax1_3").hide();
        		    jQuery("#div_pax2_3").hide();

        		   jQuery("#div_pax1_3").fadeToggle(2000);
        		   jQuery("#pax1_3").attr('required', 'true');
        		   jQuery("#pax2_3").removeAttr('required');
        		   	jQuery("#pax2_3").attr('disabled', 'true');
        		   }

        		if (jQuery('select[name=Tipo3]').val() == '2') {
                   jQuery("#div_pax1_3").hide();
        		    jQuery("#div_pax2_3").hide();

        		   jQuery("#div_pax1_3").fadeToggle(2000);
        		    jQuery("#div_pax2_3").fadeToggle(2000);
        			jQuery("#pax1_3").attr('required', 'true');
        			jQuery("#pax2_3").attr('required', 'true');
        			jQuery("#pax2_3").removeAttr('disabled');
        		   }


        			});
        		});
        		</script>
    <table width="100%" border="0" cellspacing="3" cellpadding="3" class="central">
        <tr>
    <td colspan="2">

        <label> Habitación</label>
           <select name="Tipo3" id="Tipo3"  class="ewd-feup-select">
            <option value="0">Elije una Opcion</option>
            <option value="1">SGL</option>
            <option value="2">DBL</option>
            </select>

    </td>
    <td width="33%">
   <label>Categoria</label>
 <select name="Categoria3" id="Categoria3" onchange="activa_boton(this,this.form.Submit2)"  class="ewd-feup-select" style="width:80%;">
<option value="Unica">Unica</option>
</select> </td>
      </tr>

  <tr>
    <td width="33%" align="left" >Nombre Pax:</td>
    <td width="33%" align="left" >Descuento:</td>
    <td width="33%" align="left" >Fecha de Nacimiento:</td>
  </tr>
<tr id="div_pax1_3" style="display:none;">
            <td align="center" width="33%"><input name="pax1_3" type="text"  class="" id="pax1_3"  style="width:100%;"/></td>
            <td align="center" width="33%"><select name="Edad1_3" id="Edad1_3"  class="ewd-feup-select" style="width:80%;">
               <option value="Sin Descuento" selected="selected"> Sin Descuento </option>
              <option value="Menor">Menor</option>
              <option value="Mayor">3ra. Edad (+65)</option>
            </select></td>
            <td align="center" width="33%">
           <select name="pax1_3dia" class="ewd-feup-select"  style="width:30%;">
              <?php
		     foreach($dias as $dkey => $diass)
               { ?>
             <option value="<?php echo $diass;?>" <?php echo (01 == $dkey) ? " selected" : ""; ?>><?php echo $diass; ?></option>
             <?php  }  ?>

           </select>
           <select name="pax1_3mes" class="ewd-feup-select"   style="width:30%;">
            <?php foreach($meses as $mkey => $mes){?>
            <option value="<?php echo $mkey;?>" <?php echo (1 == $mkey) ? " selected" : ""; ?>><?php echo $mes; ?></option>
            <?php  }  ?>
           </select>
           <select name="pax1_3anio" class="ewd-feup-select"  style="width:30%;">
           <?php  foreach($anios as $akey => $anio)  { ?>
              <option value="<?php echo $anio;?>" <?php echo (1980 == $akey) ? " selected" : ""; ?>><?php echo $anio; ?></option>
          <?php } ?>
              </select>
       </td>

          </tr>
          <tr id="div_pax2_3" style="display:none;">
            <td align="center" width="33%"><input name="pax2_3"  id="pax2_3" type="text"   style="width:100%;"/></td>
            <td align="center" width="33%">
            <select name="Edad2_3" id="Edad2_3"  class="ewd-feup-select" style="width:80%;">
              <option value="Sin Descuento" selected="selected"> Sin Descuento </option>
              <option value="Menor">Menor</option>
             <option value="Mayor">3ra. Edad (+65)</option>
            </select></td>
            <td align="center" width="33%">
           <select name="pax2_3dia" class="ewd-feup-select"    style="width:30%;">
              <?php
		     foreach($dias as $dkey => $diass)
               { ?>
             <option value="<?php echo $diass;?>" <?php echo (01 == $dkey) ? " selected" : ""; ?>><?php echo $diass; ?></option>
             <?php  }  ?>

           </select>

           <select name="pax2_3mes" class="ewd-feup-select"   style="width:30%;">
            <?php foreach($meses as $mkey => $mes){?>
            <option value="<?php echo $mkey;?>" <?php echo (1 == $mkey) ? " selected" : ""; ?>><?php echo $mes; ?></option>
            <?php  }  ?>
           </select>

           <select name="pax2_3anio" class="ewd-feup-select"  style="width:30%;">
           <?php  foreach($anios as $akey => $anio)  { ?>
              <option value="<?php echo $anio;?>" <?php echo (1980 == $akey) ? " selected" : ""; ?>><?php echo $anio; ?></option>
          <?php } ?>
              </select>
      </td>

          </tr></table>

   </td>
 </tr>
   <tr id="habitaciones4" style="display:none;">
    <td>

    <script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery("select[name=Tipo4]").change(function(){

		if (jQuery('select[name=Tipo4]').val() == '1') {
            jQuery("#div_pax1_4").hide();
		    jQuery("#div_pax2_4").hide();
			jQuery("#div_pax1_4").fadeToggle(2000);
			jQuery("#pax1_4").attr('required', 'true');
		   jQuery("#pax2_4").removeAttr('required');
		   	jQuery("#pax2_4").attr('disabled', 'true');
		   }

		if (jQuery('select[name=Tipo4]').val() == '2') {
           jQuery("#div_pax1_4").hide();
		    jQuery("#div_pax2_4").hide();


		  jQuery("#div_pax1_4").fadeToggle(2000);
		    jQuery("#div_pax2_4").fadeToggle(2000);
			jQuery("#pax1_4").attr('required', 'true');
			jQuery("#pax2_4").removeAttr('disabled');
		   jQuery("#pax2_4").attr('required', 'true');
		   jQuery("#pax2_4").removeAttr('disabled');
		   }


			});
		});
		</script>
    <table width="100%" border="0" cellspacing="3" cellpadding="3" class="central">
        <tr><td colspan="2">
       <label>Habitación</label>
        <select name="Tipo4" id="Tipo4"  class="ewd-feup-select">
  <option value="0">Elije una Opcion</option>
  <option value="1">SGL</option>
  <option value="2">DBL</option>

            </select>
    </td>
    <td width="33%">
   <label> Categoria</label>
    <select name="Categoria4" id="Categoria4" onchange="activa_boton(this,this.form.Submit2)"  class="ewd-feup-select"  style="width:80%;">
           <option value="Unica">Unica</option>
            </select>
    </td>
   </tr>

  <tr>
    <td width="33%" align="left" >Nombre Pax:</td>
    <td width="33%" align="left" >Descuento:</td>
    <td width="33%"  align="left">Fecha de Nacimiento:</td>
  </tr>
  <tr id="div_pax1_4" style="display:none;">
            <td align="center" width="33%"><input name="pax1_4" type="text" id="pax1_4"  style="width:100%;"/></td>

            <td align="center" width="33%"><select name="Edad1_4" id="Edad1_4"  class="ewd-feup-select" style="width:80%;">
              <option value="Sin Descuento" selected="selected"> Sin Descuento </option>
              <option value="Menor">Menor</option>
              <option value="Mayor">3ra. Edad (+65)</option>
            </select>
          </td>

            <td align="center" width="33%">
           <select name="pax1_4dia" class="ewd-feup-select"  style="width:30%;">
              <?php
		     foreach($dias as $dkey => $diass)
               { ?>
             <option value="<?php echo $diass;?>" <?php echo (01 == $dkey) ? " selected" : ""; ?>><?php echo $diass; ?></option>
             <?php  }  ?>

           </select>
           <select name="pax1_4mes" class="ewd-feup-select"   style="width:30%;">
            <?php foreach($meses as $mkey => $mes){?>
            <option value="<?php echo $mkey;?>" <?php echo (1 == $mkey) ? " selected" : ""; ?>><?php echo $mes; ?></option>
            <?php  }  ?>
           </select>
            <select name="pax1_4anio" class="ewd-feup-select"  style="width:30%;">
           <?php  foreach($anios as $akey => $anio)  { ?>
              <option value="<?php echo $anio;?>" <?php echo (1980 == $akey) ? " selected" : ""; ?>><?php echo $anio; ?></option>
          <?php } ?>
              </select>
      </td>

          </tr>
          <tr id="div_pax2_4" style="display:none;">
            <td align="center" width="33%"><input name="pax2_4" type="text" id="pax2_4" style="width:100%;"/></td>
            <td align="center" width="33%"><select name="Edad2_4" id="Edad2_4"  class="ewd-feup-select" style="width:80%;">
             <option value="Sin Descuento" selected="selected"> Sin Descuento </option>
              <option value="Menor">Menor</option>
              <option value="Mayor">3ra. Edad (+65)</option>
            </select></td>
            <td align="center" width="33%">
           <select name="pax2_4dia" class="ewd-feup-select"  style="width:30%;">
              <?php
        		     foreach($dias as $dkey => $diass)
                       { ?>
                     <option value="<?php echo $diass;?>" <?php echo (01 == $dkey) ? " selected" : ""; ?>><?php echo $diass; ?></option>
                     <?php  }  ?>

           </select>

           <select name="pax2_4mes" class="ewd-feup-select"   style="width:30%;">
                <?php foreach($meses as $mkey => $mes){?>
                <option value="<?php echo $mkey;?>" <?php echo (1 == $mkey) ? " selected" : ""; ?>><?php echo $mes; ?></option>
                <?php  }  ?>
           </select>

           <select name="pax2_4anio" class="ewd-feup-select"  style="width:30%;">
               <?php  foreach($anios as $akey => $anio)  { ?>
                  <option value="<?php echo $anio;?>" <?php echo (1980 == $akey) ? " selected" : ""; ?>><?php echo $anio; ?></option>
              <?php } ?>
              </select>
        </td>

          </tr>
        </table>


            </td>
      </tr>
      <tr align="center" >
        <td align="right" ><label>Comentarios:</label>
          <textarea name="Comentarios"  rows="4" id="Comentarios"  style="width:100%;"><?php=$Comentarios?></textarea><br /><br /><center>
          <input name="Submit2" id="Submit2" type="submit" value="Bloquear Espacio"/>
          <input type="button" name="Button2" value="Limpiar"  onclick="location.href='#'" />     </center> </td>
      </tr>
      </table>
</form>

<?php

} //End if USER

else {
	echo "Debes estar registrado para poder hacer  bloqueos.";
	}
  ?>
	  </div>
    	</div>


    	<!-- End main content -->

    </div>
</div>
<?php
}

if(empty($ppb_enable))
{
  echo '<br class="clear"/><br/><br/>';
}

get_footer();

?>
