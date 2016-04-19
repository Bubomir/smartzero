<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $title; ?></title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">
<div style="width: 680px;"><a href="<?php echo 'www.tvrdene-skla.smartzero.sk'; ?>" title="<?php echo 'SmartZero'; ?>"><img src="<?php echo 'http://www.tvrdene-skla.smartzero.sk/wp-content/plugins/contact_form/logo/smartzero-logo.png'; ?>" alt="<?php echo 'SmartZero'; ?>" style="margin-bottom: 20px; border: none; height: auto;
    width: auto;  max-width: 590px; max-height: 126px;"/></a>
  <p style="margin-top: 0px; margin-bottom: 20px;"><?php echo 'Ďakujeme Vám za zakúpenie tovaru z internetového obchodu SmartZero.sk. Vaša objednávka bude v prípade dobierky spracovávaná, v ostatných prípadoch po potvrdení platby.' ?></p>
  <table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
    <thead>
      <tr>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;" colspan="2"><?php echo 'Detail objednávky: '; ?></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><b><?php echo 'Číslo objednávky: '; ?></b> <?php echo $last_id; ?><br />
          <b><?php echo 'Vytvorená: '; ?></b> <?php echo $data['date']; ?><br />
          <b><?php echo 'Spôsob platby: '; ?></b> <?php echo $data['payment_method']; ?><br />
          <?php if ($shipping_method) { ?>
          <b><?php echo $text_shipping_method; ?></b> <?php echo $shipping_method; ?>
          <?php } ?></td>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><b><?php echo 'Email: '; ?></b> <?php echo $data['email']; ?><br />
          <b><?php echo 'Tel. č.: '; ?></b> <?php echo $data['phone']; ?><br />
          <b><?php echo 'Stav objednávky: '; ?></b> <?php echo 'prijatá'; ?><br /></td>
      </tr>
    </tbody>
  </table>

  <table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
    <thead>
      <tr>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;"><?php echo 'Adresa: '; ?></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><?php echo $data['firstname'].' '.$data['surname'].'<br>'. $data['street'].'<br>'.$data['city'].'<br>'.$data['zip'].'<br>'.$data['country']; ?></td>
      </tr>
    </tbody>
  </table>
  <table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
    <thead>
      <tr>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;"><?php echo 'Produkt: '; ?></td>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;"><?php echo 'Model: '; ?></td>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: right; padding: 7px; color: #222222;"><?php echo 'Množstvo: '; ?></td>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: right; padding: 7px; color: #222222;"><?php echo 'Cena: '; ?></td>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: right; padding: 7px; color: #222222;"><?php echo 'Celkom: '; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php 
      
      foreach ($order_products as $product) {?>
      <tr>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><?php echo $product['name']; ?>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><?php echo $product['model']; ?></td>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"><?php echo $product['quantity']; ?>ks</td>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"><?php echo number_format((float) $product['price'], 2, '.', ''); ?>€</td>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"><?php echo number_format((float) $product['total'], 2, '.', ''); ?>€</td>
      </tr>
      <?php }

       ?>
    </tbody>
    <tfoot>
      
      <tr>
       <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;" colspan="4"><b><?php echo 'Medzisúčet'; ?>:</b></td>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"><?php echo number_format((float) $data['sub_total_price'], 2, '.', ''); ?>€</td>
      </tr>
      <tr>
       <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;" colspan="4"><b><?php echo 'Doprava'; ?>:</b></td>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"><?php echo number_format((float) $data['shipping_price'], 2, '.', ''); ?>€</td>
      </tr>
      <tr>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;" colspan="4"><b><?php echo 'Celkom'; ?>:</b></td>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"><?php echo number_format((float) $data['total_price'], 2, '.', ''); ?>€</td>
      </tr>
 
    </tfoot>
  </table>
  <p style="margin-top: 0px; margin-bottom: 20px;"><?php echo 'Ak máte akékoľvek otázky, odpovedzte prosím na tento e-mail.' ?></p>
</div>
</body>
</html>
