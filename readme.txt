=== ANAC XML Bandi di Gara ===
Contributors: Milmor
Donate link: https://www.paypal.me/milesimarco
Tags: anac, anticorruzione, avcp, autorita, vigilanza, lavori, pubblici, amministrazione, trasparente, legge, obblighi, marco, milesi, wpgov, pubblicazione
Requires at least: 4.4
Tested up to: 6.2
Version: 7.5
Stable tag: 7.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Software per la gestione dei Bandi di Gara e generazione dataset XML per ANAC (ex AVCP -Legge 190/2012 Art 1.32)

== Description ==

ANAC XML BANDI DI GARA è un plugin WordPress per pubblicazione di bandi di gara ai fini della trasparenza delle pubbliche amministrazioni (D.lgs 33/2013) e l'adeguamento normativo richiesto dall’Autorità Nazionale Anticorruzione (specifiche tecniche art. 1 comma 32 Legge n. 190/2012).

> Questo plugin non supporta i raggruppamenti temporanei di impresa

**Flessibilità, **Semplicità** e **Intuitività** sono i 3 pilastri con cui è stato pensato questo software per la gestione **completa** dei bandi di gara. Sfruttando le potenzialità native di WordPress, questo plugin presenta un'interfaccia integrata adatta a tutti gli utenti, presentandosi come soluzione ideale per i siti della pubblica amministrazione "Powered by Wordpress" e per tutti gli enti che desiderano una soluzione gratuita, stabile, aggiornata e supportata.

> Più di **1200** portali della PA si appoggiano a questo plugin, tra cui USR Lombardia e USR Veneto

= Funzioni =
* Creazione e gestione dei bandi di gara tramite Custom Post Type (stessa impostazione di pagine e articoli)
* Creazione e gestione delle ditte tramite Taxonomy (tassonomia, stessa impostazione delle categorie)
* Assegnazione ditte partecipanti e aggiudicatari direttamente nella pagina di creazione del bando
* Generazione di tabelle di riepilogo tramite shortcode [gare] // [gare anno="2013"] // [gare anno="%%%%"]
* Generazione **automatica** o manuale del file indice XML per la trasmissione ad AVCP e delle annualità singole
* Gestione completa dei **Centri di Costo** (per scrittura dataset) e dei responsabili (per front-end sito)
* Codice leggero, commentato e facilmente modificabile
* Compatibilità completa per i temi Wordpress
* Generazione di dataset .xml vuoti
* **Esportazione**, stampa e copia dei dati delle gare per l'utente

= Caratteristiche Salienti =
Oltre all'adempimento degli obblighi di legge, AVCP XML per Wordpress offre alcune funzioni in grado di dare valore aggiunto al vostro operato:

* Visualizzazione pubblica dei file .xml in una pagina dedicata: www.example.com**/avcp**
* Visualizzazione singola delle voci, con possibilità di aggiunta testo a piacere, documenti, link,...
* Visualizzazione **archivio** di tutte le gare partecipate da ogni ditta [opzionale]

= BACKUP & RIPRISTINO =
ANAC XML permette il backup e il ripristino nativo delle voci dei bandi (per trasferimento sito Wordpress o solo per avere una copia di sicurezza). Accedendo a Strumenti -> Esporta è possibile scaricare il file xml di backup (da non confondere con quello generato per l'avcp, che ha una struttura completamente diversa). Per il ripristino delle voci in un altro sito è sufficiente caricare questo file in un'altra installazione utilizzando il menù Strumenti -> Importa

= CAMBIAMENTO PATH/URL FILE =
Il plugin integra un sistema di filtraggio per le variabili path/url dei file.

`add_filter( 'anac_filter_basexmlpath', function( $string ) { // Base PATH
    // $string = ...
    return $string;
}, 10, 3 );

add_filter( 'anac_filter_basexmlurl', function( $string ) { // Base URL
    // $string = ...
    return $string;
}, 10, 3 );`

= CONTATTI & SUPPORTO =
Per qualsiasi informazione, per segnalare problemi o per suggerire nuove funzioni, è attivo il forum di supporto su [wpgov.it/supporto](https://wpgov.it/supporto/)

http://www.youtube.com/watch?v=D_nmx_XXo8o

> **ATTENZIONE** | **"For each author’s protection [***] we want to make certain that everyone understands that there is no warranty for this free software.** In accordo con la licenza GPL v.2 con cui questo software viene fornito, **declino** ogni responsabilità per eventuali inadempimenti legislativi e/o altri problemi legali e/o tecnici derivanti, implicitamente o esplicitamente, dall'utilizzo di questo plugin Wordpress o da un'affrettata configurazione dello stesso (ivi compresi eventuali aggiornamenti). E' compito del gestore del sito assicurarsi che il modulo funzioni correttamente e adempia agli obblighi di legge e, al contempo, è obbligo degli operatori/impiegati/dipendenti/funzionari preposti alla gestione dell'Amministrazione Trasparente la pubblicazione degli opportuni dati.

> **EN** | This plugin is developed for **schools, universities, municipalities and local authorities** of **ITALY** and respects their legal parameters. The installation of this plugin on amateur websites and/or portals not subject to 'Amministrazione Trasparente' legislation is a waste of time since the purpose of this software is the posting of data in a legal and validated way.

== Installation ==

http://www.youtube.com/watch?v=D_nmx_XXo8o

Puoi trovare la documentazione su [wpgov.it](https://wpgov.it/soluzioni/avcp-xml-bandi-di-gara/)

== Screenshots ==

1. Pagina di gestione delle gare (back-end)
2. Inserimento guidato delle informazioni
3. Semplici e intuitive impostazioni
4. Menù del plugin
5. Esempio pagina /avcp contenente i file .xml generati
6. Tabella generata con lo shortcode [avcp] configurabile per anno
7. Esempio visualizzazione singola del bando cliccando sulle voci della tabella

== Changelog ==
> Questa è la lista completa di tutti gli aggiornamenti, test e correzioni. Ogni volta che una nuova versione viene rilasciata assicuratevi di aggiornare il prima possibile per usufruire delle ultime migliorie!

= 7.5 20231019 =
* Nuova gestione somme liquidate
* Ottimizzazioni e risoluzione bug minori
* Rimosso pannello di importazione in caso di gare già pubblicate
* Nuovo flusso di rilascio tecnico su Github

= 7.4 20230322 =
* Risolti warning e php notice
* Miglioramento interfaccia di validazione con ulteriori tipologie
* Miglioramento prestazionale
* Modifiche minori

= 7.3 20230217 =
* Aggiunta validazione specifiche 1.3 (retrocompatibile)
* Miglioramento interfaccia di validazione
* Miglioramento prestazionale
* Modifiche minori

= 7.2.3 20210123 =
* **Risolto problema di salvataggio somme liquidate 2021**
* Si invita ad aggiornare il plugin e a ricreare il file per l'anno 2021
* In caso di comunicazioni ANAC già effettuate, non è necessario ripetere la comunicazione
* Modifiche minori

= 7.2.2 20201129 =
* Fixed conflict with WP Attachments in some metabox configuration (backend)
* Compatiblity check - WP 5.6

= 7.2.1 20200702 =
* **Risolto** warning PHP 7.X
* Verificata compatibilità con ultima versione WP
* Modifiche minori

= 7.2.20200303 =
* Aggiunti filtri per il cambiamento path/url dei file xml (vedi readme)

= Versione 7.1.2 25.01.2020 =
* Minor bugfix

= Versione 7.1 13.01.2020 =
* **LEGGERE NOTE VERSIONE 7**
* Aggiunto supporto alle nuove modalità di affidamento della gara
* Bugfix scelta contraente (si ringraziano Annalisa D. e Salvatore F. per il tempestivo feedback)

= Versione 7 12.01.2020 =
* Redesign completo pagina di validazione
* Redesign e refactoring completo del metabox nella pagina di modifica della gara (ora diviso tra dettagli e somme liquidate)
* Aggiunto supporto fino all'anno 2024, standardizzazione di codice e automatismi per supporto ad anni futuri
* Parametrizzato sistema di get su url/path di ogni file xml per customizzazioni esterne
* Rimossa verifica automatica per autogenerazione XML (utilizzare il centro di validazione nel menù del plugin)
* Diversi miglioramenti e standardizzazione di codice
* Riordinamento filtri e ottimizzazione cicli
* Rimosso pannello **LOG** (integrato in validazione)

= Versione 6.7.3 27.12.2019 =
* Minor bugfix

= Versione 6.7.2 =
* Testato con WP 5.2
* **Corretto** errore di ordinamento nella tabella (php7)
* **Corretto** bug di visualizzazione somme liquidate 2019

= Versione 6.7.1 22/01/2019 =
* Modifiche visualizzazione singola, segnalazione di Francesco C.

= Versione 6.7 07.01.2019 =
* Le date ora sono in formato 0000-00-00 nel caso non sia settata
* Testato con WP 5.X
* Migliorato sistema di generazione file
* Aggiunto avviso per siti .gov.it nella pagina di validazione

= Versione 6.5 30.06.2017 =
* Corretto possibile conflitto a livello template single.php
* Miglioramenti prestazionali

= Versione 6.4.1 14.02.2017 = 
* Modifiche grafiche alla pagina di validazione
* Aggiunto link per validazione esterna di file xml [https://anac.softcare.it/Validator](https://anac.softcare.it/Validator) a cura di [SoftCare](http://www.softcare.it/)


= Versione 6.4 09.01.2017 =
* **Rimossa** opzione "Mostra Editor WYSIWYG" (adesso il riquadro del contenuto dei bandi è visibile quando si crea una nuova voce: per nasconderlo utilizzare "impostazioni schermo" in alto a destra)
* **Migliorate** le prestazioni
* **Corretti** alcuni warning
* **Testato** su WP 4.7

= Versione 6.3 23.11.2015 =
* Nuovo pannello impostazioni wpgov
* Aggiunto anno 2019/2020
* Miglioramenti minori

= Versione 6.2.3 - 1.06.2015 =
* Testato con la versione in sviluppo del cms

= Versione 6.2.2 - 11.03.2015 =
* Miglioramento stabilità e velocità

= Versione 6.2.1 - 06.03.2015 =
* Corretto conflitto con Amministrazione Trasparente
* Miglioramenti performance

= Versione 6.2 - 13.02.2015 =
* Miglioramento modulo wpgov

= Versione 6.1 - 04.02.2015 =
* **Aggiunta** funzione javascript per evitare spazi bianchi durante inserimento Codici Fiscali
* Corretto piccolo refuso nel pannello di validazione
* **Sistemati** difetti di importazione
* Piccoli altri miglioramenti

= Versione 6.0 - 01.02.2015 =
* **SI CONSIGLIA DI FARE UN BACKUP MYSQL PRIMA DI ESEGUIRE L'AGGIORNAMENTO**
* Riscritto sistema di gestione somme liquidate
* Aggiunto supporto fino al 2018
* Aggiunto supporto per gare > 3 anni
* Aggiunto supporto per somme liquidate dopo termine gara
* Sensibili miglioramenti performance
* Messaggi e pannelli rivisti tecnicamente e graficamente
* Aggiunta funzionalità di "log"
* Aggiunta **importazione** da file xml (sperimentale e solo per la prima volta)
* Aggiunti automatismi javascript nel back-end (creazione nuova gara)
* Migliorata tabella

= Versione 5.2.5 - 21.01.2015 =
* Corretto falso allarme in caso di gare con solo anno 2015 impostato

= Versione 5.2.4 - 16.01.2015 =
* Aggiornato modulo wpgov

= Versione 5.2.3 - 25.11.2014 =
* **Readme.txt** correction

= Versione 5.2.2 - 25.11.2014 = 
* **Corretta** mancata visualizzazione pulsanti di esportazione in alcune configurazioni
* **Migliorati** shortcode (beta)

= Versione 5.2.1 - 22.11.2014 =
* **Aggiunta** verifica per la funzione DateTime

= Versione 5.2 - 21.11.2014 =
* **Rimosso** datatable e tabletools a favore di una tabella in html più pulita
* **Rimosse** alcune opzioni legate a datatable e tabletools
* **Aggiunti** shortcode per grafici statistici (sperimentale)

= Versione 5.1.1 - 10.11.2014 [!] =
* **Corretto** malfunzionamento dell'importo somme liquidate (back-end) con il browser Firefox

= Versione 5.1 - 10.10.2014 [!!!] =
* **Aggiunta divisione somme liquidate su più anni e completo supporto durante la creazione
* **Migliorato** javascript per la creazione/modifica di una gara

= Versione 5.0.4 - 20.07.2014 =
* **Testato** su WordPress 4.0

= Versione 5.0.3 - 29.06.2014 =
* **Migliorato** pannello impostazioni condivise

= Versione 5 #Gasw (Giornata Apera sul Web) - 26.05.2014 =
* Rebranding wpgov.it
* Revisione delle divisioni delle pagine
* Molto altro, da scoprire a #gasw2014 :))

= =
* Notevoli **miglioramenti** nelle performance grazie ad alcune ottimizzazioni nel lancio di funzioni "admin_init" & "init" & "include()"
* **Rimosso** supporto compatibilità per aggiornamenti da versione < 3 del 9 gennaio 2014

= Versione 4.1.1 12/03/2014 =
* **Miglioramenti** css per i pulsanti di esportazione della tabella

= Versione 4.1 12/03/2014 =
* **Aggiunta** funzione righe multiple per la tabella
* **Aggiunti** tutti i dati delle gare per l'esportazione, copia e salvataggio della tabella
* **Aggiunta** possibilità di filtrare la tabella per dati non visibili all'utente (ditte, procedura scelta,...)
* **Corretta** mancata disabilitazione link ditte aggiudicatarie nella vista singola (se disabilitata)
* **Aggiunta** opzione per nascondere l'esportazione
* **Aggiunta** opzione per nascondere il menù centri di costo
* **Aggiunta** funzione COPIA/STAMPA tabella e raggruppamento esportazione CSV/EXCEL/PDF
* **Corretto** titolo errato file pdf esportato
* **Cambiato** messaggio tabella front-end in caso di assenza di gare (da "Errore Query" a "Nessuna gara trovata"

= Versione 4 31/01/2014 =
* **Aggiunta** gestione **Settori-Centri di Costo** (tassonomia+optionbox) con responsabile e link pagina per la vis. singola
* **Aggiunta** funzione esportazione tabella in CSV, EXCEL, PDF
* **Aggiunta** opzione per abilitare l'editor WYSIWYG (testo e file allegati)
* **Corretta** mancata disabilitazione link archivio ditte in vis. singola
* **Aggiunti** tag <acronym> per CIG + IT + EE in vis. singola
* **Migliorato** css tabella [avcp]
* **Aggiunto** numero gare nel tag <abstract> del dataset

= Versione 3.2 29/01/2014 (!) [!] [URGENTE] =
* **Corretta** lingua della data ITALIANO nella visualizzazione singola
* **Impostata** lingua per il selettore data in italiano nel back-end. L'anteprima nella cella della data sarà comunque in inglese.
* **CORRETTA** mancata validazione sceltacontraente 24/25 per apostrofo + aggiornamento automatico dello storico integrato
* **Aggiunta** validazione software per: annirif + data_inizio + data_fine

= Versione 3.1.3 27/01/2014 =
* **Aggiunto** valore default CIG: "0000000000"

= Versione 3.1.2 27/01/2014 =
* **Corretto** warning mkdir() in alcune configurazioni server

= Versione 3.1.1 26/01/2014 =
* Aggiunti importo default 0.00 aggiudicazione e somme liquidate
* Menù AVCP spostato in posizione 5 (sotto A.T.)

= Versione 3.1 26/01/2014 [!] =
* **Migliorata** validazione Javascript per gli importi || Grazie Emanuele Ferrarini
* **Corretto** errore javascript nella visualizzazione completa delle gare
* **Migliorato** sistema di migrazione dei dati (per aggiornamenti da v. < 3.0)
* **Migliorato** sistema interno di migrazione automatica dei dati
* **Aggiunto** avviso di mancato inserimento denominazione/partita iva ente
* **Aggiunta** funzione che elimina avcp/index.php alla disattivazione del plugin (i dataset rimangono)
* **Corretto** doppio spazio in 06-PROCEDURA NEGOZIATA SENZA PREVIA INDIZIONE DI##GARA (errore di Avcp, ma bisogna adeguarsi...)

= Versione 3 19/01/2014 =
* **Aggiunta** colonna "Aggiudicatari" nella visualizzazione admin delle gare, con indicatore rosso in caso di mancato inserimento
* **RIMOSSO** anno riferimento 2012, con procedura di aggiornamento automatico per le voci in "2013" (file 2012.xml rimosso)
* **AGGIUNTO** SUPPORTO PER DITTE ESTERE (IT/EE) con visualizzazione front-end
* **Aggiunta** condizione verifica curl_init per evitare errori fatali nella pagina delle Impostazioni
* **Rimosso** step di aggiornamento alla versione 2
* **Aggiunta** opzione per mappatura meta-capacità (gestione avanzata Ruoli & Permessi)

**Modifiche minori:**
* **Migliorate** diciture visualizzazione singola
* **Nuova** icona menù in stile Wordpress 3.8
* **Aggiunto** avvertimento nel menù "Ditte"
* **Aggiunta** precisazione date nel metabox nuova gara "Anno Riferimento"

* **3.0.1** -> Impostato a -1 il numero massimo di gare su cui eseguire query (illimitato)

= Versione 2.3.1 9/01/2014 =
* **Corretto** errore validazione "Warning 1549: failed to load external entity"
* **Corretta** visualizzazione di troppe notifiche in caso di generazione manuale di dataset non conformi

= Versione 2.3 8/01/2014 =
* **Rinnovata** visualizzazione singola bandi di gara, con visualizzazione aggiudicatari

= Versione 2.2 7/01/2014 =
* **Corretto** errore scrittura xml del codice fiscale e nome ditta aggiudicataria (erano invertiti) [!]
* **Corretta** mancata generazione automatica file .xml al salvataggio/pubblicazione di una gara
* Validazione automatica .xml rivista completamente per migliorare le performance del sito (non più lanciata in admin_init)
* **Migliorata** leggibilità dei messaggi

= Versione 2.1 6/01/2014 =
* **Corretti** errori di generazione della tabella shortcode filtrata per anno
* Aggiunto Anno di riferimento 2012
* **Migliorata** pagina delle impostazioni
* **Rimossa** opzione per disabilitare il caricamento aggiuntivo di css (dalla versione 2 è javascript richiesto)
* **Aggiunto** sistema di validazione AVCP (75% accuratezza), con notifica opzionale in caso di errore
* **Corretta** mancata scrittura <entePubblicatore> nella testata del file .xml

= Versione 2.0.3 2/01/2014 =
* Modifica nome immagine case-sensitive

= Versione 2.0.2 =
* Modifica nome immagine case-sensitive

= Versione 2.0.1 =
* Aggiunto file mancante (svista)

= Versione 2.0 2/01/2014 =
* **Corretta** errata generazione dell'url xml nel dataset
* **Corretta** errata generazione delle ditte partecipanti // Grazie Gianni Cepollina
* **Aggiunta** validazione campi data delle gare con obbligo di scelta dal calendario (input readonly)
* Nuovo sistema di gestione degli anni di riferimento: adesso vengono creati dal plugin (richiede step di aggiornamento 1.2 aggiuntivo)
* Corretto messaggio di errore "FATAL ERROR" causato da una continua chiamata dell' hook save_post
* **Corretti** valori "sceltacontraente" scritti nel dataset .xml
* **Corretto** orario generazione scritto nel dataset .xml
* **Aggiunta** generazione di file .xml vuoti
* Testato con WP 3.8

= Versione 1.1.2 30/12/2013 =
* **Corretto** conflitto con il plugin Amministrazione Trasparente che impediva il caricamento della funzione di ricerca nel metabox tipologie (Nuova Voce)

= Versione 1.1.1 28/12/2013 =
* **Corretto** problema di mancata generazione della data corretta nel file .xml [!]
* Data in formato d F Y nella visualizzazione singola del bando di gara

= Versione 1.1 19/12/2013 =
* **Migliore** notifica della creazione del file .xml
* Corretto Problema visualizzazione back-end dei Codici Fiscali
* Corretta scrittura partecipanti/aggiudicatari nel file .xml
* Rimosse alcune modifiche css per il backend
* Metabox anno di riferimento giallo!
* Rimosso il metabox "Campi Personalizzati" mostrato da Wordpress nella pagina di modifica delle gare (causava confusione)

= Versione 1.0.4 15/12/2013 =
* Corretti bug causa di possibili conflitti (taxfilteringbackend.php + avcp_metabox_generator.php)
* Rimossi 2 file .php attualmente inattivi

= Versione 1.0.3 10/12/2013 =
* Risolto bug mancata visualizzazione campi nella vista singola

= Versione 1.0.2 10/12/2013 =
* Migliore stile per System Check-up
* Forzato CHMOD del file /avcp/index.php - 0755
* Aggiunto parametro di controllo al System Check-up

= Versione 1.0.1 9/12/2013 =
* Correzione file readme.txt
* Nascosti i campi per la creazione gerarchica delle tassonomie, per evitare possibili conflitti

= Versione 1.0 9/12/2013 =
* Prima versione rilasciata

= 20/10/2013 =
* **Pubblicazione** sul repository WP.ORG per inizio fase di sviluppo/testing

(!) = Aggiornamento Importante (Sicurezza/Stabilità)
[!] = Nuova generazione del file .xml necessaria per adempiere agli obblighi normativi