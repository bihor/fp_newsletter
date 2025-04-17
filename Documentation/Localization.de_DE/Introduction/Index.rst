﻿.. include:: /Includes.rst.txt


.. _introduction:

Einleitung
==========


.. _what-it-does:

Was macht die Extension?
------------------------

Die Extension fp_newsletter wurde dazu geschrieben, eine datenschutzkonforme An- und Abmeldung zu Newslettern zu ermöglichen.
Unterstützte Tabellen: tt_address und fe_users. So kann z.B. die Extension mail oder luxletter zur Newsletter-Versendung benutzt werden.
Sämtliche Aktionen werden in einer Log-Tabelle festgehalten, damit alle Aktionen der Benutzer überprüft werden können.
Allerdings werden manche Einträge auch geändert und nicht immer neu angelegt.
So kann man als Admin sehen, wer sich wann an- oder abgemeldet hat. Erst nach einer erfolgreichen Anmeldung werden die Daten in die
tt_address-Tabelle kopiert. Die Extension kann allerdings auch ohne tt_address/fe_users benutzt werden.
Es ist einstellbar, dass ein Admin den Anmeldewunsch per E-Mail bekommt. Dann könnte der Admin die E-Mail-Adresse händisch in
einen externen Newsletter eintragen.
Bei der Double-Opt-In-Anmeldung und ggf. auch bei der Abmeldung muss man den Datenschutzbestimmungen zustimmen.
Google reCaptcha v3 oder ein mathematisches Captcha kann ggf. auch eingebunden werden.
Es gibt auch ein Widget fürs Dashboard.
Verfügbare Sprachen: englisch, deutsch, französisch und italienisch.
Die Standard-Sprache ist deutsch, aber man kann auch andere Sprachen benutzen.

Achtung!
^^^^^^^^

Diese Extension wurde nicht für multiple Newsletter-Kategorien designed. Man kann sich zwar zu mehreren Kategorien
anmelden, aber es ist nicht möglich, sich nur von speziellen Kategorien abzumelden (bei der Abmeldung)!
Der komplette Abonnent wird bei der Abmeldung gelöscht, sodass man sich nicht nur von einem speziellen Newsletter abmelden kann.
Allerdings ist seit Version 4.1.0 eine Bearbeitung der Newsletter-Daten möglich! Im Bearbeiten-Formular stehen alle
möglichen Kategorien zur Auswahl verfügbar.

Achtung!
^^^^^^^^

Wenn man Version 6 mit TYPO3 11.5 benutzt, gibt es keine Vorschau (mehr) im Backend.


.. _screenshots:

Screenshots
-----------

Einfache Ansicht des Anmelde-Formulars im FE:

.. figure:: /Images/frontend.png
   :width: 814px
   :alt: Frontend page

   Anmelde-Formular.

Danke an ...
^^^^^^^^^^^^

Diese Extension wurde programmiert von der
`fixpunkt für digitales GmbH, Bonn <https://www.fixpunkt.com/webentwicklung/typo3/typo3-extensions/>`_
und fixpunkt stellt sie der TYPO3-Community zur Verfügung.
