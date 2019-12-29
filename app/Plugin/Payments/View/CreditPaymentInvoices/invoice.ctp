<style type="text/css">
    * {
        font-family: Verdana, Arial, sans-serif;
    }
    table{
        font-size: x-small;
    }
    tfoot tr td{
        font-weight: bold;
        font-size: x-small;
    }
    .gray {
        background-color: lightgray
    }

    .table td, .table tr {
        text-align: right;
    }
</style>

<table width="100%">
    <tr>
        <td valign="top"><img src="<?=WWW_ROOT . 'theme/Design/img/logo.png';?>" alt="" width="150"/></td>
        <td align="right">
            <h3>FAKTURA</h3>
            <div><?php echo sprintf("Referens: %s", $invoice["CreditPaymentInvoice"]["id"]);?></div>
            <div><?php echo sprintf("Kundnr: %s", $user["User"]["id"]);?></div>
            <div><?php echo sprintf("Förfallodatum: %s", gmdate('Y-m-d', strtotime(gmdate('Y-m-d', $invoice["CreditPaymentInvoice"]["time"]) . ' + 7 days')));?></div>
            <div><?php echo sprintf("Summa: %s SEK", number_format(round($amount["total"]), 0, '.', ' '));?></div>
        </td>
    </tr>
</table>

<table width="100%">
    <tr>
        <td align="left">
            <h3>Redodds Limited</h3>
            <div><?php echo sprintf("Kingsbridge 23, Imriehel Road"); ?></div>
            <div><?php echo sprintf("Birkirkara"); ?></div>
            <div><?php echo sprintf("Malta"); ?></div>
            <div></div>
            <div><?php echo sprintf("Kundtjänst: +35635500260"); ?></div>
            <div><?php echo sprintf("Skype: Redodds"); ?></div>
            <div><?php echo sprintf("Email: support@redodds.com"); ?></div>
        </td>
        <td align="right">
            <h3><?php echo sprintf("%s %s ( %s )", $user["User"]["first_name"], $user["User"]["last_name"], $user["User"]["username"]);?></h3>
            <div><?php echo sprintf("%s", $user["User"]["address1"]);?></div>
            <div><?php echo sprintf("%s %s", $user["User"]["zip_code"], $user["User"]["city"]);?></div>
            <div><?php echo sprintf("%s", $user["User"]["country"]);?></div>
            <div></div>
            <div><?php echo sprintf("%s", $user["User"]["email"]);?></div>
        </td>
    </tr>
</table>

<table width="100%">
    <tr>
        <td>
            <div><?php echo sprintf("Hej %s %s ( %s ),", $user["User"]["first_name"], $user["User"]["last_name"], $user["User"]["username"]);?></div>
            <div>
                <?php echo sprintf("Based on your loan agreement on date %s your payment is now due. Please make your payment promptly to avoid further interest and late fee. Bla bla bla..", gmdate('Y-m-d', $invoice["CreditPaymentInvoice"]["time"]));?>
            </div>
        </td>
    </tr>

</table>

<br/>

<table class="table" width="100%">
    <thead style="background-color: lightgray;">
    <tr>
        <th>Summa</th>
        <th>Datum</th>
        <th>Ränta <?php echo sprintf("%s", $amount["percentage"]);?></th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><?php echo sprintf("%s", number_format(round($amount["lost"]), 0, '.', ' '));?></td>
        <td align="right"><?php echo sprintf("%s", gmdate('Y-m-d', $invoice["CreditPaymentInvoice"]["time"]));?></td>
        <td align="right"><?php echo sprintf("%s", number_format(round($amount["percentage_amount"]), 0, '.', ' '));?></td>
        <td align="right"><?php echo sprintf("%s SEK", number_format(round($amount["total"]), 0, '.', ' ' ));?></td>
    </tr>
    </tbody>
</table>

<table width="100%">
    <tr>
        <td align="right">
            <div style="font-weight: bold;"><?php echo sprintf("Avtalsdatum: %s", gmdate('Y-m-d', $invoice["CreditPaymentInvoice"]["time"]));?></div>
            <div style="font-weight: bold;"><?php echo sprintf("Förfallodatum: %s", gmdate('Y-m-d', strtotime(gmdate('Y-m-d', $invoice["CreditPaymentInvoice"]["time"]) . ' + 7 days')));?></div>
        </td>
    </tr>
</table>

<br />

<table width="100%">
    <tr>
        <td>
            <div>Betalningsinformation:</div>
            <div>Please make payments to our Swedish representative company bla bla bla…</div>
            <div>Failure to pay in time will result in bla bla bla...</div>
        </td>
    </tr>
</table>

<br />

<table width="100%">
    <tr>
        <td align="left">
            <div style="font-weight: bold;">Företagsnamn: Företagsnamn</div>
            <div style="font-weight: bold;">Bank-Giro: 123-1234</div>
            <div style="font-weight: bold;">Reference: <?php echo sprintf("%d", $invoice["CreditPaymentInvoice"]["id"]);?></div>
        </td>
    </tr>
</table>