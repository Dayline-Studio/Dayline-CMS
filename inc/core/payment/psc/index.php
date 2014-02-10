<?php
error_reporting( E_ALL );
header( "Content-Type: text/html; charset=utf-8" );
$start = microtime( true );
// |---------| Systemtest |---------|
if(phpversion() < 5){ echo 'PHP erfüllt mit der Version '.phpversion().' nicht die Voraussetzungen'; exit;}
$extensions = get_loaded_extensions();
if(!in_array('soap',$extensions)){ echo 'Die php_soap.dll wurde nicht geladen.'; exit;}
//laden der PSC-Klasse
include_once ( 'class.php' );
$currentURL = full_url();
// |------------------------------------| Setzen der Variablen |------------------------------------|

// <---- Variable Werte ---->
//muss für jede Transaktion(createDisposition) einmalig sein.
$mtid = 'Paysafecard-test_'.time().'_'.rand('1','9999999');
//Währung
$currency = 'EUR';
//Betrag
$amount = '0.01';
//MerchantClientId - z.B.: EMail-Adresse
$mCId = 'myClientI2d';

// <---- Feste Werte ---->
//Benutzername
$username = 'username';
//Passwort
$password = 'password';
//OK-URL - ok=true
//http://www.yourdomain.com/psc/index.php?ok=true&mtid='.$mtid
$okUrl = rawurlencode( $currentURL.'?ok=true&mtid='.$mtid.'&cur='.$currency.'&amo='.$amount );
//NOK-URL - nok=true
//http://www.yourdomain.com/psc/index.php?nok=true
$nokUrl = rawurlencode( $currentURL.'?nok=true' );
//PN-URL - pn=true
//http://www.yourdomain.com/psc/index.php?pn=true&mtid='.$mtid
$pnUrl = rawurlencode( $currentURL.'?pn=true&mtid='.$mtid.'&cur='.$currency.'&amo='.$amount );
//Systemsprache de/en
$sysLang = 'de';
//Debug true/false
$debug = false;
//zeige Debug an true/false
$show_debug = false;
//zeige Errors an true/false
$show_error = false;
//AutoCorrect true/false 
$autoCorrect = false;
//test oder live SYSTEM
$mode = 'test';

// |------------------------------------| Script ausführen |------------------------------------|


//einbinden der Klasse
$test = new SOPGClassicMerchantClient( $debug, $sysLang, $autoCorrect, $mode );
//wird die PHP-Datei von psc aufgerufen, wird der Bereich für die PN URL ausgeführt.
if ( isset( $_GET['pn'] ) )
{
	//die Zugangsdaten eingeben
	$test->merchant( $username, $password );
	//den aktuellen Status abfragen
	$status = $test->getSerialNumbers( $_GET['mtid'], $_GET['cur'], $subId = '' );
	//Ist die Rückgabe 'execute', kann der Betrag abgebucht werden (executeDebit)
	if ( $status === 'execute' )
	{
		$testexecute = $test->executeDebit( $_GET['amo'], '1' );
		if ( $testexecute === true )
		{
			// hier den useraccount topup -EXECUTE DEBIT SUCCESSFUL- !!!
			show_debug();
		}
	}
	show_debug();
}
elseif ( isset( $_GET['ok'] ) )
{
	//die Zugangsdaten eingeben
	$test->merchant( $username, $password );
	//den aktuellen Status abfragen
	$status = $test->getSerialNumbers( $_GET['mtid'], $_GET['cur'], $subId = '' );
	//Ist die Rückgabe 'execute', kann der Betrag abgebucht werden (executeDebit)
	if ( $status === 'execute' )
	{
		$testexecute = $test->executeDebit( $_GET['amo'], '1' );
		if ( $testexecute === true )
		{
			// hier den useraccount topup -EXECUTE DEBIT SUCCESSFUL- !!!
			show_debug();
		}
	}
	//egal ob execute ausgeführt wurde oder nicht, im Log muss eine Kundeninfo mit einer Erfolgs- oder Fehlermeldung liegen
	echo $_GET['mtid'].'<br>'.$amount.' '.$currency.'<br>';
	echo $test->getLog() . '<br />';
	
	// DEBUG & ERRORS
	show_debug();
}
elseif ( isset( $_GET['nok'] ) )
{
	//do nok
	echo 'Transaction aborted by user.';
}
//Hier startet der normale erste Aufruf
else
{
	//Setzte die Zugangsdaten
	$test->merchant( $username, $password );
	//Trage die Informationen ein.
	$test->setCustomer( $amount, $currency, $mtid, $mCId );
	//URL´s angeben.
	$test->setUrl( $okUrl, $nokUrl, $pnUrl );
	//createDisposition legt jetzt bei PSC die transaktion an und gibt dann die URL zurück, unter der der Kunde dann zahlen kann.
	//Die URL wird aber von getCustomerPanel() erzeugt!!!
	$paymentPanel = $test->createDisposition();
	if ( $paymentPanel == false )
	{
		//egal was passiert ist, es muss eine Info für den Kunden ausgegeben werden.
		echo $test->getLog() . '<br />';
		// DEBUG & ERRORS
		show_debug();
	}
	else
	{
		//Hier ist die Erstellung der Transaktion Erfolgreich abgeschlossen
		//DB Eintrag
		
		//Automatische Weiterleitung entweder durch einen Link oder durch die PHP funktion header
		
		//Header:
		header("Location:".$paymentPanel);
		//Link:
		//echo '<a href="' . $paymentPanel . '" target="_blank">weiter zum Payment Panel</a>';
		
		show_debug();
	}
}
//echo '<span style="position: absolute; bottom: 0; left: 0; width: 100%; background: #C5C5C5">Verarbeitung in: ' . ( microtime( true ) - $start ) . ' Sekunden</span>';
function show_debug()
{
    global $show_debug,$show_error,$test,$debug;
    if($show_debug === true OR $show_error === true)
    {
        echo '<div style="position: absolute; left: 0; bottom: 20px; height: 300px; width: 100%; overflow: scroll; border: 1px solid black; background: #ACACAC;">';
    }
	if ( $show_debug === true )
	{
		echo 'DEBUG:<br /> <pre>';
		var_dump( $test->debug );
		echo '</pre>';
	}
	if ( $show_error === true )
	{
		$error = $test->getLog( 'error' );
		if ( !empty( $error ) )
		{
			echo 'DEVELOPMENT-ERRORS:<br />';
			foreach ( $error as $emsg )
			{
				echo $emsg['msg'] . '<br />';
			}
		}
	}
    if($show_debug === true OR $show_error === true){echo '</div>';}
    if($debug === true)
    {
        $line = '|----- DEBUG @'.time().' -----|';
        foreach($test->debug as $key => $value)
        {$line .= $key. ' : ' .$value. "\n";}
    }   
    if($test->getLog('error') !== 0)
    {
        if(!isset($line)){$line = '|----- ERROR @'.time().' -----|';}
        else{$line .= '|----- ERROR @'.time().' -----|';}        
        foreach($test->getLog('error') as $entry)
        {$line .= serialize($entry)."\n";}  
    }
    if(isset($line))
    {
       $data = fopen('log.txt',"a+");
       fwrite($data,$line);
       fclose($data);
    } 
    
}

function full_url()
{
    $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
    $sp = strtolower($_SERVER["SERVER_PROTOCOL"]);
    $protocol = substr($sp, 0, strpos($sp, "/")) . $s;
    return $protocol . "://" . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
}
?>