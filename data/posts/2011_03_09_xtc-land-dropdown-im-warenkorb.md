---
date: 2011-03-09 21:10:07
title: xtc: Land-Dropdown im Warenkorb
tags: Webtechnik
slug: xtc-land-dropdown-im-warenkorb
---

<a href="http://neunzehn83.de/blog/wp-content/uploads/2011/02/versandkosten_im_warenkorb_de.jpg"><img class="alignnone size-medium wp-image-678" style="margin-right: 5px;" title="versandkosten_im_warenkorb_de" src="http://neunzehn83.de/blog/wp-content/uploads/2011/02/versandkosten_im_warenkorb_de-290x120.jpg" alt="" width="290" height="120" /></a><a href="http://neunzehn83.de/blog/wp-content/uploads/2011/02/versandkosten_im_warenkorb_us.jpg"><img class="alignnone size-medium wp-image-677 ml0" style="margin-right: 0;" title="versandkosten_im_warenkorb_us" src="http://neunzehn83.de/blog/wp-content/uploads/2011/02/versandkosten_im_warenkorb_us-290x120.jpg" alt="" width="290" height="120" /></a>

Die Versandkosten im Warenkorb anzuzeigen ist ein toller Service. So sieht man direkt im Warenkorb den exakten Gesamtbetrag und die Gäste müssen sich nicht extra registrieren um die richtigen Versandkosten angezeigt zu bekommen. Bei international ausgerichteten Online-Shops macht es außerdem Sinn, den Gast über ein Land-Dropdown das Zielland festlegen zu lassen. So werden die Versandkosten entsprechend berechnet und auch die Preise inkl. oder exkl. MwSt. angezeigt.

Kommt für das ausgewählte Land nur eine Versandmethode in Frage, so wird gleich der Gesamtbetrag angezeigt. Kommen mehrere Versandarten in Frage, werden diese samt kosten aufgelistet.

Im <a href="http://www.xtc-modified.org/">xtcModified-Forum</a> bin ich auch <a href="http://www.xtc-modified.org/forum/topic.php?id=9883">einen Thread</a> gestoßen in dem beschrieben wird, wie man Versandkosten im Warenkorb anzeigt. Diese Änderung habe ich um das Land-Dropdown erweitert. Die Mehrwehrtssteuer werden dem ausgewähltem Land entsprechend berechnet. Das gewählte Land wird auch in das create_account-Formular übernommen.

Alle geänderten Files gibt es <strong><a href="http://neunzehn83.de/blog/wp-content/uploads/2011/03/xtcm_Versandkosten_im_Warenkorb.zip">hier</a></strong> als Download oder <strong><a href="http://neunzehn83.de/blog/wp-content/uploads/2011/03/0001-Versandkosten-im-Warenkorb.patch">hier</a></strong> als Patch - jeweils gegen die aktuelle xtcModified v1.05. Prinzipiell sollte das auch mit XTC SP2.1 funktionieren.

<h2>Im Detail:</h2>

<pre>
<strong><span style="color: #3366ff;">/create_account.php und /create_account_guest.php</span></strong>
<strong>Suche:</strong>
if (isset($_POST['country'])) {
  $selected = $_POST['country'];
} else {
  $selected = STORE_COUNTRY;
}
 
<strong>Ersetze durch:</strong>
if (isset($_POST['country'])) {
  $selected = $_POST['country'];
<strong>} else if (isset($_SESSION['country'])) {
  $selected = $_SESSION['country'];</strong>
} else {
 $selected = STORE_COUNTRY;
}
 
<span style="color: #3366ff;">/includes/cart_actions.php</span>
<strong>Suche:</strong>
case 'update_product' :
 
<strong>Ersetze durch:</strong>
case 'update_product' :
 // Versandkosten im Warenkorb
 if (isset($_POST['country'])) {
 $_SESSION['country'] = xtc_remove_non_numeric($_POST['country']);
 }
 //-
 
<span style="color: #3366ff;">/includes/classes/xtcPrice.php</span>
<strong>Suche:</strong>
$this-&gt;TAX[$zones_data['class']]=xtc_get_tax_rate($zones_data['class']);
 
<strong>Ersetze durch:</strong>
// Versandkosten im Warenkorb
$country_id = -1;
if (isset($_SESSION['country']) &amp;&amp; !isset($_SESSION['customer_id'])) {
  $country_id = $_SESSION['country'];
}
$this-&gt;TAX[$zones_data['class']]=xtc_get_tax_rate($zones_data['class'], $country_id);
//-
 
<span style="color: #3366ff;">/includes/modules/order_details_cart.php</span>
<strong>Suche:</strong>
$module_smarty-&gt;assign('TOTAL_CONTENT', $total_content);
 
<strong>Ersetze durch:</strong>
// Versandkosten im Warenkorb
include DIR_FS_CATALOG.'/includes/shipping_estimate.php';
//-
$module_smarty-&gt;assign('TOTAL_CONTENT', $total_content);
 
<span style="color: #3366ff;">/templates/xtc5/modules/order_details.html</span>
<strong>Suche:</strong>
&lt;tr&gt;
 &lt;td colspan="4"&gt;{$UST_CONTENT}&lt;strong&gt;{$TOTAL_CONTENT}&lt;/strong&gt;{if $SHIPPING_INFO}{$SHIPPING_INFO}{/if}&lt;/td&gt;
 &lt;td&gt;&amp;nbsp;&lt;/td&gt;
 &lt;/tr&gt;
 
<strong>Ersetze durch:
</strong>&lt;tr&gt;
 &lt;td colspan="5"&gt;
 {if $SELECT_COUNTRY}{#text_country#} {$SELECT_COUNTRY}{/if}&lt;br /&gt;
 &lt;/td&gt;
 &lt;/tr&gt;
 &lt;tr&gt;
 &lt;td colspan="2"&gt;
 &amp;nbsp;
 &lt;/td&gt;
 &lt;td colspan="3"&gt;
 {if $UST_CONTENT}{$UST_CONTENT}{else}&lt;strong&gt;{php}printf(TAX_INFO_EXCL, ''){/php}&lt;/strong&gt;&lt;br /&gt;{/if}
 &lt;u&gt;{$TOTAL_CONTENT}&lt;/u&gt;&lt;br /&gt;
 {foreach name=aussen item=shipping_data from=$shipping_content}
 {$shipping_data.NAME} ({$COUNTRY}): {$shipping_data.VALUE}&lt;br /&gt;
 {/foreach}
 {if $total}
 &lt;strong&gt;{#text_total#}: {$total}&lt;/strong&gt;
 {/if}
 &lt;/td&gt;
 &lt;/tr&gt;</pre>

Außerdem die Datei "<a href="http://neunzehn83.de/blog/wp-content/uploads/2011/03/shipping_estimate.zip">shipping_esitmate.php</a>" im Ordner /includes platzieren.