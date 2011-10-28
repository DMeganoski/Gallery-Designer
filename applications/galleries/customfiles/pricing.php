<?php if (!defined('APPLICATION')) exit();
$Tins[] = array('Label' => '1-S (Round)', 'Width' => "6-11/16in", 'Depth' => '1-13/16in', 'Units' => 'in.', 'BaseLidPrice' => '2.40', 'BaseBasePrice' => '1.13');
$Tins[] = array('Label' => '2-C (Round)', 'Width' => "7-3/16in", 'Depth' => '2-5/8in', 'Units' => 'in.', 'BaseLidPrice' => '2.51', 'BaseBasePrice' => '1.19');
$Tins[] = array('Label' => '3-C (Round)', 'Width' => "8-18in", 'Depth' => '3in', 'Units' => 'in.', 'BaseLidPrice' => '3.20', 'BaseBasePrice' => '1.73');
$Tins[] = array('Label' => '5-C (Round)', 'Width' => "9-7/8in", 'Depth' => '3-1/2in', 'Units' => 'in.', 'BaseLidPrice' => '4.15', 'BaseBasePrice' => '1.99');
$Tins[] = array('Label' => '115 (Round)', 'Width' => "9-7/8in", 'Depth' => '1-15/16in', 'Units' => 'in.', 'BaseLidPrice' => '4.49', 'BaseBasePrice' => '1.40');
$Tins['Modifier'] = .04;
$Mod = $Tins['Modifier'];

?>
<div id="Custom">
    <div class ="Heading">
        <h1>2011 Pricing Guide</h1>
    </div>
   <table>
      <tbody>
      <tr>
      <td class="Head Description">Tin Description</td>
      <td class="Head Configuation">Configurations</td>
      <td class="Head Price Number">Min (24)</td>
      <td class="Head Price Number">100-</td>
      <td class="Head Price Number">250-</td>
      <td class="Head Price Number">500-</td>
      <td class="Head Price Number">1000-</td>
      </tr>
      </tbody>

      <?php
      foreach ($Tins as $Tin) {
	 if (is_array($Tin)) {
	 $BaseLidPrice = $Tin['BaseLidPrice'];
	 $BaseBasePrice = $Tin['BaseBasePrice'];
      //echo '<table class="Tin Pricing">'?>


	 <tr>
	 <td colspan="7" class="Main Label"><?php echo $Tin['Label']; ?></td>
	 </tr>
	  <tr>
	    <td class="Size"><?php echo $Tin['Width'].' x '.$Tin['Depth']; ?></td>
	    <td class="Label">Lid w/ Laminate</td>
	    <td class="Price"><strong>$</strong><?php echo number_format($BaseLidPrice, 2, '.', ''); ?></td>
	    <td class="Price"><strong>$</strong><?php echo number_format($BaseLidPrice - $Mod, 2, '.', ''); ?></td>
	    <td class="Price"><strong>$</strong><?php echo number_format($BaseLidPrice - 2*$Mod, 2, '.', ''); ?></td>
	    <td class="Price"><strong>$</strong><?php echo number_format($BaseLidPrice - 3*$Mod, 2, '.', ''); ?></td>
	    <td class="Price"><strong>$</strong><?php echo number_format($BaseLidPrice - 4*$Mod, 2, '.', ''); ?></td>
	  </tr>
	  <tr>
	    <td class="Empty"></td>
	    <td class="Label">Base</td>
	    <td class="Price"><strong>$</strong><?php echo number_format($BaseBasePrice, 2, '.', ''); ?></td>
	    <td class="Price"><strong>$</strong><?php echo number_format($BaseBasePrice - $Mod, 2, '.', ''); ?></td>
	    <td class="Price"><strong>$</strong><?php echo number_format($BaseBasePrice - 2*$Mod, 2, '.', ''); ?></td>
	    <td class="Price"><strong>$</strong><?php echo number_format($BaseBasePrice - 3*$Mod, 2, '.', ''); ?></td>
	    <td class="Price"><strong>$</strong><?php echo number_format($BaseBasePrice - 4*$Mod, 2, '.', ''); ?></td>
	  </tr>
	  <tr>
	    <td class="Empty"></td>
	    <td class="Label">Total Both (Unassembled)</td>
	    <td class="Price"><strong>$</strong><?php echo number_format($BaseBasePrice + $BaseLidPrice, 2, '.', ''); ?></td>
	    <td class="Price"><strong>$</strong><?php echo number_format($BaseBasePrice + $BaseLidPrice - .08, 2, '.', ''); ?></td>
	    <td class="Price"><strong>$</strong><?php echo number_format($BaseBasePrice + $BaseLidPrice - .16, 2, '.', ''); ?></td>
	    <td class="Price"><strong>$</strong><?php echo number_format($BaseBasePrice + $BaseLidPrice - .24, 2, '.', ''); ?></td>
	    <td class="Price"><strong>$</strong><?php echo number_format($BaseBasePrice + $BaseLidPrice - .32, 2, '.', ''); ?></td>
	  </tr>
      <?php // echo '</table>';
       } }
       ?>
   </table>
</div>