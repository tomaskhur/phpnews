-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Počítač: localhost
-- Vytvořeno: Úte 21. bře 2023, 20:42
-- Verze serveru: 10.3.25-MariaDB-0+deb10u1
-- Verze PHP: 5.6.36-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `4a2_khurtomas_db1`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `article`
--

CREATE TABLE `article` (
  `id` int(10) UNSIGNED NOT NULL,
  `author_id` int(11) NOT NULL,
  `file_name` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `title` varchar(150) COLLATE utf8_czech_ci NOT NULL,
  `perex` text COLLATE utf8_czech_ci NOT NULL,
  `text` text COLLATE utf8_czech_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `visible` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `article`
--

INSERT INTO `article` (`id`, `author_id`, `file_name`, `title`, `perex`, `text`, `created_at`, `visible`) VALUES
(1, 1, NULL, 'Let\'s Encrypt zablokoval nebezpečnou validaci pomocí self-signed certifikátu ', 'Let\'s encrypt má první větší bezpečnostní problém. Za určitých okolností bylo možné získat certifikát i pro cizí doménové jméno. Jak zareagovali a jakým způsobem hodlají situaci řešit? ', '<h2>Jak funguje tls-sni-01</h2>\r\n<p>Validačn&iacute; metoda <code>tls-sni-01</code> je vyn&aacute;lezem tvůrců projektu autority Let\'s Encrypt. Spoč&iacute;v&aacute; ve vystaven&iacute; <em>self-signed</em> certifik&aacute;tu na neexistuj&iacute;c&iacute; dom&eacute;nov&eacute; jm&eacute;no (např&iacute;klad <code>773c7d.13445a.acme.invalid</code>), kter&eacute; obsahuje ověřovac&iacute; k&oacute;d. Certifikačn&iacute; autorita při ověřov&aacute;n&iacute; nav&aacute;že s dan&yacute;m dom&eacute;nov&yacute;m jm&eacute;nem TLS spojen&iacute; a do hlavičky SNI vlož&iacute; toto speci&aacute;ln&iacute; jm&eacute;no. K &uacute;spě&scaron;n&eacute;mu ověřen&iacute; dojde, pokud server odpov&iacute; certifik&aacute;tem vystaven&yacute;m na dan&eacute; speci&aacute;ln&iacute; jm&eacute;no.</p>\r\n<p>Validačn&iacute; metodu <code>tls-sni-01</code> použ&iacute;v&aacute; předev&scaron;&iacute;m ofici&aacute;ln&iacute; klient <a href=\"https://certbot.eff.org/\">Certbot</a>. Je v&yacute;hodn&aacute; pro automatizaci, protože vyžaduje minim&aacute;ln&iacute; konfiguračn&iacute; z&aacute;sahy do webserveru. Nen&iacute; ale jedin&aacute;, Let\'s Encrypt podporuje tak&eacute; validaci <code>http-01</code> spoč&iacute;vaj&iacute;c&iacute; ve vystaven&iacute; souboru s určit&yacute;m obsahem na určit&eacute; cestě a <code>dns-01</code>, kde k ověřen&iacute; doch&aacute;z&iacute; um&iacute;stněn&iacute;m TXT z&aacute;znamu na dom&eacute;ně <code>_acme-challenge.</code>.</p>\r\n<h2>Zranitelnost sd&iacute;len&yacute;ch hostingů</h2>\r\n<p>Podle zji&scaron;těn&iacute; Franse Ros&eacute;na existuj&iacute; provozovatel&eacute; sd&iacute;len&yacute;ch webhostingů, pro kter&eacute; ověřen&iacute; metodou <code>tls-sni-01</code> umožňuje z&iacute;skat ciz&iacute; certifik&aacute;t:</p>\r\n<ul>\r\n<li>webhostingy různ&yacute;ch z&aacute;kazn&iacute;ků sd&iacute;l&iacute; stejnou IP adresu</li>\r\n<li>uživatelům je povoleno nahr&aacute;t vlastn&iacute; TLS certifik&aacute;t bez kontroly, zda je vyd&aacute;n na dom&eacute;nov&eacute; jm&eacute;no držen&eacute; dan&yacute;m uživatelem</li>\r\n</ul>\r\n<p>Kombinace těchto dvou okolnost&iacute; pak umožňuje z&iacute;skat TLS certifik&aacute;t na libovoln&eacute; dom&eacute;nov&eacute; jm&eacute;no hostovan&eacute; na stejn&eacute; IP adrese. Mějme např&iacute;klad dvojici webov&yacute;ch prezentac&iacute;, jednu na dom&eacute;ně <code>legit.example</code>, druhou na dom&eacute;ně <code>badguy.example</code>. Prvn&iacute; patř&iacute; oběti, druh&aacute; &uacute;točn&iacute;kovi, obě sd&iacute;l&iacute; IP adresu. &Uacute;točn&iacute;k jednodu&scaron;e pož&aacute;d&aacute; autoritu o certifik&aacute;t na jm&eacute;no <code>legit.example</code> a na v&yacute;zvu autority vyrob&iacute; <em>self-signed</em> certifik&aacute;t na autoritou požadovan&eacute; jm&eacute;no, kter&yacute; nahraje jako certifik&aacute;t pro j&iacute;m ovl&aacute;dan&yacute; hosting <code>badguy.example</code>. Autorita se připoj&iacute; na IP adresu oběti, kter&aacute; je shodn&aacute; s IP adresou &uacute;točn&iacute;ka a pož&aacute;d&aacute; o speci&aacute;ln&iacute; certifik&aacute;t. Webserver ochotně vybere certifik&aacute;t poskytnut&yacute; &uacute;točn&iacute;kem, byť patř&iacute; zcela jin&eacute;mu z&aacute;kazn&iacute;kovi.</p>\r\n<p>Zranitelnost tedy postihuje <strong>v&yacute;lučně sd&iacute;len&eacute; hostingy</strong>, pro kter&eacute; jsou splněny v&yacute;&scaron;e uveden&eacute; podm&iacute;nky. Přitom už nez&aacute;lež&iacute; na ž&aacute;dn&yacute;ch dal&scaron;&iacute;ch okolnostech. Zranitelnost stejn&yacute;m způsobem funguje i pro inovovanou variantu ověřen&iacute; <code>tls-sni-02</code>, kter&aacute; je souč&aacute;st&iacute; nov&eacute;ho standardu protokolu ACME.</p>\r\n<h2>Reakce Let\'s Encrypt</h2>\r\n<p>V kr&aacute;tk&eacute; době po zji&scaron;těn&iacute; incidentu byla validace metodou <code>tls-sni-01</code> vypnuta. I přesto, že nejde o nejobl&iacute;beněj&scaron;&iacute; metodu validace (tou je <code>http-01</code>), m&aacute; sv&eacute; uživatele a velk&aacute; č&aacute;st z nich nemůže zcela automaticky přej&iacute;t na jin&yacute; druh validace. V pl&aacute;nu proto je validaci opět zprovoznit v momentě, kdy bude probl&eacute;m nějak&yacute;m způsobem vyře&scaron;en nebo obejit.</p>\r\n<p>Lid&eacute; z ISRG, organizace stoj&iacute;c&iacute; za projektem Let\'s Encrypt, se domn&iacute;vaj&iacute;, že probl&eacute;m je možn&eacute; zm&iacute;rnit implementac&iacute; silněj&scaron;&iacute;ch kontrol na straně provozovatale webhostingu, tak aby si z&aacute;kazn&iacute;k nemohl nahr&aacute;t libovoln&yacute; certifik&aacute;t. Postižen&iacute; provozovatel&eacute; jsou v kontaktu s ISRG a takov&eacute; opravy by měly b&yacute;t brzy dostupn&eacute;.</p>\r\n<p>Během n&aacute;sleduj&iacute;c&iacute;ch 48 hodin chce ISRG vytvořit seznam postižen&yacute;ch webhostingů. Jakmile bude hotov&yacute;, měla by b&yacute;t validace <code>tls-sni-01</code> znovu zprovozněna, s t&iacute;m, že pro IP adresy na seznamu bude zablokov&aacute;na.</p>\r\n<p>Dal&scaron;&iacute;m krokem je pak vyvol&aacute;n&iacute; diskuze o budoucnosti validačn&iacute; metody v r&aacute;mci komunity kolem Let\'s Encrypt a protokolu ACME. Je možn&eacute;, že po zv&aacute;žen&iacute; v&scaron;ech pro a proti bude takov&aacute;to validace prohl&aacute;&scaron;ena za zastaralou a jej&iacute; použ&iacute;v&aacute;n&iacute; bude postupně utlumov&aacute;no.</p>', '2018-01-11 11:08:38', 1),
(2, 2, NULL, 'Procesory Intel mají vážnou hardwarovou chybu, záplata výrazně snižuje výkon ', 'V procesorech Intel se nachází závažná bezpečnostní chyba, kterou nelze zcela opravit jinak než na úrovni hardwaru. Patche pro operační systém snižují výkon CPU až o desítky procent a problém se netýká jen Linuxu, ale i Windows. ', '<p>AMD stále tvrdí, že její CPU nejsou postižena (tedy přesněji řečeno, že nejde ani o zásadní, ani o obecný problém, viz <a href=\"http://www.amd.com/en/corporate/speculative-execution\">vyjádření společnosti</a>). Linus Torvalds mezitím do jádra <a href=\"https://www.phoronix.com/scan.php?page=news_item&amp;px=Linux-Tip-Git-Disable-x86-PTI\">začlenil patch</a>, který vypíná ochranu proti této chybě, tedy Page Table Isolation, pro CPU AMD. Google však naopak tvrdí, že postižena jsou i CPU ARM a AMD, nicméně blíže nic neupřesňuje (může jít tedy jen o určité architektury). Na <a href=\"https://spectreattack.com/\">webu Meltdown and Spectre</a> se hovoří o tom, že Meltdown postihuje prakticky všechna CPU od roku 1995 (kromě Intel Itanium a Atomů z doby před 2013). U Spectre je již ověřeno, že postihuje i CPU ARM a AMD.</p>\r\n\r\n<p>Bližší detailní informace shrnují dokumenty odkazované v <a href=\"https://spectreattack.com/\">dolní části webu Meltdown and Spectre</a> Google uvádí svá zjištění na <a href=\"https://googleprojectzero.blogspot.cz/2018/01/reading-privileged-memory-with-side.html\">webu týmu Zero</a>, resp. <a href=\"https://security.googleblog.com/2018/01/todays-cpu-vulnerability-what-you-need.html\">svém bezpečnostním blogu</a>.</p>\r\n\r\n<p>Úvodem nutno podotknout, že toto není <a href=\"https://www.root.cz/clanky/minix-je-zrejme-nejrozsirenejsim-systemem-je-ukryty-v-procesorech-intel/\">další článek o Intel Management Engine</a>. Jde o zcela jiný problém, pro který je v jádru 4.15 k dispozici sada opravných patchů, které byly/jsou/budou backportovány i do řad 4.14 (aktuální stabilní) a 4.9 (aktuální LTS). Podobnou věc implementují i Windows 10, v Microsoftu se na tom už <a href=\"https://twitter.com/aionescu/status/930412525111296000\">několik týdnů pracuje</a>.</p>\r\n\r\n<h2>Špatná implementace u Intelu</h2>\r\n\r\n<p>Procesory Intel totiž obsahují chybu implementace TLB (Translation Lookaside Buffer, součást CPU s nemalým dopadem na výkon), která potenciálně umožňuje útočníkovi dostat se k datům, ke kterým nemá daný uživatel systému oprávnění. Řečeno jinak: „útočník“ se může z jedné virtuální mašiny dostat k datům v paměti jiné virtuální mašiny. Problém se týká v podstatě všech CPU z posledních generací u Intelu, což mimo jiné znamená, že z něj plyne i teoretická napadnutelnost všech cloudových služeb využívajících CPU Intel (například Amazon EC2, Google Compute Engine, Microsoft Azure) či jakýchkoli jiných strojů.</p>\r\n\r\n<p>Řešení v softwarové podobě existuje, je na Linuxu implementováno jako <a href=\"https://en.wikipedia.org/wiki/Kernel_page-table_isolation\">Page Table Isolation</a>, ale představuje tak velkou zátěž z hlediska přerušení a systémových volání, že při reálném použití dochází k propadu výkonu CPU o jednotky až desítky procent. Řešení totiž spočívá v tom, že pokud program chce po jádru systému data z jeho paměti, musí nyní (patřičně opatchovaný) kernel nejprve smazat TLB cache.</p>\r\n\r\n<h2>Jak moc velký problém to je?</h2>\r\n\r\n<p>Detailní popis chyby není z pochopitelných důvodů zatím k dispozici, ale můžeme usuzovat z několika indicií. Tou první je ticho po pěšině, které se Intel snažil držet, podobně jako u Management Engine. To obvykle není dobré znamení. Tím druhým je, že změny do linuxového jádra připutovaly v rychlém sledu a dokonce jsou backportovány do starších verzí, včetně LTS.</p>\r\n\r\n<p>Úpravy existují i za cenu velké ztráty výkonu, takže je jasné, že bezpečnost (resp. závažnost problému) zde má o hodně vyšší prioritu. A v neposlední řadě s ohledem na to, že od loňského podzimu na věci pracuje i Microsoft pro Windows 10 s NT kernelem, lze to vnímat jako potvrzení hardwarové chyby.</p>\r\n\r\n<p>Objevilo se více informací o míře propadu výkonu po aplikaci patchů. <a href=\"https://www.techpowerup.com/240174/intel-secretly-firefighting-a-major-cpu-bug-affecting-datacenters\">Obecně se uvádí</a> propad na úrovni 30 až 35 %, <a href=\"https://www.phoronix.com/scan.php?page=article&amp;item=linux-415-x86pti&amp;num=1\">Phoronix provedl vlastní rozsáhlejší měření</a>. Z nich vyplynulo, že kupříkladu hry či komprese videa nejsou prakticky vůbec penalizovány. Nicméně dopady v I/O operacích, kompilačních testech či databázových (PostreSQL) jsou hodně velké.</p>\r\n\r\n<h2>AMD se to netýká</h2>\r\n\r\n<p>Důležité pro budoucí vývoj je to, že celý problém není dán návrhem x86 architektury jako takové (či nějaké pozdější instrukční sady x86 procesorů), ale konkrétní implementací konkrétní funkcionality tak, jak ji Intel ve svých CPU realizoval. Konkurenční AMD je tedy z obliga, jejích CPU se problém netýká, a platí to jak pro serverové Opterony, tak obecně pro procesory architektur Ryzen, Threadripper a EPYC.</p>\r\n\r\n<p>Pikantní ale je, že patche na tuto chybu mají velký výkonnostní dopad i na strojích s AMD CPU. Za vše totiž může aktivace <code>X86_BUG_CPU_INSECURE</code>, která vede k použití kódu, který neustále maže TLB. Toto označení je nyní aktivní pro všechny x86 CPU jako bezpečnostní opatření. AMD již <a href=\"https://lkml.org/lkml/2017/12/27/2\">řeší jeho odstranění pro svá CPU</a>.</p>\r\n\r\n<h2>Souvislosti a důsledky budou zajímavé</h2>\r\n\r\n<p>Dovolte mi nyní volněji dosadit celou věc do souvislostí. Máme zde tedy nyní procesory Intel, u kterých se pro několik posledních generací ví o dvou velkých problémech. Těmi generacemi myslím cokoli od Sandy Bridge výše (o Core 2 se už nemá smysl příliš bavit). V různých verzích x86 CPU architektur Intelu se nachází různé verze Intel Management Engine, tedy vlastní malý běžící „počítač“, aktuálně používající x86 CPU s OS Minix – to samo o sobě je potenciálně velký problém, nicméně probrali jsme ho před časem <a href=\"https://www.root.cz/clanky/minix-je-zrejme-nejrozsirenejsim-systemem-je-ukryty-v-procesorech-intel/\">v samostatném článku</a>.</p>\r\n\r\n<p>Intel si zkrátka loni svoji reputaci vůbec nevylepšil a nepřispívá tomu ani neustálé odkládání nových výrobních procesů (indikující neschopnost přivést 10nm výrobu x86 CPU k světu – a dokládají to i nejnovější neoficiální data). A nyní přichází další rána: všechny x86 procesory Intel jsou prokazatelně nebezpečné a není možné s tím nic udělat bez velké výkonnostní penalizace.</p>\r\n\r\n<p>A právě ta penalizace je věc, která Intelu dle mého ubírá obchody (vysvětlím za chvíli). Penalizaci na úrovni desítek procent si Intel mohl dovolit v době, kdy neměl konkurenci, tj. kdy AMD měla na trhu mizerné procesory typu Bulldozer/Piledriver (AMD FX 8 a 9). Nyní je situace zcela jiná, AMD má na trhu vynikající procesory od desktopů (Ryzen), přes hi-end desktopy (Threadripper) až po servery (EPYC). Intel prakticky není schopen jí konkurovat, maximálně dokáže oproti 16jádrovéhu Threadripperu s cenovkou 25 tisíc Kč postavit o trošku výkonnější 18jádrové Core i9–7980XE s cenovkou 48 tisíc Kč.</p>\r\n\r\n<p>Sousloví „o trošku výkonnější“ si ale můžeme dnes škrtnout, protože záplaty na hardwarovou chybu v procesorech Intel ubírají výrazně výkon jak na Linuxu, tak na Windows a není v moci Intelu s tím cokoli udělat (avšak nutno zdůraznit, že se to týká spíše určitých typů aplikací). Pokud tuto argumentaci přeženu, tak by na základě známých skutečností mělo být možné tvrdit, že AMD má momentálně na desktopovém trhu prokazatelně rychlejší procesor (Threadripper 1950X) za zhruba poloviční cenu oproti konkurenčnímu model Core i9–7980XE. A podobné lze tvrdit i o serverových procesorech, kde jsou cenové rozdíly mnohdy ještě zajímavější.</p>\r\n\r\n<p>Pokud byl rok 2017 z hlediska trhu x86 CPU první po letech stagnace opravdu zajímavým rokem, tak to byl teprve slaboučký odvar toho, co nás čeká letos. Už několik let tvrdím, že Intel usnul na vavřínech, stačilo mu oproti skomírající AMD pouze udržovat status quo a inovovat jen mírně. Nyní to za svůj přístup schytá a může si za to vlastně sám.</p>\r\n\r\n<p>Dlužno připomenout, že tento problém s implementací TLB cache není první. V roce 2008 <a href=\"https://www.anandtech.com/show/2477/2\">měly první AMD Phenomy též chybu v této části CPU</a> a záplata vedla též k propadům výkonu. Z hlediska architektury x86 CPU tomu tam asi bude u TLB vždy.</p>\r\n', '2018-01-11 10:51:06', 1),
(15, 4, 'images/98ba48c230f378e064a02ec15c3b7227.jpg', 'nevímcosživotem', 'nevímcosživotem', '<p>nev&iacute;mcosživotem</p>', '2022-11-06 13:33:51', 1),
(16, 4, NULL, 'pátý', 'pátý', '<p>p&aacute;t&yacute;</p>', '2022-11-07 14:04:45', 1),
(17, 4, NULL, 'šestý', 'šestý\r\n', '<p>&scaron;est&yacute;&yacute;&yacute;&yacute;&yacute;&yacute;&yacute;&yacute;&yacute;&yacute;</p>', '2022-11-07 14:07:36', 1),
(18, 14, NULL, 'testovací na delete', 'testovací na delete', '<p>testovac&iacute; na delete</p>', '2022-11-07 18:52:03', 1),
(20, 15, NULL, 'testovaci na delete 3', 'testovaci na delete 3', '<p>testovaci na delete 3</p>', '2022-11-08 08:25:10', 0),
(22, 2, NULL, 'TEST NA TEST', 'fdfsdfs', '<p>dsfsdfs</p>', '2022-12-05 19:17:50', 1),
(40, 19, 'images/98ba48c230f378e064a02ec15c3b7227.jpg', 'Test', 'Test', '<p>Test</p>', '2022-12-06 08:35:47', 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `article_category`
--

CREATE TABLE `article_category` (
  `article_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `article_category`
--

INSERT INTO `article_category` (`article_id`, `category_id`) VALUES
(2, 2),
(15, 3),
(16, 2),
(16, 3),
(17, 3),
(18, 12),
(20, 12),
(40, 2),
(40, 3);

-- --------------------------------------------------------

--
-- Struktura tabulky `author`
--

CREATE TABLE `author` (
  `id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `surname` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `visible` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `author`
--

INSERT INTO `author` (`id`, `name`, `surname`, `email`, `password`, `visible`) VALUES
(1, 'Karel', 'Vágner', 'vagnerkarel@gmail.com', '$2y$10$Gc8dCKWynyiGKf6FaLutFeyLgopIMQ6JLMusZBRaY/O0pCQJ9X2UO', 0),
(2, 'Eliška', 'Mladá', 'mladaeliska@gmail.com', '$2y$10$Gc8dCKWynyiGKf6FaLutFeyLgopIMQ6JLMusZBRaY/O0pCQJ9X2UO', 1),
(4, 'Petr', 'Pavel', 'pavelpetr@gmail.com', '$2a$12$9pd4RxyEtvvhQYc21pg3BOzmy8h293NZzdaVlHmDtVZl8mCxSRtcq', 1),
(14, 'Pes', 'Basík', 'basikpes@gmail.com', '$2a$12$23GFmVw3XlFjNE46IJDBSukSISaXYhxMu.t463XeZsq3CLT1j2cQ2', 1),
(15, 'David', 'Vangárd', 'vangarddavid@gmail.com', '$2a$12$Jl2KLx95N7CE/VusvzQKd.lUmmmW3cfRd3kajAFE6dOeiA/4NwlCe', 1),
(19, 'Karel', 'Vomáčka', 'karel@vomacka.cz', '$2y$10$40hK1eVwa2PT3n2SlcJvf.j4FnbWUMNh7NlLdfD.5QrdwTcrBENGW', 1),
(20, 'Jarmila', 'Vostrá', 'vostrajarmila@gmail.com', '$2y$10$lM8qY4W0alAlFMoMDaP01OasX0yGGcytmJgg.D4HL3JBTvHOPB2jS', 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(2, 'Hardware'),
(3, 'Test2'),
(12, 'TestDelete'),
(16, 'kategorie test');

-- --------------------------------------------------------

--
-- Struktura tabulky `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `comment` varchar(250) COLLATE utf8_czech_ci NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `image_article`
--

CREATE TABLE `image_article` (
  `id` int(11) NOT NULL,
  `file_name` varchar(255) COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `article_ibfk_2` (`file_name`);

--
-- Klíče pro tabulku `article_category`
--
ALTER TABLE `article_category`
  ADD PRIMARY KEY (`article_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Klíče pro tabulku `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `image_article`
--
ALTER TABLE `image_article`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `article`
--
ALTER TABLE `article`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pro tabulku `author`
--
ALTER TABLE `author`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pro tabulku `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pro tabulku `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pro tabulku `image_article`
--
ALTER TABLE `image_article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `article_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `author` (`id`);

--
-- Omezení pro tabulku `article_category`
--
ALTER TABLE `article_category`
  ADD CONSTRAINT `article_category_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `article_category_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
